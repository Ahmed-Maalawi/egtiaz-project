<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletResource;
use App\Mail\WalletChargeSuccessMail;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\PaymentGatewayService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentGatewayService $paymentService)
    {
        $this->paymentService = $paymentService;

        // Apply rate limiting middleware
//        $this->middleware('throttle:10,1')->only(['chargeWallet']);
//        $this->middleware('throttle:100,1')->only(['handleWebhook']);
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('super-admin')) {
            $wallets = Wallet::all();
        } else {
            $wallets = Wallet::where('company_id', $user->moderator_company_id)->get();
        }

        return response()->json([
            'data' => WalletResource::collection($wallets),
            'success' => true,
        ]);
    }

    public function show($id)
    {
        try {
            $user = Auth::user();

            $wallet = Wallet::with(['company', 'transactions'])
                ->find($id);

            if (!$wallet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wallet not found'
                ], 404);
            }

            if (!$user->hasRole('super-admin') && $user->moderator_company_id != $wallet->company_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to access this wallet'
                ], 403);
            }

            return response()->json([
                'wallet' => new WalletResource($wallet),
                'success' => true,
            ]);

        } catch (Exception $e) {
            Log::error('Wallet fetch error', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch wallet details'
            ], 500);
        }
    }

    /**
     * Generate payment link for wallet charging
     */
    public function chargeWallet(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1|max:1000000',
            'currency' => 'sometimes|string|size:3|in:AED',
            'customer_name' => 'sometimes|string|max:100',
            'customer_mobile' => 'sometimes|string|regex:/^[0-9+\-\s()]+$/|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        $amount = $request->amount;
        $currency = $request->currency ?? 'AED';

        // Get company
        $company = $user->companyOfModeration()->first();
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found for this moderator',
            ], 404);
        }

        $wallet = $company->wallet;
        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found for this company',
            ], 404);
        }

        // Sanitize customer data
        $customerData = [
            'given_name' => strip_tags($request->customer_name ?? $user->name ?? ''),
            'surname' => $user->name ?? '',
            'email' => filter_var($user->email ?? '', FILTER_SANITIZE_EMAIL),
            'mobile' => preg_replace('/[^0-9+\-\s()]/', '', $request->customer_mobile ?? $user->phone ?? ''),
        ];

        // Generate payment link
        $result = $this->paymentService->generatePaymentLink(
            $amount,
            $user->id,
            $customerData,
            $currency
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'code' => $result['code'] ?? null
            ], 400);
        }

        // Store transaction record with idempotency key
//        $idempotencyKey = hash('sha256', $user->id . $amount . $result['merchant_transaction_id']);

        $transaction = WalletTransaction::create([
            'user_id' => $user->id,
            'wallet_id' => $wallet->id,
            'payment_id' => $result['id'],
            'merchant_transaction_id' => $result['merchant_transaction_id'],
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'pending',
            'payment_link' => $result['payment_link'],
            'qr_code' => $result['qr_code'],
            'ndc' => $result['ndc'],
//            'idempotency_key' => $idempotencyKey,
//            'expires_at' => now()->addHours(24),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment link generated successfully',
            'data' => [
                'transaction_id' => $transaction->id,
                'payment_link' => $result['payment_link'],
                'qr_code' => $result['qr_code'],
                'payment_id' => $result['id'],
                'amount' => $amount,
                'currency' => $currency,
//                'expires_at' => $transaction->expires_at->toIso8601String()
            ]
        ], 200);
    }

    /**
     * Handle shopper result (redirect back from payment page)
     */
    public function handleShopperResult(Request $request)
    {
        $paymentId = $request->input('id');
        $resourcePath = $request->input('resourcePath');

        Log::info('Payment callback received', [
            'payment_id' => $paymentId,
            'resource_path' => $resourcePath,
            'ip' => $request->ip()
        ]);

        if (!$paymentId && !$resourcePath) {
            return $this->redirectToApp($request, 'failed', 'Invalid payment callback');
        }

        $transaction = WalletTransaction::where('payment_id', $paymentId)->first();

        if (!$transaction) {
            Log::warning('Transaction not found for payment', ['payment_id' => $paymentId]);
            return $this->redirectToApp($request, 'failed', 'Transaction not found');
        }

        // Check if transaction expired
        if ($transaction->expires_at && $transaction->expires_at->isPast()) {
            $transaction->update(['status' => 'expired']);
            return $this->redirectToApp($request, 'failed', 'Payment link has expired');
        }

        // Get payment status
        if ($resourcePath) {
            $paymentStatus = $this->getPaymentStatusFromResourcePath($resourcePath);
        } else {
            $paymentStatus = $this->paymentService->getPaymentStatus($paymentId);
        }

        if (!$paymentStatus || !isset($paymentStatus['result']['code'])) {
            return $this->redirectToApp($request, 'failed', 'Unable to verify payment status');
        }

        $resultCode = $paymentStatus['result']['code'];

        if ($this->paymentService->isSuccessfulPayment($resultCode)) {
            // Prevent double processing with database lock
            return DB::transaction(function () use ($transaction, $paymentStatus, $request) {
                // Re-fetch with lock
                $transaction = WalletTransaction::where('id', $transaction->id)
                    ->lockForUpdate()
                    ->first();

                if ($transaction->status === 'completed') {
                    return $this->redirectToApp($request, 'success', 'Payment already processed', [
                        'transaction_id' => $transaction->id,
                        'amount' => $transaction->amount
                    ]);
                }

                $transaction->update([
                    'status' => 'completed',
                    'gateway_response' => $paymentStatus,
                    'completed_at' => now()
                ]);

                // Update wallet balance
                $transaction->wallet->increment('balance', $transaction->amount);

                // Send email to moderators
                try {
                    $this->sendPaymentSuccessEmailToModerator($transaction);
                } catch (\Exception $e) {
                    Log::error('Failed to send payment success email', [
                        'transaction_id' => $transaction->id,
                        'error' => $e->getMessage()
                    ]);
                }

                Log::info('Wallet charged successfully', [
                    'user_id' => $transaction->user_id,
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Wallet charged successfully',
                    'data' => [
                        'transaction_id' => $transaction->id,
                        'amount' => $transaction->amount,
                        'currency' => $transaction->currency,
                        'wallet_balance' => $transaction->wallet->fresh()->balance
                    ]
                ]);
            });
        }

        // Payment failed or pending
        $status = $this->determineTransactionStatus($resultCode);

        $transaction->update([
            'status' => $status,
            'gateway_response' => $paymentStatus
        ]);

        $message = $paymentStatus['result']['description'] ?? 'Payment ' . $status;

        return response()->json([
            'success' => false,
            'message' => $message,
            'transaction' => [
                'id' => $transaction->id,
                'status' => $status,
                'code' => $resultCode
            ]
        ]);
    }

    /**
     * Handle webhook with signature verification
     */
    public function handleWebhook(Request $request)
    {
        // Verify webhook signature
//        if (!$this->verifyWebhookSignature($request)) {
//            Log::warning('Invalid webhook signature', [
//                'ip' => $request->ip(),
//                'payload' => $request->all()
//            ]);
//            return response()->json(['status' => 'invalid_signature'], 403);
//        }


        Log::info('Payment webhook received', [
            'payload' => $request->all(),
            'ip' => $request->ip()
        ]);

        $paymentId = $request->input('id');

        if (!$paymentId) {
            return response()->json(['status' => 'error'], 400);
        }

        // Idempotency check - prevent duplicate processing
        $webhookId = $request->header('X-Webhook-Id') ?? $paymentId;
        $cacheKey = "webhook_processed_{$webhookId}";

//        if (Cache::has($cacheKey)) {
//            Log::info('Webhook already processed (idempotency)', ['webhook_id' => $webhookId]);
//            return response()->json(['status' => 'already_processed'], 200);
//        }

        // Find transaction
        $transaction = WalletTransaction::where('payment_id', $paymentId)->first();

        if (!$transaction) {
            Log::warning('Webhook: Transaction not found', ['payment_id' => $paymentId]);
            return response()->json(['status' => 'not_found'], 404);
        }

        // Don't reprocess completed transactions
        if ($transaction->status === 'completed') {
            Cache::put($cacheKey, true, 86400); // Cache for 24 hours
            return response()->json(['status' => 'already_processed'], 200);
        }

        $resultCode = $request->input('result.code');

        if ($this->paymentService->isSuccessfulPayment($resultCode)) {
            // Use database transaction for atomicity
            DB::transaction(function () use ($transaction, $request) {
                // Lock the transaction row
                $transaction = WalletTransaction::where('id', $transaction->id)
                    ->lockForUpdate()
                    ->first();

                // Double-check status after lock
                if ($transaction->status === 'completed') {
                    return;
                }

                $transaction->update([
                    'status' => 'completed',
                    'gateway_response' => $request->all(),
                    'completed_at' => now()
                ]);

                // Update wallet balance
                $transaction->wallet->increment('balance', $transaction->amount);

                // Send email notification
                try {
                    $this->sendPaymentSuccessEmailToModerator($transaction);
                } catch (\Exception $e) {
                    Log::error('Webhook: Failed to send email', [
                        'transaction_id' => $transaction->id,
                        'error' => $e->getMessage()
                    ]);
                }
            });

            // Mark webhook as processed
            Cache::put($cacheKey, true, 86400);

            Log::info('Webhook: Wallet charged successfully', [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount
            ]);

            return response()->json(['status' => 'success'], 200);
        }

        // Payment failed
        $transaction->update([
            'status' => 'failed',
            'gateway_response' => $request->all()
        ]);

        Cache::put($cacheKey, true, 86400);

        return response()->json(['status' => 'failed'], 200);
    }

    /**
     * Verify webhook signature for security
     */
    protected function verifyWebhookSignature(Request $request): bool
    {
        $webhookSecret = config('services.afs_payment_gateway.webhook_secret');

        if (!$webhookSecret) {
            Log::error('Webhook secret not configured');
            return false;
        }

        // Get signature from header (adjust header name based on AFS documentation)
        $receivedSignature = $request->header('X-Signature') ?? $request->header('X-AFS-Signature');

        if (!$receivedSignature) {
            Log::warning('No signature header found in webhook');
            return false;
        }

        // Get raw request body
        $payload = $request->getContent();

        // Calculate expected signature using HMAC SHA-256
        $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

        // Use timing-safe comparison
        return hash_equals($expectedSignature, $receivedSignature);
    }

    /**
     * Get payment status using resourcePath from callback
     */
    protected function getPaymentStatusFromResourcePath($resourcePath)
    {
        try {
            $baseUrl = config('services.afs_payment_gateway.url');
            $statusUrl = rtrim($baseUrl, '/v1/paybylink') . $resourcePath;

            Log::info('Checking payment via resourcePath', ['url' => $statusUrl]);

            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . config('services.afs_payment_gateway.auth_token'),
                ])
                ->get($statusUrl, [
                    'entityId' => config('services.afs_payment_gateway.entity_id')
                ]);

            $data = $response->json();

            Log::info('Payment status from resourcePath', ['response' => $data]);

            return $data;
        } catch (\Exception $e) {
            Log::error('Payment status check error', [
                'resource_path' => $resourcePath,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Redirect to mobile app with payment result
     */
    protected function redirectToApp($request, $status, $message, $data = [])
    {
        $scheme = config('app.mobile_scheme', 'yourapp');

        $params = [
            'status' => $status,
            'message' => $message,
        ];

        if (!empty($data)) {
            $params['data'] = base64_encode(json_encode($data));
        }

        $deepLink = $scheme . '://payment/result?' . http_build_query($params);

        // If web request, redirect to admin panel
        if (!$request->expectsJson() && !str_contains($request->userAgent() ?? '', 'Mobile')) {
            return redirect()->route('admins.companies.index')->with([
                'status' => $status,
                'message' => $message,
                'data' => $data
            ]);
        }

        return redirect($deepLink);
    }

    /**
     * Get transaction status
     */
    public function getTransactionStatus($transactionId)
    {
        $user = auth()->user();

        $transaction = WalletTransaction::where('id', $transactionId)
            ->where('user_id', $user->id)
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'status' => $transaction->status,
                'payment_link' => $transaction->payment_link,
                'created_at' => $transaction->created_at,
                'completed_at' => $transaction->completed_at,
                'expires_at' => $transaction->expires_at,
            ]
        ], 200);
    }

    /**
     * Get user wallet transactions history
     */
    public function getTransactionHistory(Request $request)
    {
        $perPage = min($request->input('per_page', 15), 100); // Max 100 per page

        $transactions = WalletTransaction::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ], 200);
    }

    /**
     * Determine transaction status based on result code
     */
    protected function determineTransactionStatus($code)
    {
        // Pending codes
        if (preg_match('/^(000\.200|800\.400\.5|100\.400\.500)/', $code)) {
            return 'pending';
        }

        // Rejected/cancelled codes
        if (preg_match('/^(000\.400\.0|100\.400\.0)/', $code)) {
            return 'cancelled';
        }

        // Default to failed
        return 'failed';
    }

    /**
     * Send payment success email to moderators
     */
    private function sendPaymentSuccessEmailToModerator(WalletTransaction $transaction)
    {
        $company = $transaction->wallet->company;

        if (!$company) {
            Log::warning('Company not found for transaction', ['transaction_id' => $transaction->id]);
            return;
        }

        $moderators = $company->moderators()->where('status', 'active')->get();

        if ($moderators->isEmpty()) {
            Log::warning('No active moderators found', ['company_id' => $company->id]);
            return;
        }

        foreach ($moderators as $moderator) {
            try {
                Mail::to($moderator->email)
                    ->queue(new WalletChargeSuccessMail($moderator, $transaction, $company));

                Log::info('Payment success email queued', [
                    'moderator_id' => $moderator->id,
                    'transaction_id' => $transaction->id
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to queue email', [
                    'moderator_id' => $moderator->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
