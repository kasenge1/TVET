<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SiteSetting;

class MpesaService
{
    protected $consumerKey;
    protected $consumerSecret;
    protected $shortcode;
    protected $passkey;
    protected $callbackUrl;
    protected $environment;

    public function __construct()
    {
        $this->consumerKey = SiteSetting::get('mpesa_consumer_key', '');
        $this->consumerSecret = SiteSetting::get('mpesa_consumer_secret', '');
        $this->shortcode = SiteSetting::get('mpesa_shortcode', '');
        $this->passkey = SiteSetting::get('mpesa_passkey', '');
        $this->callbackUrl = SiteSetting::get('mpesa_callback_url', config('app.url') . '/api/mpesa/callback');
        $this->environment = SiteSetting::get('mpesa_environment', 'sandbox'); // sandbox or production
    }

    /**
     * Get the base URL based on environment
     */
    protected function getBaseUrl(): string
    {
        return $this->environment === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    /**
     * Generate access token for M-Pesa API
     */
    public function getAccessToken(): ?string
    {
        try {
            $url = $this->getBaseUrl() . '/oauth/v1/generate?grant_type=client_credentials';

            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get($url);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            Log::error('M-Pesa Access Token Error', ['response' => $response->json()]);
            return null;
        } catch (\Exception $e) {
            Log::error('M-Pesa Access Token Exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Generate password for STK Push
     */
    protected function generatePassword(): string
    {
        $timestamp = now()->format('YmdHis');
        return base64_encode($this->shortcode . $this->passkey . $timestamp);
    }

    /**
     * Initiate STK Push request
     */
    public function stkPush(string $phoneNumber, float $amount, string $accountReference, string $description): array
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Failed to get access token',
            ];
        }

        $timestamp = now()->format('YmdHis');
        $password = $this->generatePassword();

        // Format phone number (remove leading 0 or +254)
        $phoneNumber = $this->formatPhoneNumber($phoneNumber);

        $url = $this->getBaseUrl() . '/mpesa/stkpush/v1/processrequest';

        try {
            $response = Http::withToken($accessToken)
                ->post($url, [
                    'BusinessShortCode' => $this->shortcode,
                    'Password' => $password,
                    'Timestamp' => $timestamp,
                    'TransactionType' => 'CustomerPayBillOnline',
                    'Amount' => (int) $amount,
                    'PartyA' => $phoneNumber,
                    'PartyB' => $this->shortcode,
                    'PhoneNumber' => $phoneNumber,
                    'CallBackURL' => $this->callbackUrl,
                    'AccountReference' => $accountReference,
                    'TransactionDesc' => $description,
                ]);

            $result = $response->json();

            if ($response->successful() && isset($result['ResponseCode']) && $result['ResponseCode'] === '0') {
                return [
                    'success' => true,
                    'message' => 'STK Push sent successfully',
                    'checkout_request_id' => $result['CheckoutRequestID'],
                    'merchant_request_id' => $result['MerchantRequestID'],
                ];
            }

            Log::error('M-Pesa STK Push Error', ['response' => $result]);
            return [
                'success' => false,
                'message' => $result['errorMessage'] ?? $result['ResponseDescription'] ?? 'STK Push failed',
            ];
        } catch (\Exception $e) {
            Log::error('M-Pesa STK Push Exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'An error occurred while processing payment',
            ];
        }
    }

    /**
     * Query STK Push transaction status
     */
    public function queryStatus(string $checkoutRequestId): array
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Failed to get access token',
            ];
        }

        $timestamp = now()->format('YmdHis');
        $password = $this->generatePassword();

        $url = $this->getBaseUrl() . '/mpesa/stkpushquery/v1/query';

        try {
            $response = Http::withToken($accessToken)
                ->post($url, [
                    'BusinessShortCode' => $this->shortcode,
                    'Password' => $password,
                    'Timestamp' => $timestamp,
                    'CheckoutRequestID' => $checkoutRequestId,
                ]);

            $result = $response->json();

            if ($response->successful() && isset($result['ResultCode'])) {
                return [
                    'success' => $result['ResultCode'] === '0',
                    'message' => $result['ResultDesc'] ?? 'Unknown status',
                    'result_code' => $result['ResultCode'],
                ];
            }

            return [
                'success' => false,
                'message' => $result['errorMessage'] ?? 'Query failed',
            ];
        } catch (\Exception $e) {
            Log::error('M-Pesa Query Exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'An error occurred while querying status',
            ];
        }
    }

    /**
     * Format phone number to 254XXXXXXXXX format
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any spaces, dashes, or special characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // If starts with 0, replace with 254
        if (str_starts_with($phoneNumber, '0')) {
            $phoneNumber = '254' . substr($phoneNumber, 1);
        }

        // If starts with +254, remove the +
        if (str_starts_with($phoneNumber, '+')) {
            $phoneNumber = substr($phoneNumber, 1);
        }

        // If doesn't start with 254, add it
        if (!str_starts_with($phoneNumber, '254')) {
            $phoneNumber = '254' . $phoneNumber;
        }

        return $phoneNumber;
    }

    /**
     * Check if M-Pesa is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->consumerKey)
            && !empty($this->consumerSecret)
            && !empty($this->shortcode)
            && !empty($this->passkey);
    }
}
