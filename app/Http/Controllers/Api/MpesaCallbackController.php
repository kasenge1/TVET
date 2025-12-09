<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SiteSetting;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MpesaCallbackController extends Controller
{
    protected EmailService $emailService;

    /**
     * Safaricom IP ranges for callback validation
     * These are the known Safaricom/M-Pesa API server IPs
     */
    protected array $safaricomIps = [
        '196.201.214.200',
        '196.201.214.206',
        '196.201.213.114',
        '196.201.214.207',
        '196.201.214.208',
        '196.201.213.44',
        '196.201.212.127',
        '196.201.212.138',
        '196.201.212.129',
        '196.201.212.136',
        '196.201.212.74',
        '196.201.212.69',
    ];

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Handle M-Pesa STK Push callback
     */
    public function handleCallback(Request $request)
    {
        // Log callback receipt with masked sensitive data
        Log::channel('mpesa')->info('M-Pesa Callback Received', [
            'ip' => $request->ip(),
            'has_data' => !empty($request->all()),
        ]);

        // Validate IP in production (skip in sandbox/local)
        if (!$this->isValidSourceIp($request->ip())) {
            Log::channel('mpesa')->warning('M-Pesa Callback: Invalid source IP', [
                'ip' => $request->ip(),
            ]);
            // Still process in case IP list is outdated, but log warning
        }

        // Parse callback data - handle both nested and flat structures
        $callbackData = $request->input('Body.stkCallback') ?? $request->input('stkCallback');

        if (!$callbackData) {
            Log::channel('mpesa')->error('M-Pesa Callback: Invalid data structure', [
                'raw_data' => $request->all(),
            ]);
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Invalid data structure']);
        }

        $checkoutRequestId = $callbackData['CheckoutRequestID'] ?? null;
        $merchantRequestId = $callbackData['MerchantRequestID'] ?? null;
        $resultCode = $callbackData['ResultCode'] ?? null;
        $resultDesc = $callbackData['ResultDesc'] ?? null;

        // Validate required fields
        if (!$checkoutRequestId) {
            Log::channel('mpesa')->error('M-Pesa Callback: Missing CheckoutRequestID');
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Missing CheckoutRequestID']);
        }

        // Find the subscription by checkout request ID
        $subscription = Subscription::where('mpesa_checkout_id', $checkoutRequestId)->first();

        if (!$subscription) {
            // Try finding by merchant request ID as fallback
            if ($merchantRequestId) {
                $subscription = Subscription::where('mpesa_merchant_id', $merchantRequestId)->first();
            }

            if (!$subscription) {
                Log::channel('mpesa')->error('M-Pesa Callback: Subscription not found', [
                    'checkout_id' => $checkoutRequestId,
                    'merchant_id' => $merchantRequestId,
                ]);
                return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Subscription not found']);
            }
        }

        // Use database transaction for data integrity
        DB::beginTransaction();

        try {
            if ($resultCode === 0 || $resultCode === '0') {
                // Payment successful - ensure idempotency (don't process twice)
                if ($subscription->status === 'active') {
                    DB::rollBack();
                    Log::channel('mpesa')->info('M-Pesa Callback: Subscription already active, skipping duplicate', [
                        'subscription_id' => $subscription->id,
                    ]);
                    return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Already processed']);
                }

                $callbackMetadata = $callbackData['CallbackMetadata']['Item'] ?? [];
                $transactionData = $this->parseCallbackMetadata($callbackMetadata);

                // Validate amount matches (security check)
                if (isset($transactionData['amount']) && abs($transactionData['amount'] - $subscription->amount) > 1) {
                    Log::channel('mpesa')->warning('M-Pesa Callback: Amount mismatch', [
                        'subscription_id' => $subscription->id,
                        'expected' => $subscription->amount,
                        'received' => $transactionData['amount'],
                    ]);
                    // Continue processing but log the discrepancy
                }

                $subscription->update([
                    'status' => 'active',
                    'payment_method' => 'mpesa',
                    'transaction_id' => $transactionData['mpesa_receipt'] ?? null,
                    'mpesa_result_code' => $resultCode,
                    'mpesa_result_desc' => $resultDesc,
                    'starts_at' => now(),
                ]);

                DB::commit();

                Log::channel('mpesa')->info('M-Pesa Payment Successful', [
                    'subscription_id' => $subscription->id,
                    'user_id' => $subscription->user_id,
                    'amount' => $subscription->amount,
                    'transaction_id' => $transactionData['mpesa_receipt'] ?? null,
                    // Mask phone number for privacy - only show last 4 digits
                    'phone_masked' => isset($transactionData['phone_number'])
                        ? '******' . substr($transactionData['phone_number'], -4)
                        : null,
                ]);

                // Send subscription confirmation email (non-blocking)
                $this->sendConfirmationEmail($subscription, $transactionData);

            } else {
                // Payment failed or cancelled
                $failureStatus = $this->determineFailureStatus($resultCode);

                $subscription->update([
                    'status' => $failureStatus,
                    'mpesa_result_code' => $resultCode,
                    'mpesa_result_desc' => $resultDesc,
                ]);

                DB::commit();

                Log::channel('mpesa')->warning('M-Pesa Payment Failed', [
                    'subscription_id' => $subscription->id,
                    'user_id' => $subscription->user_id,
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc,
                    'status' => $failureStatus,
                ]);

                // Send payment failed email (non-blocking)
                $this->sendFailureEmail($subscription, $resultDesc);
            }

            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Callback processed successfully']);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::channel('mpesa')->error('M-Pesa Callback: Processing error', [
                'subscription_id' => $subscription->id ?? null,
                'checkout_id' => $checkoutRequestId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return success to M-Pesa to prevent retries, but log the error
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Callback received']);
        }
    }

    /**
     * Validate if the request is from Safaricom
     */
    protected function isValidSourceIp(string $ip): bool
    {
        // Skip validation in local/sandbox environment
        $environment = SiteSetting::get('mpesa_environment', 'sandbox');
        if ($environment === 'sandbox' || app()->environment('local', 'testing')) {
            return true;
        }

        // Check if IP is in allowed list
        if (in_array($ip, $this->safaricomIps)) {
            return true;
        }

        // Check if IP is in Safaricom range (196.201.212.0/22)
        $ipLong = ip2long($ip);
        $rangeStart = ip2long('196.201.212.0');
        $rangeEnd = ip2long('196.201.215.255');

        return $ipLong >= $rangeStart && $ipLong <= $rangeEnd;
    }

    /**
     * Determine the failure status based on result code
     */
    protected function determineFailureStatus(int|string $resultCode): string
    {
        return match ((int) $resultCode) {
            1032 => 'cancelled',      // User cancelled
            1037 => 'timeout',        // Timeout waiting for user
            1025 => 'failed',         // Invalid credentials
            1 => 'failed',            // General failure
            default => 'failed',
        };
    }

    /**
     * Parse callback metadata to extract transaction details
     */
    protected function parseCallbackMetadata(array $items): array
    {
        $data = [];

        foreach ($items as $item) {
            $name = $item['Name'] ?? null;
            $value = $item['Value'] ?? null;

            switch ($name) {
                case 'Amount':
                    $data['amount'] = (float) $value;
                    break;
                case 'MpesaReceiptNumber':
                    $data['mpesa_receipt'] = $value;
                    break;
                case 'TransactionDate':
                    $data['transaction_date'] = $value;
                    break;
                case 'PhoneNumber':
                    $data['phone_number'] = (string) $value;
                    break;
                case 'Balance':
                    $data['balance'] = $value;
                    break;
            }
        }

        return $data;
    }

    /**
     * Send confirmation email (non-blocking)
     */
    protected function sendConfirmationEmail(Subscription $subscription, array $transactionData): void
    {
        try {
            $user = $subscription->user;
            $package = $subscription->package;

            if ($user) {
                $this->emailService->sendSubscriptionConfirmation($user, [
                    'package_name' => $package?->name ?? 'Premium',
                    'amount' => $subscription->amount,
                    'expires_at' => $subscription->expires_at,
                    'transaction_id' => $transactionData['mpesa_receipt'] ?? null,
                ]);
            }
        } catch (\Exception $e) {
            Log::channel('mpesa')->error('M-Pesa: Failed to send confirmation email', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send failure email (non-blocking)
     */
    protected function sendFailureEmail(Subscription $subscription, ?string $resultDesc): void
    {
        try {
            $user = $subscription->user;

            if ($user) {
                $message = $this->getHumanReadableError($resultDesc);
                $this->emailService->sendPaymentFailed($user, $message);
            }
        } catch (\Exception $e) {
            Log::channel('mpesa')->error('M-Pesa: Failed to send failure email', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Convert M-Pesa error to human-readable message
     */
    protected function getHumanReadableError(?string $resultDesc): string
    {
        if (!$resultDesc) {
            return 'Payment could not be processed. Please try again.';
        }

        $errorMappings = [
            'insufficient' => 'Insufficient M-Pesa balance. Please top up and try again.',
            'cancel' => 'Payment was cancelled. You can try again when ready.',
            'timeout' => 'Payment request timed out. Please try again.',
            'wrong pin' => 'Incorrect M-Pesa PIN entered. Please try again.',
            'locked' => 'Your M-Pesa account is locked. Please contact Safaricom.',
        ];

        $lowerDesc = strtolower($resultDesc);

        foreach ($errorMappings as $keyword => $message) {
            if (str_contains($lowerDesc, $keyword)) {
                return $message;
            }
        }

        return 'Payment could not be processed: ' . $resultDesc;
    }
}
