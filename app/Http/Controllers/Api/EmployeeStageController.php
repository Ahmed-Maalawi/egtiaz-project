<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\EmployeeStageResource;
use App\Http\Resources\PaymentAccountResource;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeStage;
use App\Models\IqamaType;
use App\Models\PaymentAccount;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmployeeStageController extends Controller
{
    /**
     * Get single employee data for API
     */
    public function getSingleEmployee(Request $request)
    {
        try {
            $types = IqamaType::select('id', 'name')->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'iqama_types' => $types
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch iqama types',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending employee stages for API
     */
    public function getPending(Request $request)
    {
        try {
            $moderator = User::findOrFail(Auth::id());
            $companyIds = $moderator->companyOfModeration()->pluck('id')->toArray();

            $employees = Employee::with(['upcomingStage.stage', 'company', 'iqamaType', 'employeeStages'])
                ->whereIn('company_id', $companyIds)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'employees' => EmployeeResource::collection($employees)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Failed to fetch pending employee stages')      ,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show employee stage details for API
     */
    public function show(int $employeeStageId)
    {
        try {
            $employeeStage = EmployeeStage::with(['stage', 'employee', 'employee.company'])
                ->findOrFail($employeeStageId);

            return response()->json([
                'success' => true,
                'data' => [
                    'employee_stage' => new EmployeeStageResource($employeeStage)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Employee stage not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Show pay employee stage page data for API
     */
    public function showPayEmployeeStagePage(int $employeeStageId)
    {
        try {
            $userId = Auth::id();

            $employeeStage = EmployeeStage::with(['stage', 'employee'])
                ->findOrFail($employeeStageId);

            $paymentAccounts = PaymentAccount::whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'employee_stage'    => new EmployeeStageResource($employeeStage),
                    'payment_accounts'  => PaymentAccountResource::collection($paymentAccounts),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch payment page data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pay employee stage via API
     */
    public function payEmployeeStage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_stage_id'     => 'required|exists:employee_stages,id',
            'payment_account_id'    => 'required|exists:payment_accounts,id',
            'description'           => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $employeeStage = EmployeeStage::with(['stage', 'employee'])->find($request->employee_stage_id);
            $paymentAccount = PaymentAccount::find($request->payment_account_id);

            // Check if both records exist
            if (!$employeeStage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee stage not found'
                ], 404);
            }

            if (!$paymentAccount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment account not found'
                ], 404);
            }

            // Check if employee stage has required relationships
            if (!$employeeStage->stage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stage information not found for this employee stage'
                ], 404);
            }

            if (!$employeeStage->employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee information not found for this stage'
                ], 404);
            }

            if ($employeeStage->status == 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Stage already completed'
                ], 400);
            }

            $amount = $employeeStage->stage->price ?? $employeeStage->stage->cost;

            if (!$amount || $amount <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid stage amount. Please check stage price or cost'
                ], 400);
            }

            if ($paymentAccount->balance < $amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance in the payment account'
                ], 400);
            }

            $moderator = User::with('companyOfModeration.wallet')->find(Auth::id());

            if (!$moderator || !$moderator->moderator_company_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Moderator company not found'
                ], 404);
            }

            $company = Company::with('wallet')->find($moderator->moderator_company_id);

            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found'
                ], 404);
            }

            if (!$company->wallet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company wallet not found'
                ], 404);
            }

            // Check company wallet balance
            if ($company->wallet->balance < $amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance in company wallet'
                ], 400);
            }

            DB::transaction(function () use ($request, $company, $employeeStage, $paymentAccount, $amount) {
                $newPaymentAccountBalance = $paymentAccount->balance - $amount;
                $newCompanyBalance = $company->wallet->balance - $amount;

                $transaction = Transaction::create([
                    'created_by'                => Auth::id(),
                    'transaction_id'            => Str::uuid(),
                    'from_payment_account_id'   => $paymentAccount->id,
                    'payment_account_id'        => $paymentAccount->id,
                    'user_id'                   => Auth::id(),
                    'amount'                    => $amount,
                    'transactionable_id'        => $employeeStage->id,
                    'transactionable_type'      => EmployeeStage::class,
                    'from_balance_before'       => $paymentAccount->balance,
                    'from_balance_after'        => $newPaymentAccountBalance,
                    'status'                    => 'completed',
                    'type'                      => 'stage_payment',
                    'description'               => $request->description ?? "Stage payment for {$employeeStage->stage->name} - Employee: {$employeeStage->employee->name}",
                    'processed_at'              => now(),
                ]);

                $paymentAccount->update([
                    'balance' => $newPaymentAccountBalance
                ]);

                $company->wallet->update([
                    'balance' => $newCompanyBalance
                ]);

                $employeeStage->update([
                    'status'        => 'completed',
                    'completed_at'  => now(),
                    'done_by'       => Auth::id(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Stage payment completed successfully',
                'data' => [
                    'employee_stage_id' => $employeeStage->id,
                    'amount' => $amount,
                    'status' => 'completed'
                ]
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'One of the required records was not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the stage payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new employee stage (placeholder)
     */
    public function store(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Method not implemented'
        ], 501);
    }

    /**
     * Update employee stage (placeholder)
     */
    public function update(Request $request, int $employeeStageId)
    {
        return response()->json([
            'success' => false,
            'message' => 'Method not implemented'
        ], 501);
    }

    /**
     * Delete employee stage (placeholder)
     */
    public function destroy(int $employeeStageId)
    {
        return response()->json([
            'success' => false,
            'message' => 'Method not implemented'
        ], 501);
    }
}
