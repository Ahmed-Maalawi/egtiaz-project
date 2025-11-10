<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletResource;
use App\Models\Company;
use App\Models\PaymentAccount;
use App\Models\Salary;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\PaymentGatewayService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $query = Salary::with('user');

        if ($request->has('month') && $request->month) {
            $query->where('month', $request->month);
        } else {
            $query->get();
        }

        if ($request->has('status') && in_array($request->status, ['pending', 'paid'])) {
            $query->where('status', $request->status);
        }

        $salaries = $query->orderBy('created_at', 'desc')->paginate(20);

        $month = $request->month ?? now()->format('Y-m');
        $totalPending = Salary::where('month', $month)->where('status', 'pending')->sum('amount');
        $totalPaid = Salary::where('month', $month)->where('status', 'paid')->sum('amount');

        if (Auth::user()->hasRole('super-admin')) {
            $paymentAccounts = PaymentAccount::all();
        } else {
            $paymentAccounts = PaymentAccount::whereHas('users', function ($query) {
                $query->where('user_id', Auth::id());
            })->get();
        }


        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => [
                    'salaries' => $salaries->items(),
                    'pagination' => [
                        'total' => $salaries->total(),
                        'per_page' => $salaries->perPage(),
                        'current_page' => $salaries->currentPage(),
                        'last_page' => $salaries->lastPage(),
                    ],
                    'total_pending' => $totalPending,
                    'total_paid' => $totalPaid,
                    'payment_accounts' => $paymentAccounts,
                ],
            ]);
        }

        return view('admin.hr.salaries.index', compact('salaries', 'totalPending', 'totalPaid', 'paymentAccounts'));
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
            $message = __('Cannot generate salaries for future months.');

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }

            return redirect()->route('admins.hr.salaries.index')->with('error', $message);
        }

        foreach ($users as $user) {
            try {
                $existingSalary = Salary::where('user_id', $user->id)->where('month', $month)->first();

                if ($existingSalary) {
                    $skippedCount++;
                    continue;
                }

                Salary::create([
                    'user_id' => $user->id,
                    'amount' => $user->salary,
                    'month' => $month,
                    'status' => 'pending',
                    'notes' => "Auto-generated for {$month}",
                ]);

                $generatedCount++;
            } catch (Exception $e) {
                $errors[] = "Failed to generate salary for {$user->name}: {$e->getMessage()}";
                Log::error("Salary generation failed for user {$user->id}: " . $e->getMessage());
            }
        }

        $message = "Salary generation completed for {$month}. Generated: {$generatedCount}, Skipped: {$skippedCount}";
        if (!empty($errors)) $message .= ", Errors: " . count($errors);

        return response()->json([
            'success' => true,
            'message' => $message,
            'errors' => $errors,
        ]);
    }

    public function paySalary(Request $request)
    {
        try {
            $request->validate([
                'salary_id' => 'required|exists:salaries,id',
                'payment_account_id' => 'required|exists:payment_accounts,id',
                'description' => 'nullable|string|max:255',
            ]);

            $salary = Salary::with('user')->find($request->salary_id);
            $paymentAccount = PaymentAccount::find($request->payment_account_id);
            $user = Auth::user();

            if (!$salary) {
                return $this->errorResponse($request, 'Salary not found.');
            }

            if (!$paymentAccount) {
                return $this->errorResponse($request, 'Payment account not found.');
            }

            if (!$salary->user) {
                return $this->errorResponse($request, 'Employee information not found for this salary.');
            }

            if ($salary->status === 'paid') {
                return $this->errorResponse($request, 'Salary already paid.');
            }

            $amount = $salary->user->salary;
            if (!$amount || $amount <= 0) {
                return $this->errorResponse($request, 'Invalid salary amount.');
            }

            if ($paymentAccount->balance < $amount) {
                return $this->errorResponse($request, 'Insufficient balance.');
            }

            $hasAccess = $paymentAccount->users()->where('user_id', $user->id)->exists();
            if (!$hasAccess) {
                return $this->errorResponse($request, 'You do not have access to this payment account.');
            }

            DB::transaction(function () use ($salary, $paymentAccount, $amount, $user, $request) {
                $newBalance = $paymentAccount->balance - $amount;

                Transaction::create([
                    'transaction_id' => Str::uuid(),
                    'from_payment_account_id' => $paymentAccount->id,
                    'user_id' => $salary->user->id,
                    'payment_account_id' => $paymentAccount->id,
                    'created_by' => $user->id,
                    'amount' => $amount,
                    'transactionable_id' => $salary->id,
                    'transactionable_type' => Salary::class,
                    'from_balance_before' => $paymentAccount->balance,
                    'from_balance_after' => $newBalance,
                    'status' => 'completed',
                    'type' => 'salary_payment',
                    'description' => $request->description ?? "Salary payment for {$salary->month} - {$salary->user->name}",
                    'processed_at' => now(),
                ]);

                $paymentAccount->update(['balance' => $newBalance]);
                $salary->update(['status' => 'paid', 'paid_at' => now()]);
            });

            return $this->successResponse($request, 'Salary payment completed successfully.');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $e->validator->errors()], 422);
            }
            return back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            return $this->errorResponse($request, 'Error processing payment: ' . $e->getMessage());
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
            $totalAmount = Salary::whereIn('id', $salaryIds)->where('status', 'pending')->sum('amount');

            $user = Auth::user();

            if ($paymentAccount->balance < $totalAmount) {
                return $this->errorResponse($request, 'Insufficient balance for bulk payment.');
            }

            $hasAccess = $paymentAccount->users()->where('user_id', $user->id)->exists();
            if (!$hasAccess) {
                return $this->errorResponse($request, 'You do not have access to this payment account.');
            }

            $successCount = 0;
            $errorCount = 0;

            foreach ($salaryIds as $salaryId) {
                try {
                    DB::transaction(function () use ($salaryId, $paymentAccount, $user, &$successCount, &$errorCount, $request) {
                        $salary = Salary::with('user')->find($salaryId);
                        if ($salary && $salary->status === 'pending') {
                            $amount = $salary->amount;

                            if ($paymentAccount->balance < $amount) {
                                throw new Exception('Insufficient balance during bulk payment processing');
                            }

                            $newBalance = $paymentAccount->balance - $amount;

                            Transaction::create([
                                'transaction_id' => Str::uuid(),
                                'from_payment_account_id' => $paymentAccount->id,
                                'user_id' => $salary->user->id,
                                'payment_account_id' => $paymentAccount->id,
                                'created_by' => $user->id,
                                'amount' => $amount,
                                'transactionable_id' => $salary->id,
                                'transactionable_type' => Salary::class,
                                'from_balance_before' => $paymentAccount->balance,
                                'from_balance_after' => $newBalance,
                                'status' => 'completed',
                                'type' => 'salary_payment',
                                'description' => $request->description ?? "Salary payment for {$salary->month} - {$salary->user->name}",
                                'processed_at' => now(),
                            ]);

                            $paymentAccount->update(['balance' => $newBalance]);
                            $salary->update(['status' => 'paid', 'paid_at' => now()]);
                            $successCount++;
                        }
                    });
                } catch (Exception $e) {
                    $errorCount++;
                    Log::error("Failed to pay salary ID {$salaryId}: " . $e->getMessage());
                }
            }

            $message = "Successfully paid {$successCount} salaries.";
            if ($errorCount > 0) $message .= " Failed to pay {$errorCount}.";

            return $this->successResponse($request, $message);

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $e->validator->errors()], 422);
            }
            return back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            return $this->errorResponse($request, 'Bulk payment error: ' . $e->getMessage());
        }
    }

    public function getCompanyBalance()
    {
        $user = Auth::user();
        $moderator = User::with('companyOfModeration.wallet')->find($user->id);

        if ($moderator->moderator_company_id && $moderator->companyOfModeration) {
            return response()->json([
                'success' => true,
                'company_name' => $moderator->companyOfModeration->name,
                'company_balance' => $moderator->companyOfModeration->balance,
            ]);
        }

        return response()->json([
            'success' => false,
            'company_name' => 'No company assigned',
            'company_balance' => 0,
        ]);
    }

    /**
     * Helper methods for unified response handling
     */
    private function successResponse(Request $request, string $message, array $extra = [])
    {
        if ($request->expectsJson()) {
            return response()->json(array_merge(['success' => true, 'message' => $message], $extra));
        }

        return back()->with('success', $message);
    }

    private function errorResponse(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $message], 400);
        }

        return back()->with('error', $message);
    }


}
