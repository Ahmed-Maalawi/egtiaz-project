<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\EndOfService;
use App\Models\PaymentAccount;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EndOfServiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = EndOfService::with('user');

        $eosRecords = $query->latest()->paginate(10);
        return view('admin.eos.index', compact('eosRecords'));
    }


    public function create()
    {
        $user = Auth::user();

        $paymentAccounts = $user->paymentAccounts;

        $users = User::whereDoesntHave('eos')->whereNot('id', Auth::user()->id)->withoutRole('super-admin')->get();
        return view('admin.eos.create', compact('users', 'paymentAccounts'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'user_id' => 'required|exists:users,id',
                'joining_date' => 'required|date',
                'leaving_date' => 'required|date|after:joining_date',
                'basic_salary' => 'required|numeric',
                'gross_salary' => 'nullable|numeric',
                'annual_leave_balance' => 'numeric',
                'incentive' => 'nullable|numeric',
                'rewards' => 'nullable|numeric',
                'other_additions' => 'nullable|numeric',
                'cash_advance' => 'nullable|numeric',
                'petty_cash' => 'nullable|numeric',
                'fines' => 'nullable|numeric',
                'compensation_notice' => 'nullable|numeric',
                'other_deductions' => 'nullable|numeric',
                'payment_account_id' => 'required|exists:payment_accounts,id'
            ]);

            $user = Auth::user();
            $paymentAccount = PaymentAccount::find($data['payment_account_id']);

            if (!$paymentAccount) {
                return back()->with('error', __('Payment account not found.'));
            }

            // Check if user has access to the payment account
            $hasAccess = $paymentAccount->users()->where('user_id', $user->id)->exists();

            if (!$hasAccess) {
                return back()->with('error', __('You do not have access to this payment account.'));
            }


            $joining = Carbon::parse($data['joining_date']);
            $leaving = Carbon::parse($data['leaving_date']);
            $years = $joining->diffInYears($leaving);


            if ($years < 1) {
                $eosAmount = 0;
            } elseif ($years < 5) {
                $eosAmount = $data['basic_salary'] * 21 / 30 * $years; // 21 days per year
            } else {
                $eosAmount = $data['basic_salary'] * 30 / 30 * $years; // 30 days per year
            }

            $additions = ($data['incentive'] ?? 0) + ($data['rewards'] ?? 0) + ($data['other_additions'] ?? 0);
            $deductions = ($data['cash_advance'] ?? 0) + ($data['petty_cash'] ?? 0) + ($data['fines'] ?? 0) + ($data['compensation_notice'] ?? 0) + ($data['other_deductions'] ?? 0);
            $leavePay = $data['annual_leave_balance'] * ($data['basic_salary'] / 30);

            $netPay = $eosAmount + $additions + $leavePay - $deductions;

            $data['net_pay'] = floatval($netPay);

            DB::transaction(function () use ($data, $netPay, $user, $paymentAccount, $request) {

                $newPaymentAccountBalance = $paymentAccount->balance - $netPay;
                $newPaymentAccountBalance = floatval($newPaymentAccountBalance);


                $eos = EndOfService::create([
                    'user_id' => $data['user_id'],
                    'joining_date' => $data['joining_date'],
                    'leaving_date' => $data['leaving_date'],
                    'basic_salary' => $data['basic_salary'],
                    'gross_salary' => $data['gross_salary'] ?? null,
                    'annual_leave_balance' => $data['annual_leave_balance'],
                    'incentive' => $data['incentive'] ?? 0,
                    'rewards' => $data['rewards'] ?? 0,
                    'other_additions' => $data['other_additions'] ?? 0,
                    'cash_advance' => $data['cash_advance'] ?? 0,
                    'petty_cash' => $data['petty_cash'] ?? 0,
                    'fines' => $data['fines'] ?? 0,
                    'compensation_notice' => $data['compensation_notice'] ?? 0,
                    'other_deductions' => $data['other_deductions'] ?? 0,
                    'net_pay' => $netPay,
                ]);


                $transaction = Transaction::create([
                    'transaction_id'            => Str::uuid(),
                    'user_id'                   => $data['user_id'],
                    'payment_account_id'        => $paymentAccount->id,
                    'created_by'                => $user->id,
                    'amount'                    => $netPay,
                    'transactionable_id'        => $eos->id,
                    'transactionable_type'      => EndOfService::class,
                    'from_balance_before'       => $paymentAccount->balance,
                    'from_balance_after'        => $newPaymentAccountBalance,
                    'status'                    => 'completed',
                    'type'                      => 'eos_payment',
                    'description'               => $request->description ?? "End of Service payment for employee",
                    'processed_at'              => now(),
                ]);

                // Update payment account balance
                $paymentAccount->update([
                    'balance' => $newPaymentAccountBalance
                ]);

            });

            return redirect()->route('admins.eos.index')->with('success', 'End of Service payment processed successfully.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            return back()->with('error', __('An error occurred while processing the End of Service payment: ') . $e->getMessage());
        }
    }

    public function show(EndOfService $eo)
    {
        $eo->load('user');
        return view('admin.eos.show', compact('eo'));
    }


    public function edit(EndOfService $eo)
    {
        $users = User::all();
        return view('admin.eos.edit', compact('eo', 'users'));
    }

    public function update(Request $request, EndOfService $eo)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'joining_date' => 'required|date',
            'leaving_date' => 'required|date|after:joining_date',
            'basic_salary' => 'required|numeric|min:0',
            'gross_salary' => 'nullable|numeric|min:0',
            'annual_leave_balance' => 'nullable|numeric|min:0',
            'incentive' => 'nullable|numeric|min:0',
            'rewards' => 'nullable|numeric|min:0',
            'other_additions' => 'nullable|numeric|min:0',
            'cash_advance' => 'nullable|numeric|min:0',
            'petty_cash' => 'nullable|numeric|min:0',
            'fines' => 'nullable|numeric|min:0',
            'compensation_notice' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
        ]);


        $joining = Carbon::parse($data['joining_date']);
        $leaving = Carbon::parse($data['leaving_date']);
        $years = $joining->diffInYears($leaving);


        if ($years < 1) $eoAmount = 0;
        elseif ($years < 5) $eoAmount = $data['basic_salary'] * 21 / 30 * $years;
        else $eoAmount = $data['basic_salary'] * 30 / 30 * $years;


        $additions = ($data['incentive'] ?? 0) + ($data['rewards'] ?? 0) + ($data['other_additions'] ?? 0);
        $deductions = ($data['cash_advance'] ?? 0) + ($data['petty_cash'] ?? 0) + ($data['fines'] ?? 0) + ($data['compensation_notice'] ?? 0) + ($data['other_deductions'] ?? 0);

        $leavePay = ($data['annual_leave_balance'] ?? 0) * ($data['basic_salary'] / 30);
        $netPay = $eoAmount + $additions + $leavePay - $deductions;

        $data['net_pay'] = $netPay;

        $eo->update($data);

        return redirect()->route('admins.eos.show', $eo)->with('success', __('Record updated successfully.'));
    }

    public function destroy(EndOfService $eo)
    {
        $eo->load('transaction.paymentAccount');


        DB::transaction(function () use ($eo) {

            $paymentAccount = $eo->transaction->paymentAccount;
            $transaction = $eo->transaction;
            $amount = $transaction->amount;

//            dd('payment_id', $paymentAccount);
            $newBalance = $paymentAccount->balance + $amount;

            $paymentAccount->update(['balance' => $newBalance]);
        });

        $eo->transaction->delete();
        $eo->delete();

        return redirect()->route('admins.eos.index')->with('success', __('Record deleted successfully.'));
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'joining_date' => 'required|date',
            'leaving_date' => 'required|date|after:joining_date',
            'basic_salary' => 'required|numeric|min:0',
            'annual_leave_balance' => 'nullable|numeric|min:0',
            'incentive' => 'nullable|numeric|min:0',
            'rewards' => 'nullable|numeric|min:0',
            'other_additions' => 'nullable|numeric|min:0',
            'cash_advance' => 'nullable|numeric|min:0',
            'petty_cash' => 'nullable|numeric|min:0',
            'fines' => 'nullable|numeric|min:0',
            'compensation_notice' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
        ]);


        $joining = Carbon::parse($validated['joining_date']);
        $leaving = Carbon::parse($validated['leaving_date']);
        $years = $joining->diffInYears($leaving);

        $basic_salary = $validated['basic_salary'];
        $annual_leave_balance = $validated['annual_leave_balance'] ?? 0;


        $additions = ($validated['incentive'] ?? 0)
            + ($validated['rewards'] ?? 0)
            + ($validated['other_additions'] ?? 0);

        $deductions = ($validated['cash_advance'] ?? 0)
            + ($validated['petty_cash'] ?? 0)
            + ($validated['fines'] ?? 0)
            + ($validated['compensation_notice'] ?? 0)
            + ($validated['other_deductions'] ?? 0);


        if ($years < 1) {
            $eosAmount = 0;
        } elseif ($years < 5) {
            $eosAmount = $basic_salary * 21 / 30 * $years;
        } else {
            $eosAmount = $basic_salary * 30 / 30 * $years;
        }

        $leavePay = $annual_leave_balance * ($basic_salary / 30);
        $netPay = $eosAmount + $additions + $leavePay - $deductions;


        return response()->json([
            'years' => number_format($years, 2),
            'eos_amount' => number_format($eosAmount, 2),
            'leave_pay' => number_format($leavePay, 2),
            'additions' => number_format($additions, 2),
            'deductions' => number_format($deductions, 2),
            'net_pay' => number_format($netPay, 2),
        ]);
    }
}
