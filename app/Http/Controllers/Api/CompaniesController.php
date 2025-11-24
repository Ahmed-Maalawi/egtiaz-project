<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompaniesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $per_page = $request->get('per_page', 10);

            $companies = Company::with(['moderators', 'employees', 'wallet'])->filter($request->all())->paginate($per_page);

            return response()->json([
                'success' => true,
                'data' => CompanyResource::collection($companies),
                'meta' => [
                    'current_page' => $companies->currentPage(),
                    'last_page' => $companies->lastPage(),
                    'per_page' => $companies->perPage(),
                    'total' => $companies->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false,
            ]);
        }
    }

    public function show(int $id)
    {
        try {
            $company = Company::with(['moderators', 'employees', 'wallet'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => new CompanyResource($company),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false,
            ]);
        }
    }

    public function getCompanyProfile(Request $request)
    {
        //        $user = auth()->user();
        //
        //        $company = Company::withCount(['moderators', 'employees.'])->findOrFail($user->moderator_company_id);
        //        $creditTransactions= $company->wallet->walletTransactions()->with('user')->latest()->limit(5)->get();
        //        $debitTransactions =  $company->wallet->paymentTransactions()->with(['createdBy', 'user', 'transactionable'])->latest()->limit(5)->get();
        //
        //
        //        // Normalize both collections
        //        $normalizedDbTransactions = collect($debitTransactions)->map(function ($t) {
        //            return [
        //                'transaction_type' => 'debit',
        //                'transaction_id' => $t['transaction_id'],
        //                'wallet_id' => $t['to_wallet_id'],
        //                'amount' => (float)$t['amount'],
        //                'status' => $t['status'],
        //                'description' => $t['payment_link'] ?? null,
        //                'created_by' => $t['createdBy'] ?? null,
        //                'created_at' => $t['created_at'],
        //                'updated_at' => $t['updated_at'],
        //                'extra' => $t, // keep full data if needed
        //            ];
        //        });
        //
        //        $normalizedCrTransactions = collect($creditTransactions)->map(function ($t) {
        //            return [
        //                'transaction_type' => 'credit',
        //                'transaction_id' => $t['payment_id'],
        //                'wallet_id' => $t['wallet_id'],
        //                'amount' => (float)$t['amount'],
        //                'status' => $t['status'],
        //                'description' => $t['description'] ?? null,
        //                'created_by' => $t['user'] ?? null,
        //                'created_at' => $t['created_at'],
        //                'updated_at' => $t['updated_at'],
        //                'extra' => $t, // keep full data if needed
        //            ];
        //        });
        //
        //        $allTransactions = $normalizedDbTransactions
        //            ->merge($normalizedCrTransactions)
        //            ->sortByDesc('created_at')
        //            ->values();
        //
        //        $data['employees_count'] = $company->employees_count;
        //        $data['moderators_count'] = $company->moderators_count;
        //        $data['company'] = new CompanyResource($company);
        //        $data['transactions'] = $allTransactions;
        //
        //        return response()->json([
        //            'success' => true,
        //            'message' => 'get company profile',
        //            'data' => $data,
        //        ]);

        $user = Auth::user();

        $company = $user->companyOfModeration;

        $company->load([
            'moderators',
            'employees',
            'wallet',
            'wallet.walletTransactions',
            'wallet.walletTransactions.employeeStage',
        ]);

        $data = [];

        $data['company'] = $company;

        // Map wallet transactions to include transaction type (debit/credit)
        $data['walletTransactions'] = $company->wallet->walletTransactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'wallet_id' => $transaction->wallet_id,
                'user_id' => $transaction->user_id,
                'employee_stage_id' => $transaction->employee_stage_id,
                'amount' => $transaction->amount,
                'status' => $transaction->status,
                'type' => $transaction->type,
                'payment_id' => $transaction->payment_id,
                'gateway' => $transaction->gateway,
                'gateway_response' => $transaction->gateway_response,
                'description' => $transaction->description,
                'completed_at' => $transaction->completed_at,
                'created_at' => $transaction->created_at,
                'updated_at' => $transaction->updated_at,
                'transaction_type' => $transaction->method_type, // 'debit' or 'credit'
                'employee_stage' => $transaction->employeeStage,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'get company profile',
            'data' => $data,
        ]);

    }

    public function getCompanyData(Request $request)
    {
        $user = auth()->user();

        $company = Company::with(['moderators', 'employees', 'wallet'])->findOrFail($user?->moderator_company_id);

        if (! $company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found',
            ]);
        }

        $data['employees_count'] = count($company->employees);
        $data['moderators_count'] = count($company->moderators);
        $data['wallet'] = $company->wallet;

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
