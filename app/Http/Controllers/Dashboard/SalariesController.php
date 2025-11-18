<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PaymentAccount;
use App\Models\Salary;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Log;

class SalariesController extends Controller
{
    public function index(Request $request)
    {
        $query = Salary::with('user');

        if ($request->has('month') && $request->month) {
            $query->where('month', $request->month);
        } else {
//            $query->where('month', now()->format('Y-m'));
            $query->get();
        }

        if ($request->has('status') && in_array($request->status, ['pending', 'paid'])) {
            $query->where('status', $request->status);
        }

        $salaries = $query->orderBy('created_at', 'desc')->paginate(20);

        $totalPending = Salary::where('month', $request->month ?? now()->format('Y-m'))
            ->where('status', 'pending')
            ->sum('amount');

        $totalPaid = Salary::where('month', $request->month ?? now()->format('Y-m'))
            ->where('status', 'paid')
            ->sum('amount');

        // Get payment accounts for the dropdown (only those accessible by user)
        if (Auth::user()->hasRole('super-admin')) {
            $paymentAccounts = PaymentAccount::all();
        } else {
            $paymentAccounts = PaymentAccount::whereHas('users', function($query) {
                $query->where('user_id', Auth::id());
            })
                ->get();
        }


        return view('admin.hr.salaries.index', compact('salaries', 'totalPending', 'totalPaid', 'paymentAccounts'));
    }

    public function create()
    {
//        $employees = Employee::where('status', 'active')->get();
//        $paymentAccounts = PaymentAccount::whereHas('users', function($query) {
//                $query->where('employee_id', Auth::id());
//            })
//            ->get();
//
//        return view('admin.hr.salaries.create', compact('employees', 'paymentAccounts'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $month = $request->month;

        $users = User::where('salary', '>', 0)->where('status', 'active')->get();

        $generatedCount = 0;
        $skippedCount = 0;
        $errors = [];

        if (date('Y-m') < $month) {
            return redirect()->route('admins.hr.salaries.index')
                ->with('error', __('Cannot generate salaries for future months.'));
        }

        foreach ($users as $user) {
            try {

                $existingSalary = Salary::where('user_id', $user->id)
                    ->where('month', $month)
                    ->first();

                if ($existingSalary) {
                    $skippedCount++;
                    continue;
                }

                Salary::create([
                    'user_id'       => $user->id,
                    'amount'        => $user->salary,
                    'month'         => $month,
                    'status'        => 'pending',
                    'notes'         => "Auto-generated for {$month}",
                ]);

                $generatedCount++;

            } catch (Exception $e) {
                $errors[] = "Failed to generate salary for {$user->name}: {$e->getMessage()}";
                Log::error("Salary generation failed for user {$user->id}: " . $e->getMessage());
            }
        }

        $message = "Salary generation completed for {$month}. ";
        $message .= "Generated: {$generatedCount}, ";
        $message .= "Skipped (already exists): {$skippedCount}";

        if (!empty($errors)) {
            $message .= ", Errors: " . count($errors);
        }

        $redirect = redirect()->route('admins.hr.salaries.index', ['month' => $month]);

        if ($generatedCount > 0) {
            $redirect->with('success', $message);
        } else {
            $redirect->with('info', $message);
        }

        if (!empty($errors)) {
            $redirect->with('error_array', $errors);
        }

        return $redirect;
    }

    public function paySalary(Request $request)
    {
        try {
            $request->validate([
                'salary_id' => 'required|exists:salaries,id',
                'payment_account_id' => 'required|exists:payment_accounts,id',
                'description' => 'nullable|string|max:255',
            ]);


            $salary = Salary::with(['user'])->find($request->salary_id);
            $paymentAccount = PaymentAccount::find($request->payment_account_id);

            if (!$salary) {
                return back()->with('error', __('Salary not found.'));
            }

            if (!$paymentAccount) {
                return back()->with('error', __('Payment account not found.'));
            }


            if (!$salary->user) {
                return back()->with('error', __('Employee information not found for this salary.'));
            }


            if ($salary->status == 'paid') {
                return back()->with('error', __('Salary already paid.'));
            }

            $amount = $salary->user->salary;

            if (!$amount || $amount <= 0) {
                return back()->with('error', __('Invalid salary amount.'));
            }


            $user = Auth::user();
            $moderator = User::with('companyOfModeration.wallet')->find($user->id);


            if ($paymentAccount->balance < $amount) {
                return back()->with('error', __('Insufficient balance in the selected payment account. Available: $' . number_format($paymentAccount->balance, 2)));
            }


            $hasAccess = $paymentAccount->users()->where('user_id', $user->id)->exists();
            if (!$hasAccess) {
                return back()->with('error', __('You do not have access to this payment account.'));
            }


            DB::transaction(function () use ($request, $salary, $paymentAccount, $amount, $user) {

                $newPaymentAccountBalance = $paymentAccount->balance - $amount;

                $transaction = Transaction::create([
                    'transaction_id'            => Str::uuid(),
                    'user_id'                   => $salary->user->id,
                    'payment_account_id'        => $paymentAccount->id,
                    'created_by'                => $user->id,
                    'amount'                    => $amount,
                    'transactionable_id'        => $salary->id,
                    'transactionable_type'      => Salary::class,
                    'from_balance_before'       => $paymentAccount->balance,
                    'from_balance_after'        => $newPaymentAccountBalance,
                    'status'                    => 'completed',
                    'type'                      => 'salary_payment',
                    'description'               => $request->description ?? "Salary payment for {$salary->month} - {$salary->user->name}",
                    'processed_at'              => now(),
                ]);

                $paymentAccount->update([
                    'balance' => $newPaymentAccountBalance
                ]);


                $salary->update([
                    'status' => 'paid',
                    'paid_at' => Carbon::now(),
                ]);
            });

            return back()->with('success', __('Salary payment completed successfully.'));

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            return back()->with('error', __('An error occurred while processing the payment: ') . $e->getMessage());
        }
    }

    public function bulkPaySalaries(Request $request)
    {

        try {
            $request->validate([
                'salary_ids' => 'required|array',
                'salary_ids.*' => 'exists:salaries,id',
                'payment_account_id' => 'required|exists:payment_accounts,id',
            ]);

            $salaryIds = $request->salary_ids;
            $paymentAccount = PaymentAccount::find($request->payment_account_id);
            $totalAmount = Salary::whereIn('id', $salaryIds)
                ->where('status', 'pending')
                ->sum('amount');


            $user = Auth::user();
            $moderator = User::with('companyOfModeration.wallet')->find($user->id);

            if (!$moderator->moderator_company_id) {
                return back()->with('error', __('You are not assigned to any company.'));
            }

            if ($paymentAccount->balance < $totalAmount) {
                return back()->with('error', __('Insufficient balance in the payment account. Required: $' . number_format($totalAmount, 2) . ', Available: $' . number_format($paymentAccount->balance, 2)));
            }


            $hasAccess = $paymentAccount->users()->where('user_id', $user->id)->exists();
            if (!$hasAccess) {
                return back()->with('error', __('You do not have access to this payment account.'));
            }

            $successCount = 0;
            $errorCount = 0;

            foreach ($salaryIds as $salaryId) {
                try {
                    DB::transaction(function () use ($salaryId, $paymentAccount, $user, &$successCount, &$totalAmount) {
                        $salary = Salary::with(['user'])->find($salaryId);

                        if ($salary && $salary->status === 'pending') {
                            $amount = $salary->amount;


                            if ($paymentAccount->balance < $amount) {
                                throw new Exception('Insufficient balance during bulk payment processing');
                            }

                            $newPaymentAccountBalance = $paymentAccount->balance - $amount;

                            $transaction = Transaction::create([
                                'transaction_id'            => Str::uuid(),
                                'from_payment_account_id'   => $paymentAccount->id,
                                'user_id'                   => $salary->user->id,
                                'payment_account_id'        => $paymentAccount->id,
                                'created_by'                => $user->id,
                                'amount'                    => $amount,
                                'transactionable_id'        => $salary->id,
                                'transactionable_type'      => Salary::class,
                                'from_balance_before'       => $paymentAccount->balance,
                                'from_balance_after'        => $newPaymentAccountBalance,
                                'status'                    => 'completed',
                                'type'                      => 'salary_payment',
                                'description'               => $request->description ?? "Salary payment for {$salary->month} - {$salary->user->name}",
                                'processed_at'              => now(),
                            ]);


                            $paymentAccount->update(['balance' => $newPaymentAccountBalance]);

                            $salary->update([
                                'status' => 'paid',
                                'paid_at' => Carbon::now(),
                            ]);

                            $successCount++;
                        }
                    });
                } catch (Exception $e) {
                    $errorCount++;

                    Log::error("Failed to pay salary ID {$salaryId}: " . $e->getMessage());
                }
            }

            $message = "Successfully paid {$successCount} salaries.";
            if ($errorCount > 0) {
                $message .= " Failed to pay {$errorCount} salaries.";
            }

            return back()->with('success', $message);

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            return back()->with('error', __('An error occurred while processing bulk payment: ') . $e->getMessage());
        }
    }

    /**
     * Get company wallet balance for current user
     */
    public function getCompanyBalance()
    {
        $user = Auth::user();
        $moderator = User::with('companyOfModeration.wallet')->find($user->id);

        if ($moderator->moderator_company_id && $moderator->companyOfModeration) {
            return response()->json([
                'company_balance' => $moderator->companyOfModeration->balance,
                'company_name' => $moderator->companyOfModeration->name,
            ]);
        }

        return response()->json([
            'company_balance' => 0,
            'company_name' => 'No company assigned',
        ]);
    }
}
