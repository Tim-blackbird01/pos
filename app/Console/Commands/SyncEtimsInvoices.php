<?php

namespace App\Console\Commands;

use App\EtimsInvoice;
use App\Services\EtimsService;
use App\Transaction;
use Illuminate\Console\Command;

class SyncEtimsInvoices extends Command
{
    protected $signature = 'etims:sync {transaction_id? : Retry one POS transaction} {--failed : Retry every failed eTIMS invoice}';

    protected $description = 'Retry failed KRA eTIMS invoice submissions.';

    public function handle(EtimsService $etims): int
    {
        $transactionId = $this->argument('transaction_id');
        if ($transactionId) {
            $transactions = Transaction::whereKey($transactionId)->where('type', 'sell')->where('status', 'final')->get();
        } elseif ($this->option('failed')) {
            $transactions = Transaction::whereIn('id', EtimsInvoice::where('status', 'failed')->pluck('transaction_id'))
                ->where('type', 'sell')
                ->where('status', 'final')
                ->get();
        } else {
            $this->error('Provide a transaction ID or use --failed.');

            return self::FAILURE;
        }

        foreach ($transactions as $transaction) {
            $invoice = $etims->submit($transaction);
            $this->line("{$transaction->invoice_no}: {$invoice->status}");
        }

        return self::SUCCESS;
    }
}
