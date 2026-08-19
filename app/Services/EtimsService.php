<?php

namespace App\Services;

use App\EtimsInvoice;
use App\Business;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class EtimsService
{
    /** Load eTIMS credentials for one business only. */
    protected function configureFor(Transaction $transaction): bool
    {
        $settings = Business::findOrFail($transaction->business_id)->etims_settings ?? [];
        if (empty($settings['enabled'])) {
            return false;
        }

        $environment = $settings['environment'] ?? 'sandbox';
        $baseUrl = $settings['base_url'] ?? ($environment === 'production'
            ? 'https://etims-api.kra.go.ke/etims-api/'
            : 'https://etims-api-sbx.kra.go.ke/etims-api/');

        // Explicitly set every credential for this sale. There is no .env
        // fallback, so another subscriber's eTIMS account can never be used.
        foreach (['tin', 'branch_id', 'device_serial_number', 'communication_key', 'item_classification_code', 'item_code_prefix', 'package_unit_code', 'quantity_unit_code', 'default_tax_code', 'zero_tax_code', 'default_payment_code'] as $key) {
            config(['etims.'.$key => $settings[$key] ?? null]);
        }
        config([
            'etims.enabled' => true,
            'etims.environment' => $environment,
            'etims.base_url' => $baseUrl,
            'etims.tax_rates' => $settings['tax_rates'] ?? ['A' => 0, 'B' => 16],
            'etims.timeout' => 20,
            'etims.package_unit_code' => $settings['package_unit_code'] ?? 'NT',
            'etims.quantity_unit_code' => $settings['quantity_unit_code'] ?? 'U',
            'etims.default_tax_code' => $settings['default_tax_code'] ?? 'B',
            'etims.zero_tax_code' => $settings['zero_tax_code'] ?? 'A',
            'etims.default_payment_code' => $settings['default_payment_code'] ?? '01',
            'etims.item_code_prefix' => $settings['item_code_prefix'] ?? '',
        ]);

        return true;
    }

    public function submit(Transaction $transaction): EtimsInvoice
    {
        if (! $this->configureFor($transaction)) {
            throw new InvalidArgumentException('eTIMS is not enabled for this business.');
        }

        $invoice = DB::transaction(function () use ($transaction) {
            $invoice = EtimsInvoice::where('transaction_id', $transaction->id)->lockForUpdate()->first();

            if ($invoice && $invoice->status === 'accepted') {
                return $invoice;
            }

            return $invoice ?: EtimsInvoice::create([
                'transaction_id' => $transaction->id,
                'trader_invoice_number' => (string) $transaction->invoice_no,
                'status' => 'pending',
            ]);
        });

        if ($invoice->status === 'accepted') {
            return $invoice;
        }

        try {
            $this->assertConfigured();
            $transaction->loadMissing(['sell_lines.product', 'sell_lines.variations', 'contact', 'payment_lines']);
            $payload = $this->makeSalesPayload($transaction);
            $response = Http::acceptJson()
                ->asJson()
                ->timeout(config('etims.timeout'))
                ->post($this->url('/saveTrnsSalesOsdc'), $payload);

            $body = $response->json();
            if (! is_array($body)) {
                $body = ['resultCd' => (string) $response->status(), 'resultMsg' => $response->body()];
            }

            if (! $response->successful() || ($body['resultCd'] ?? null) !== '000') {
                throw new \RuntimeException($body['resultMsg'] ?? 'eTIMS rejected the invoice.');
            }

            $data = $body['data'] ?? [];
            $invoice->fill([
                'status' => 'accepted',
                'etims_invoice_number' => $payload['invcNo'],
                'current_receipt_number' => $data['curRcptNo'] ?? null,
                'total_receipt_number' => $data['totRcptNo'] ?? null,
                'internal_data' => $data['intrlData'] ?? null,
                'receipt_signature' => $data['rcptSign'] ?? null,
                'sdc_datetime' => $data['sdcDateTime'] ?? null,
                'error_message' => null,
                'request_payload' => $this->redact($payload),
                'response_payload' => $body,
                'submitted_at' => now(),
            ])->save();
        } catch (\Throwable $exception) {
            $invoice->forceFill([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'submitted_at' => now(),
            ])->save();

            Log::warning('eTIMS invoice submission failed.', [
                'transaction_id' => $transaction->id,
                'message' => $exception->getMessage(),
            ]);
        }

        return $invoice->fresh();
    }

    public function initializeDevice(): array
    {
        foreach (['tin', 'branch_id', 'device_serial_number'] as $key) {
            if (blank(config('etims.'.$key))) {
                throw new InvalidArgumentException('ETIMS_'.strtoupper($key).' is required.');
            }
        }

        $response = Http::acceptJson()->asJson()->timeout(config('etims.timeout'))->post($this->url('/selectInitOsdcInfo'), [
            'tin' => config('etims.tin'),
            'bhfId' => config('etims.branch_id'),
            'dvcSrlNo' => config('etims.device_serial_number'),
        ]);

        return $response->json() ?: ['resultCd' => (string) $response->status(), 'resultMsg' => $response->body()];
    }

    protected function makeSalesPayload(Transaction $transaction): array
    {
        $now = Carbon::parse($transaction->transaction_date ?: now());
        $taxRates = config('etims.tax_rates');
        $buckets = [];
        foreach ($taxRates as $code => $rate) {
            $buckets[$code] = ['taxable' => 0.0, 'tax' => 0.0, 'rate' => (float) $rate];
        }

        $items = [];
        foreach ($transaction->sell_lines->whereNull('parent_sell_line_id')->values() as $index => $line) {
            $quantity = round((float) $line->quantity, 2);
            $discount = round((float) $line->get_discount_amount(), 2);
            $total = round(((float) $line->unit_price_inc_tax * $quantity) - $discount, 2);
            $taxAmount = round((float) $line->item_tax * $quantity, 2);
            $taxable = round($total - $taxAmount, 2);
            $code = $this->taxCode($taxAmount, $total);
            if (! isset($buckets[$code])) {
                $buckets[$code] = ['taxable' => 0.0, 'tax' => 0.0, 'rate' => 0.0];
            }
            $buckets[$code]['taxable'] += $taxable;
            $buckets[$code]['tax'] += $taxAmount;

            $sku = optional($line->variations)->sub_sku ?: optional($line->product)->sku;
            if (blank($sku)) {
                throw new InvalidArgumentException("Product on sale {$transaction->invoice_no} has no SKU for eTIMS item code.");
            }

            $items[] = [
                'itemSeq' => $index + 1,
                'itemClsCd' => config('etims.item_classification_code'),
                'itemCd' => substr(config('etims.item_code_prefix').$sku, 0, 20),
                'itemNm' => substr(optional($line->product)->name ?: optional($line->variations)->name, 0, 200),
                'bcd' => optional($line->variations)->sub_sku,
                'pkgUnitCd' => config('etims.package_unit_code'),
                'pkg' => $quantity,
                'qtyUnitCd' => config('etims.quantity_unit_code'),
                'qty' => $quantity,
                'prc' => round((float) $line->unit_price_inc_tax, 2),
                'splyAmt' => $taxable,
                'dcRt' => (float) $line->line_discount_amount,
                'dcAmt' => $discount,
                'taxTyCd' => $code,
                'taxblAmt' => $taxable,
                'taxAmt' => $taxAmount,
                'totAmt' => $total,
            ];
        }

        if (empty($items)) {
            throw new InvalidArgumentException('A final sale needs at least one product line before it can be sent to eTIMS.');
        }

        $bucketFields = [];
        foreach (['A', 'B', 'C', 'D', 'E'] as $code) {
            $bucket = $buckets[$code] ?? ['taxable' => 0, 'tax' => 0, 'rate' => 0];
            $bucketFields['taxblAmt'.$code] = round($bucket['taxable'], 2);
            $bucketFields['taxRt'.$code] = $bucket['rate'];
            $bucketFields['taxAmt'.$code] = round($bucket['tax'], 2);
        }

        $customer = $transaction->contact;
        $totalTaxable = round(array_sum(array_column($buckets, 'taxable')), 2);
        $totalTax = round(array_sum(array_column($buckets, 'tax')), 2);
        $total = round($totalTaxable + $totalTax, 2);

        return array_merge([
            'tin' => config('etims.tin'),
            'bhfId' => config('etims.branch_id'),
            'cmcKey' => config('etims.communication_key'),
            'trdInvcNo' => (string) $transaction->invoice_no,
            'invcNo' => $transaction->id,
            'orgInvcNo' => 0,
            'custTin' => optional($customer)->tax_number,
            'custNm' => optional($customer)->name,
            'rcptTyCd' => 'S',
            'pmtTyCd' => $this->paymentCode($transaction),
            'salesSttsCd' => '02',
            'cfmDt' => $now->format('YmdHis'),
            'salesDt' => $now->format('Ymd'),
            'stockRlsDt' => $now->format('YmdHis'),
            'cnclReqDt' => null,
            'cnclDt' => null,
            'rfdDt' => null,
            'rfdRsnCd' => null,
            'totItemCnt' => count($items),
            'totTaxblAmt' => $totalTaxable,
            'totTaxAmt' => $totalTax,
            'totAmt' => $total,
            'prchrAcptcYn' => 'N',
            'remark' => null,
            'regrId' => (string) $transaction->created_by,
            'regrNm' => 'Ultimate POS',
            'modrId' => (string) $transaction->created_by,
            'modrNm' => 'Ultimate POS',
            'receipt' => [
                'custTin' => optional($customer)->tax_number,
                'custMblNo' => optional($customer)->mobile,
                'rcptPbctDt' => $now->format('YmdHis'),
                'trdeNm' => null,
                'adrs' => null,
                'topMsg' => null,
                'btmMsg' => null,
                'prchrAcptcYn' => 'N',
            ],
            'itemList' => $items,
        ], $bucketFields);
    }

    protected function taxCode(float $taxAmount, float $total): string
    {
        return abs($taxAmount) < 0.005 || abs($total) < 0.005
            ? config('etims.zero_tax_code')
            : config('etims.default_tax_code');
    }

    protected function paymentCode(Transaction $transaction): string
    {
        return config('etims.default_payment_code');
    }

    protected function assertConfigured(): void
    {
        foreach (['tin', 'branch_id', 'communication_key', 'item_classification_code'] as $key) {
            if (blank(config('etims.'.$key))) {
                throw new InvalidArgumentException('ETIMS_'.strtoupper($key).' is required before submitting invoices.');
            }
        }
    }

    protected function redact(array $payload): array
    {
        $payload['cmcKey'] = '[redacted]';

        return $payload;
    }

    protected function url(string $path): string
    {
        return rtrim(config('etims.base_url'), '/').'/'.ltrim($path, '/');
    }
}
