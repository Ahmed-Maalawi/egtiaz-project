<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentGatewayService
{
    protected $baseUrl;
    protected $entityId;
    protected $authToken;
    protected $shopperResultUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.afs_payment_gateway.url');
        $this->entityId = config('services.afs_payment_gateway.entity_id');
        $this->authToken = config('services.afs_payment_gateway.auth_token');
        $this->shopperResultUrl = config('services.afs_payment_gateway.shopper_result_url');
    }

    /**
     * Generate payment link for wallet charging
     *
     * @param float $amount
     * @param int $userId
     * @param array $customerData
     * @param string $currency
     * @return array
     */
    public function generatePaymentLink($amount, $userId, $customerData = [], $currency = 'USD')
    {
        $merchantTransactionId = $this->generateTransactionId($userId);

        $params = [
            'entityId' => $this->entityId,
            'amount' => number_format($amount, 2, '.', ''),
            'currency' => $currency,
            'paymentType' => 'DB', // Debit transaction
            'merchantTransactionId' => $merchantTransactionId,
            'shopperResultUrl' => $this->shopperResultUrl,

            // Customer details
            'customer.givenName' => $customerData['role'] ?? '',
            'customer.surname' => $customerData['name'] ?? '',
            'customer.email' => $customerData['email'] ?? '',

            // Link validity
            'validUntil' => 1,
            'validUntilUnit' => 'DAY',

            // Create QR Code for easy mobile payment
            'createQRCode' => 'false',

            // Custom parameters to track user
            'customParameters[user_id]' => $userId,
            'customParameters[type]' => 'wallet_charge',
        ];

        // Optional: Add billing information if required
        if (isset($customerData['billing'])) {
            $params = array_merge($params, $this->formatBillingData($customerData['billing']));
        }


        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->authToken,
            ])->asForm()->post($this->baseUrl, $params);

            $data = $response->json();

            // Log the response for debugging
            Log::info('Payment Gateway Response', ['response' => $data]);

            // Check if request was successful
            // Success codes typically start with 000.000 or 000.100
            if (isset($data['result']['code']) &&
                preg_match('/^(000\.000\.|000\.100\.)/', $data['result']['code'])) {

                return [
                    'success' => true,
                    'id' => $data['id'],
                    'payment_link' => $data['link'],
                    'qr_code' => $data['qrCode'] ?? null,
                    'merchant_transaction_id' => $merchantTransactionId,
                    'ndc' => $data['ndc'] ?? null,
                    'message' => 'Payment link generated successfully'
                ];
            }

            Log::error('Payment link generation failed', [
                'response' => $data,
                'params' => $params
            ]);

            return [
                'success' => false,
                'code' => $data['result']['code'] ?? 'UNKNOWN',
                'message' => $data['result']['description'] ?? 'Failed to generate payment link'
            ];

        } catch (\Exception $e) {
            Log::error('Payment gateway exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Payment gateway connection error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get payment status for Pay by Link
     *
     * @param string $paymentId The link ID returned from generatePaymentLink
     * @return array|null
     */
    public function getPaymentStatus($paymentId)
    {
        try {
            // Try multiple possible endpoints
            $endpoints = [
                // Pay by Link status endpoint
                'https://eu-test.oppwa.com/paybylink/v1/' . $paymentId,
                // Standard checkout status endpoint
                'https://eu-test.oppwa.com/v1/checkouts/' . $paymentId . '/payment',
            ];

            foreach ($endpoints as $statusUrl) {
                Log::info('Trying payment status endpoint', [
                    'payment_id' => $paymentId,
                    'url' => $statusUrl
                ]);

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->authToken,
                ])->get($statusUrl, [
                    'entityId' => $this->entityId
                ]);

                $data = $response->json();

                // If we get a valid response (not 404), return it
                if (isset($data['result']['code']) && !str_contains($data['result']['code'], '200.300.')) {
                    Log::info('Payment status retrieved', [
                        'payment_id' => $paymentId,
                        'endpoint' => $statusUrl,
                        'response' => $data
                    ]);
                    return $data;
                }
            }

            Log::warning('Could not retrieve payment status from any endpoint', [
                'payment_id' => $paymentId
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Payment status check error', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Generate unique transaction ID
     *
     * @param int $userId
     * @return string
     */
    protected function generateTransactionId($userId)
    {
        return 'WALLET_' . $userId . '_' . time() . '_' . uniqid();
    }

    /**
     * Format billing data for the request
     *
     * @param array $billing
     * @return array
     */
    protected function formatBillingData($billing)
    {
        return [
            'billing.street1' => $billing['street1'] ?? '',
            'billing.houseNumber1' => $billing['house_number'] ?? '',
            'billing.postcode' => $billing['postcode'] ?? '',
            'billing.city' => $billing['city'] ?? '',
            'billing.country' => $billing['country'] ?? 'US',
        ];
    }

    /**
     * Validate payment result code
     *
     * @param string $code
     * @return bool
     */
    public function isSuccessfulPayment($code)
    {
        // Successful payment codes according to OPPWA documentation
        // 000.000.* - Transaction succeeded
        // 000.100.1* - Transaction pending
        // 000.3* - Transaction pending
        // 000.6* - Transaction pending
        return preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $code);
    }
}
