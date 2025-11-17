<?php

namespace App\Http\Controllers\Dashboard;

use App\Jobs\SendEmployeeCompletionEmail;
use App\Models\EmployeeStage;
use App\Models\PaymentAccount;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WalletTransaction;
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
        try {

            $validated = $request->validate([
                'employee_stage_id'     => 'required|exists:employee_stages,id',
                'payment_account_id'    => 'required|exists:payment_accounts,id',
                'description'           => 'nullable|string|max:255',
                'stage_price'           => 'required|numeric|min:0',
            ]);

            $employeeStage = EmployeeStage::with([
                'stage',
                'employee.company.wallet',
                'employee.stages' // To check if all stages completed
            ])->findOrFail($validated['employee_stage_id']);

            $paymentAccount = PaymentAccount::findOrFail($validated['payment_account_id']);


            $this->validateStagePayment($employeeStage, $paymentAccount, $validated['stage_price']);

            $cost_amount = $employeeStage->stage->cost;
            $stage_price = $validated['stage_price'];
            $company = $employeeStage->employee->company;
            $wallet = $company->wallet;


            DB::transaction(function () use ($employeeStage, $paymentAccount, $wallet, $cost_amount, $stage_price, $validated) {

                $newPaymentAccountBalance = $paymentAccount->balance - $cost_amount;
                $newWalletBalance = $wallet->balance - $stage_price;
                $profit = $stage_price - $cost_amount;


                $transaction = Transaction::create([
                    'created_by'                => Auth::id(),
                    'transaction_id'            => Str::uuid(),
                    'payment_account_id'        => $paymentAccount->id,
                    'employee_stage_id'         => $employeeStage->id,
                    'user_id'                   => null,
                    'amount'                    => $cost_amount,
                    'transactionable_id'        => $employeeStage->id,
                    'transactionable_type'      => EmployeeStage::class,
                    'from_balance_before'       => $paymentAccount->balance,
                    'from_balance_after'        => $newPaymentAccountBalance,
                    'status'                    => 'completed',
                    'type'                      => 'stage_payment',
                    'method_type'               => 'debit',
                    'description'               => $validated['description'] ??
                        "Stage payment cost for {$employeeStage->stage->name} - Employee: {$employeeStage->employee->name}",
                    'metadata'                  => [
                        'cost' => $cost_amount,
                        'price' => $stage_price,
                        'profit' => $profit,
                        'company_id' => $wallet->company_id,
                        'employee_id' => $employeeStage->employee_id,
                    ],
                    'processed_at'              => now(),
                ]);


                $paymentAccount->update(['balance' => $newPaymentAccountBalance]);


                $walletTransaction = $wallet->walletTransactions()->create([
                    'user_id'                   => Auth::id(),
                    'employee_stage_id'         => $employeeStage->id, // Link to employee stage
                    'payment_id'                => Str::uuid(),
                    'merchant_transaction_id'   => Str::uuid(),
                    'amount'                    => $stage_price,
                    'currency'                  => 'SAR',
                    'status'                    => 'completed',
                    'type'                      => 'stage_payment', // Add type to differentiate
                    'description'               => "Service charge for {$employeeStage->stage->name} - Employee: {$employeeStage->employee->name}",
                    'payment_link'              => null,
                    'qr_code'                   => null,
                    'ndc'                       => null,
                    'gateway_response'          => json_encode([
                        'transaction_id' => $transaction->id,
                        'cost' => $cost_amount,
                        'price' => $stage_price,
                        'profit' => $profit,
                    ]),
                    'completed_at'              => now(),
                ]);


                $wallet->update(['balance' => $newWalletBalance]);


                $employeeStage->update([
                    'status'                    => 'completed',
                    'completed_at'              => now(),
                    'done_by'                   => Auth::id(),
                    'price_amount'              => $stage_price,
                    'amount_cost'               => $cost_amount,
                    'transaction_id'            => $transaction->id, // Link main transaction
                    'wallet_transaction_id'     => $walletTransaction->id, // Link wallet transaction
                ]);

                Log::info("Stage payment completed successfully", [
                    'transaction_id' => $transaction->id,
                    'wallet_transaction_id' => $walletTransaction->id,
                    'employee_stage_id' => $employeeStage->id,
                    'employee_id' => $employeeStage->employee_id,
                    'cost' => $cost_amount,
                    'price' => $stage_price,
                    'profit' => $profit,
                    'payment_account_id' => $paymentAccount->id,
                    'wallet_id' => $wallet->id,
                    'user_id' => Auth::id()
                ]);
            });


            $this->checkAndNotifyIfAllPapersCompleted($employeeStage->employee);

            return redirect()
                ->route('admins.employee-stages.getSingleEmployee', ['employee_id' => $employeeStage->employee_id])
                ->with('success', __('Stage payment completed successfully. Profit: :profit', [
                    'profit' => number_format($stage_price - $cost_amount, 2)
                ]));

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Model not found in PayEmployeeStage: ' . $e->getMessage());
            return back()->with('error', __('One of the required records was not found.'));

        } catch (\Exception $e) {
            Log::error('Stage payment error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return back()->with('error', __('An error occurred while processing the stage payment: :error', [
                'error' => $e->getMessage()
            ]));
        }
    }

    /**
     * Validate stage payment requirements
     */
    private function validateStagePayment($employeeStage, $paymentAccount, $stagePrice)
    {
        if (!$employeeStage->stage) {
            throw new \Exception(__('Stage information not found for this employee stage.'));
        }

        if (!$employeeStage->employee) {
            throw new \Exception(__('Employee information not found for this stage.'));
        }

        if (!$employeeStage->employee->company) {
            throw new \Exception(__('Company information not found for this employee.'));
        }

        if (!$employeeStage->employee->company->wallet) {
            throw new \Exception(__('Company wallet not found.'));
        }

        if ($employeeStage->status == 'completed') {
            throw new \Exception(__('Stage already completed.'));
        }

        $cost_amount = $employeeStage->stage->cost ?? 0;

        if (!$cost_amount || $cost_amount <= 0) {
            throw new \Exception(__('Invalid stage cost. Please check stage configuration.'));
        }

        if ($stagePrice <= 0) {
            throw new \Exception(__('Invalid stage price. Price must be greater than zero.'));
        }

        if ($stagePrice < $cost_amount) {
            throw new \Exception(__('Stage price cannot be less than cost. Cost: :cost, Price: :price', [
                'cost' => number_format($cost_amount, 2),
                'price' => number_format($stagePrice, 2)
            ]));
        }
    }

    /**
     * Check if all employee papers are completed and notify moderators
     */
    private function checkAndNotifyIfAllPapersCompleted($employee)
    {
        $employee->load('stages');

        $allCompleted = $employee->stages->every(fn($stage) => $stage->status === 'completed');

        if ($allCompleted && $employee->stages->count() > 0) {

            $employee->update([
                'all_papers_completed' => true,
                'papers_completed_at' => now(),
            ]);


            $moderators = User::role('moderator')
                ->where('moderator_company_id', $employee->company_id)
                ->where('status', 'active')
                ->whereNotNull('email')
                ->get();


            foreach ($moderators as $moderator) {
                SendEmployeeCompletionEmail::dispatch($employee, $moderator);
                Log::info("Email queued for moderator: {$moderator->email}");
            }

            Log::info("All papers completed for employee", [
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'total_stages' => $employee->stages->count(),
                'moderators_notified' => $moderators->count(),
            ]);
        }
    }

    /**
     * Get transaction details for employee stage
     */
    public function getStageTransactions($employeeStageId)
    {
        $employeeStage = EmployeeStage::with([
            'stage',
            'employee.company'
        ])->findOrFail($employeeStageId);


        $transaction = Transaction::where('employee_stage_id', $employeeStageId)
            ->where('transactionable_type', EmployeeStage::class)
            ->first();

        $walletTransaction = WalletTransaction::where('employee_stage_id', $employeeStageId)
            ->first();

        return response()->json([
            'employee_stage' => $employeeStage,
            'payment_transaction' => $transaction, // Cost transaction
            'wallet_transaction' => $walletTransaction, // Price transaction
            'profit' => $employeeStage->price_amount - $employeeStage->amount_cost,
        ]);
    }

    /**
     * Get profit report
     */
    public function getProfitReport(Request $request)
    {
        $validated = $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $query = EmployeeStage::where('status', 'completed')
            ->whereNotNull('price_amount')
            ->whereNotNull('amount_cost');

        if (!empty($validated['from_date'])) {
            $query->whereDate('completed_at', '>=', $validated['from_date']);
        }

        if (!empty($validated['to_date'])) {
            $query->whereDate('completed_at', '<=', $validated['to_date']);
        }

        if (!empty($validated['company_id'])) {
            $query->whereHas('employee', function ($q) use ($validated) {
                $q->where('company_id', $validated['company_id']);
            });
        }

        $stages = $query->with(['stage', 'employee.company'])->get();

        $totalCost = $stages->sum('amount_cost');
        $totalPrice = $stages->sum('price_amount');
        $totalProfit = $totalPrice - $totalCost;

        return response()->json([
            'summary' => [
                'total_cost' => number_format($totalCost, 2),
                'total_price' => number_format($totalPrice, 2),
                'total_profit' => number_format($totalProfit, 2),
                'profit_margin' => $totalPrice > 0 ? round(($totalProfit / $totalPrice) * 100, 2) : 0,
                'total_stages' => $stages->count(),
            ],
            'stages' => $stages->map(function ($stage) {
                return [
                    'id' => $stage->id,
                    'stage_name' => $stage->stage->name,
                    'employee_name' => $stage->employee->name,
                    'company_name' => $stage->employee->company->name,
                    'cost' => number_format($stage->amount_cost, 2),
                    'price' => number_format($stage->price_amount, 2),
                    'profit' => number_format($stage->price_amount - $stage->amount_cost, 2),
                    'completed_at' => $stage->completed_at->format('Y-m-d H:i:s'),
                ];
            }),
        ]);
    }
}
