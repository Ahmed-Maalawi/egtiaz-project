<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletResource;
use App\Models\Company;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\PaymentGatewayService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentGatewayService $paymentService)
    {
        $this->paymentService = $paymentService;
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
            'amount' => 'required|numeric|min:1|max:100000',
            'currency' => 'sometimes|string|size:3',
            'customer_name' => 'sometimes|string|max:100',
            'customer_mobile' => 'sometimes|string|max:20',
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
        $currency = $request->currency ?? 'SAR';

        if (!$user->hasRole('moderator')) {
            return response()->json([
                'success' => false,
                'message' => 'Only moderators can charge company wallets',
            ], 403);
        }

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

        // Prepare customer data
        $customerData = [
            'given_name' => $request->customer_name ?? $user->name ?? '',
            'surname' => '',
            'email' => $user->email ?? '',
            'mobile' => $request->customer_mobile ?? $user->phone ?? '',
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

        // Store transaction record
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
                'expires_in' => '24 hours'
            ]
        ], 200);
    }

    /**
     * Handle shopper result (redirect back from payment page)
     * This is called when user completes/cancels payment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function handleShopperResult(Request $request)
    {
        // The callback might send either 'id' or the full resourcePath
        $paymentId = $request->input('id');
        $resourcePath = $request->input('resourcePath');

        Log::info('Payment callback received', [
            'payment_id' => $paymentId,
            'resource_path' => $resourcePath,
            'all_params' => $request->all()
        ]);

        if (!$paymentId && !$resourcePath) {
            return $this->redirectToApp($request, 'failed', 'Invalid payment callback');
        }

        // Find transaction
        $transaction = WalletTransaction::where('payment_id', $paymentId)->first();

        if (!$transaction) {
            Log::warning('Transaction not found for payment', ['payment_id' => $paymentId]);
            return $this->redirectToApp($request, 'failed', 'Transaction not found');
        }

        // If we have resourcePath, use it to get payment status
        if ($resourcePath) {
            $paymentStatus = $this->getPaymentStatusFromResourcePath($resourcePath);
        } else {
            // Otherwise try the Pay by Link endpoint
            $paymentStatus = $this->paymentService->getPaymentStatus($paymentId);
        }

        if (!$paymentStatus || !isset($paymentStatus['result']['code'])) {
            return $this->redirectToApp($request, 'failed', 'Unable to verify payment status');
        }

        $resultCode = $paymentStatus['result']['code'];

        // Check if payment was successful
        if ($this->paymentService->isSuccessfulPayment($resultCode)) {

            // Prevent double processing
            if ($transaction->status === 'completed') {
                return $this->redirectToApp($request, 'success', 'Payment already processed', [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount
                ]);
            }

            // Update transaction
            $transaction->update([
                'status' => 'completed',
                'gateway_response' => $paymentStatus,
                'completed_at' => now()
            ]);

            // Update user wallet balance
            $user = $transaction->user;

            $transaction->wallet->update([
                'balance' => $user->balance + $transaction->amount
            ]);

//            $user->increment('wallet_balance', $transaction->amount);

            Log::info('Wallet charged successfully', [
                'user_id' => $user->id,
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount
            ]);

            return $this->redirectToApp($request, 'success', 'Wallet charged successfully', [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'wallet_balance' => $user->wallet_balance
            ]);
        }

        // Payment failed or pending
        $status = $this->determineTransactionStatus($resultCode);

        $transaction->update([
            'status' => $status,
            'gateway_response' => $paymentStatus
        ]);

        $message = $paymentStatus['result']['description'] ?? 'Payment ' . $status;

        return $this->redirectToApp($request, $status, $message, [
            'transaction_id' => $transaction->id,
            'code' => $resultCode
        ]);
    }

    /**
     * Get payment status using resourcePath from callback
     *
     * @param string $resourcePath
     * @return array|null
     */
    protected function getPaymentStatusFromResourcePath($resourcePath)
    {
        try {
            // Build the full URL: baseUrl + resourcePath
            $baseUrl = 'https://eu-test.oppwa.com';
            $statusUrl = $baseUrl . $resourcePath;

            Log::info('Checking payment via resourcePath', [
                'url' => $statusUrl
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.payment_gateway.auth_token'),
            ])->get($statusUrl, [
                'entityId' => config('services.payment_gateway.entity_id')
            ]);

            $data = $response->json();

            Log::info('Payment status from resourcePath', [
                'response' => $data
            ]);

            return $data;
        } catch (\Exception $e) {
            Log::error('Payment status check via resourcePath error', [
                'resource_path' => $resourcePath,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Redirect to mobile app with payment result
     *
     * @param Request $request
     * @param string $status
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    protected function redirectToApp($request, $status, $message, $data = [])
    {
        // For mobile app deep linking
        // Format: yourapp://payment/result?status=success&message=...&data=...
        $scheme = config('app.mobile_scheme', 'yourapp');

        $params = [
            'status' => $status,
            'message' => $message,
        ];

        if (!empty($data)) {
            $params['data'] = base64_encode(json_encode($data));
        }

        $deepLink = $scheme . '://payment/result?' . http_build_query($params);

        // If it's a web request, show a simple page
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
     *
     * @param int $transactionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionStatus($transactionId)
    {
        $transaction = WalletTransaction::where('id', $transactionId)
            ->where('user_id', auth()->id())
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
            ]
        ], 200);
    }

    /**
     * Get user wallet transactions history
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionHistory(Request $request)
    {
        $perPage = $request->input('per_page', 15);

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
     *
     * @param string $code
     * @return string
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


    public function handleWebhook(Request $request)
    {
        Log::info('Payment webhook received', [
            'payload' => $request->all()
        ]);

        $paymentId = $request->input('id');

        if (!$paymentId) {
            return response()->json(['status' => 'error'], 400);
        }

        // Find transaction
        $transaction = WalletTransaction::where('payment_id', $paymentId)->first();

        if (!$transaction) {
            Log::warning('Webhook: Transaction not found', ['payment_id' => $paymentId]);
            return response()->json(['status' => 'not_found'], 404);
        }

        // Don't reprocess completed transactions
        if ($transaction->status === 'completed') {
            return response()->json(['status' => 'already_processed'], 200);
        }

        $resultCode = $request->input('result.code');

        if ($this->paymentService->isSuccessfulPayment($resultCode)) {
            // Update transaction
            $transaction->update([
                'status' => 'completed',
                'gateway_response' => $request->all(),
                'completed_at' => now()
            ]);

            // Update user wallet
            $user = $transaction->user;
            $user->increment('wallet_balance', $transaction->amount);

            Log::info('Webhook: Wallet charged successfully', [
                'user_id' => $user->id,
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

        return response()->json(['status' => 'failed'], 200);
    }
}
