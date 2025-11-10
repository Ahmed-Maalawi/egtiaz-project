<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Company;
use App\Models\EmployeeStage;
use App\Models\PaymentAccount;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\IqamaType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Log;

class EmployeeStageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getSingleEmployee(Request $request)
    {
        $types = IqamaType::select('id', 'name')->get();
        return view('admin.employee-stages.get-single-employee', compact('types'));
    }

    public function getPending(Request $request)
    {
        $moderator = User::findOrFail(Auth::id());
        $companyIds = $moderator->companyOfModeration()->pluck('id')->toArray();

        $employees = Employee::with(['upcomingStage.stage','company','iqamaType'])
            ->whereIn('company_id', $companyIds)
            ->get();

        return view('admin.employee-stages.upcoming', [
            'employees' => $employees,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeStage $employeeStage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeStage $employeeStage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeStage $employeeStage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeStage $employeeStage)
    {
        //
    }

    public function showPayEmployeeStagePage(int $employeeStageId)
    {
        $userId = Auth::user()->with('paymentAccounts');

        $employeeStage = EmployeeStage::findOrFail($employeeStageId);
        $paymentAccounts = PaymentAccount::whereHas('users', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->get();

        return view('admin.employee-stages.pay-employee-stage', compact('paymentAccounts', 'employeeStage'));
    }

    public function PayEmployeeStage(Request $request)
    {

//        try {
            $request->validate([
                'employee_stage_id'     => 'required|exists:employee_stages,id',
                'payment_account_id'    => 'required|exists:payment_accounts,id',
                'description'           => 'nullable|string|max:255',
            ]);

            $employeeStage = EmployeeStage::with(['stage', 'employee'])->find($request->employee_stage_id);
            $paymentAccount = PaymentAccount::find($request->payment_account_id);

            // Check if both records exist
            if (!$employeeStage) {
                return back()->with('error', __('Employee stage not found.'));
            }

            if (!$paymentAccount) {
                return back()->with('error', __('Payment account not found.'));
            }

            // Check if employee stage has required relationships
            if (!$employeeStage->stage) {
                return back()->with('error', __('Stage information not found for this employee stage.'));
            }

            if (!$employeeStage->employee) {
                return back()->with('error', __('Employee information not found for this stage.'));
            }

            if($employeeStage->status == 'completed'){
                return back()->with('error', __('Stage already completed.'));
            }


            $amount = $employeeStage->stage->price ?? $employeeStage->stage->cost;


            if (!$amount || $amount <= 0) {
                return back()->with('error', __('Invalid stage amount. Please check stage price or cost.'));
            }

            if ($paymentAccount->balance < $amount) {
                return back()->with('error', __('Insufficient balance in the payment account.'));
            }

            $moderator = User::with('companyOfModeration.wallet')->find(Auth::id());

            if (!$moderator || !$moderator->moderator_company_id) {
                return back()->with('error', __('Moderator company not found.'));
            }

            $company = Company::with('wallet')->find($moderator->moderator_company_id);

            if (!$company) {
                return back()->with('error', __('Company not found.'));
            }

            if (!$company->wallet) {
                return back()->with('error', __('Company wallet not found.'));
            }

            // Check company wallet balance
            if ($company->wallet->balance < $amount) {
                return back()->with('error', __('Insufficient balance in company wallet.'));
            }

            DB::transaction(function () use ($request, $company, $employeeStage, $paymentAccount, $amount) {

                $newPaymentAccountBalance = $paymentAccount->balance - $amount;
                $newCompanyBalance = $company->wallet->balance - $amount;


                $transaction = Transaction::create([
                    'created_by'                => Auth::id(),
                    'transaction_id'            => Str::uuid(),
                    'to_wallet_id'              => $company->wallet->id,
                    'from_payment_account_id'   => $paymentAccount->id,
//                    'employee_id'               => $employeeStage->employee->id,
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

                Log::info("Stage payment completed", [
                    'transaction_id' => $transaction->id,
                    'employee_stage_id' => $employeeStage->id,
                    'employee_id' => $employeeStage->employee_id,
                    'amount' => $amount,
                    'payment_account_id' => $paymentAccount->id,
                    'user_id' => Auth::id()
                ]);
            });

            return redirect()->route('admins.employee-stages.getSingleEmployee')->with('success', __('Stage payment completed successfully.'));

//        } catch (\Illuminate\Validation\ValidationException $e) {
//            return back()->withErrors($e->validator)->withInput();
//
//        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
//            Log::error('Model not found in PayEmployeeStage: ' . $e->getMessage());
//            return back()->with('error', __('One of the required records was not found.'));
//
//        } catch (\Exception $e) {
//            Log::error('Stage payment error: ' . $e->getMessage());
//            return back()->with('error', __('An error occurred while processing the stage payment. Please try again.'));
//        }
    }
}
