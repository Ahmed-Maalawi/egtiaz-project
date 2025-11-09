<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\WalletResource;
use App\Models\Company;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\PaymentGatewayService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'amount'   => 'required|numeric|min:1|max:1000000',
            'currency' => 'sometimes|string|size:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();

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

        $userId   = $user->id;
        $amount   = $request->amount;
        $currency = $request->currency ?? 'SAR';

        $result = $this->paymentService->generatePaymentLink($amount, $userId, $user, $currency);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }

        $transaction = WalletTransaction::create([
            'user_id'                => $userId,
            'payment_id'             => $result['id'],
            'merchant_transaction_id'=> $result['merchant_transaction_id'],
            'amount'                 => $amount,
            'currency'               => $currency,
            'status'                 => 'pending',
            'payment_link'           => $result['payment_link'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment link generated successfully',
            'data'    => [
                'transaction_id' => $transaction->id,
                'payment_link'   => $result['payment_link'],
                'amount'         => $amount,
                'currency'       => $currency,
            ],
        ]);
    }

    /**
     * Payment callback/webhook handler
     */
    public function paymentCallback(Request $request)
    {

        $checkoutId = $request->input('id') ?? $request->input('checkoutId');

        if (!$checkoutId) {
            return response()->json(['success' => false, 'message' => 'transaction not found'], 400);
        }


        // Get payment status from gateway
        $paymentStatus = $this->paymentService->getPaymentStatus($checkoutId);
        dd($paymentStatus);
        $transaction = WalletTransaction::where('payment_id', $checkoutId)->first();

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'transaction not found'], 404);
        }

        // Update transaction based on payment status
        if (isset($paymentStatus['result']['code']) &&
            preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $paymentStatus['result']['code'])) {

            $transaction->update([
                'status' => 'completed',
                'gateway_response' => $paymentStatus,
                'completed_at' => now()
            ]);

            // Update user wallet balance
            $user = $transaction->user;

            $wallet = Wallet::where('id', $user->moderator_company_id)->first();

            $wallet->update([
               'amount' => $wallet->amount + $transaction->amount,
            ]);
//            $user->increment('wallet_balance', $transaction->amount);

            return response()->json(['success' => true], 200);
        }

        $transaction->update([
            'status' => 'failed',
            'gateway_response' => $paymentStatus
        ]);

        return response()->json(['success' => false], 200);
    }

    public function getTransactionStatus(Request $request, int $id)
    {

    }

    public function getTransactionHistory(Request $request)
    {

    }

}
