<?php

namespace Modules\Mpesa\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Mpesa\Entities\MpesaSetting;

class DarajaService
{
    /** @var MpesaSetting */
    protected $settings;

    protected $baseUrl;

    public function __construct(MpesaSetting $settings)
    {
        $this->settings = $settings;

        $this->baseUrl = $settings->environment === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    public static function forBusiness(int $businessId): self
    {
        $settings = MpesaSetting::getForBusiness($businessId);

        if (!$settings || !$settings->is_enabled) {
            throw new \Exception('M-Pesa is not configured / enabled for this business. Go to M-Pesa > Settings to set it up.');
        }

        if (empty($settings->consumer_key) || empty($settings->consumer_secret)) {
            throw new \Exception('M-Pesa Consumer Key / Consumer Secret are missing. Please update M-Pesa settings.');
        }

        return new self($settings);
    }

   public function getAccessToken(): string
{
    $cacheKey = 'mpesa_access_token_' . $this->settings->business_id . '_' . $this->settings->environment;

    // Sandbox tokens expire unpredictably — skip cache in sandbox
    if ($this->settings->environment !== 'production') {
        Cache::forget($cacheKey);
    }

    return Cache::remember($cacheKey, 55 * 60, function () {
            Log::info('Fetching M-Pesa token', [
                'url' => $this->baseUrl . '/oauth/v1/generate?grant_type=client_credentials',
                'key' => $this->settings->consumer_key,
            ]);

          $response = Http::withBasicAuth(
    $this->settings->consumer_key,
    $this->settings->consumer_secret
)->withHeaders([
    'User-Agent' => 'Mozilla/5.0 (compatible; MyApp/1.0)',
])->get($this->baseUrl . '/oauth/v1/generate?grant_type=client_credentials');

            Log::info('M-Pesa token response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to authenticate with M-Pesa: ' . $response->body());
            }

            $data = $response->json();

            if (empty($data['access_token'])) {
                throw new \Exception('M-Pesa did not return an access token: ' . $response->body());
            }

            return $data['access_token'];
        });
    }

    protected function getStkCredentials(): array
    {
        $timestamp = Carbon::now()->format('YmdHis');
        $password = base64_encode($this->settings->shortcode . $this->settings->passkey . $timestamp);

        return [
            'timestamp' => $timestamp,
            'password' => $password,
        ];
    }

    public function stkPush(string $phone, float $amount, string $accountRef, string $callbackUrl, ?string $description = null): array
    {
        Log::info('stkPush called', [
            'phone' => $phone,
            'amount' => $amount,
            'accountRef' => $accountRef,
            'callbackUrl' => $callbackUrl,
        ]);

        $token = $this->getAccessToken();

        Log::info('Token being used for STK Push', ['token' => $token]);

        $credentials = $this->getStkCredentials();

        $transactionType = $this->settings->shortcode_type === 'till'
            ? 'CustomerBuyGoodsOnline'
            : 'CustomerPayBillOnline';

        $payload = [
            'BusinessShortCode' => $this->settings->shortcode,
            'Password'          => $credentials['password'],
            'Timestamp'         => $credentials['timestamp'],
            'TransactionType'   => $transactionType,
            'Amount'            => (int) round($amount),
            'PartyA'            => $phone,
            'PartyB'            => $this->settings->shortcode,
            'PhoneNumber'       => $phone,
            'CallBackURL'       => $callbackUrl,
            'AccountReference'  => $accountRef ?: ($this->settings->account_reference ?: 'POS Payment'),
            'TransactionDesc'   => $description ?: ($this->settings->transaction_desc ?: 'Payment'),
        ];

        // Test with a direct curl-style request to rule out Http facade issues
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/mpesa/stkpush/v1/processrequest');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MyApp/1.0)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ]);
        $curlResponse = curl_exec($ch);
        $curlStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        Log::info('STK Push cURL response', [
            'status' => $curlStatus,
            'body'   => $curlResponse,
            'token'  => $token,
        ]);

        $data = json_decode($curlResponse, true);

        if ($curlStatus < 200 || $curlStatus >= 300) {
            $message = $data['errorMessage'] ?? $data['error_description'] ?? 'Unknown error from M-Pesa';
            throw new \Exception('STK Push failed: ' . $message);
        }

        return $data;
    }

    public function stkQuery(string $checkoutRequestId): array
    {
        $credentials = $this->getStkCredentials();

        $payload = [
            'BusinessShortCode' => $this->settings->shortcode,
            'Password'          => $credentials['password'],
            'Timestamp'         => $credentials['timestamp'],
            'CheckoutRequestID' => $checkoutRequestId,
        ];

        $response = Http::withToken($this->getAccessToken())
            ->post($this->baseUrl . '/mpesa/stkpushquery/v1/query', $payload);

        return $response->json();
    }

    public function registerC2BUrls(string $confirmationUrl, string $validationUrl): array
    {
        $payload = [
            'ShortCode'       => $this->settings->shortcode,
            'ResponseType'    => 'Completed',
            'ConfirmationURL' => $confirmationUrl,
            'ValidationURL'   => $validationUrl,
        ];

        $response = Http::withToken($this->getAccessToken())
            ->post($this->baseUrl . '/mpesa/c2b/v1/registerurl', $payload);

        $data = $response->json();

        if (!$response->successful()) {
            $message = $data['errorMessage'] ?? 'Unknown error registering C2B URLs';
            throw new \Exception('C2B URL registration failed: ' . $message);
        }

        return $data;
    }
}