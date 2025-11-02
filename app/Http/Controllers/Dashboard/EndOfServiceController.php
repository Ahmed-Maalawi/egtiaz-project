<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Employee;
use App\Models\EndOfService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EndOfServiceController extends Controller
{
    public function index()
    {
        $eosRecords = EndOfService::with('employee')->latest()->paginate(10);
        return view('admin.eos.index', compact('eosRecords'));
    }


    public function create()
    {
        $user = Auth::user();

        if ($user->hasRole('super-admin')) {
            $employees = Employee::whereDoesntHave('eos')->get();
        } else {
            $employees = Employee::where('company_id', $user?->moderator_company_id ?? null)->whereDoesntHave('eos')->get();
        }

        return view('admin.eos.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required',
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
        ]);

        $joining = Carbon::parse($data['joining_date']);
        $leaving = Carbon::parse($data['leaving_date']);
        $years = $joining->diffInYears($leaving);

        // EOS Formula
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

        $data['net_pay'] = $netPay;

        EndOfService::create($data);

        return redirect()->route('admins.eos.index')->with('success', 'End of Service record saved successfully.');
    }

    public function show(EndOfService $eo)
    {
        $eo->load('employee');
        return view('admin.eos.show', compact('eo'));
    }


    public function edit(EndOfService $eo)
    {
        $employees = Employee::all();
        return view('admin.eos.edit', compact('eo', 'employees'));
    }

    public function update(Request $request, EndOfService $eo)
    {
        $data = $request->validate([
            'employee_id' => 'required',
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

        // Calculate years of service
        $joining = Carbon::parse($data['joining_date']);
        $leaving = Carbon::parse($data['leaving_date']);
        $years = $joining->diffInYears($leaving);

        // Calculate EOS Amount
        if ($years < 1) $eoAmount = 0;
        elseif ($years < 5) $eoAmount = $data['basic_salary'] * 21 / 30 * $years;
        else $eoAmount = $data['basic_salary'] * 30 / 30 * $years;

        // Additions & Deductions
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
        $eo->delete();

        return redirect()->route('admins.eos.index')->with('success', __('Record deleted successfully.'));
    }

    public function calculate(Request $request)
    {
        // ✅ Step 1: Validate the required fields
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

        // ✅ Step 2: Safe parsing after validation
        $joining = Carbon::parse($validated['joining_date']);
        $leaving = Carbon::parse($validated['leaving_date']);
        $years = $joining->diffInYears($leaving);

        $basic_salary = $validated['basic_salary'];
        $annual_leave_balance = $validated['annual_leave_balance'] ?? 0;

        // ✅ Step 3: Handle null-safe arithmetic
        $additions = ($validated['incentive'] ?? 0)
            + ($validated['rewards'] ?? 0)
            + ($validated['other_additions'] ?? 0);

        $deductions = ($validated['cash_advance'] ?? 0)
            + ($validated['petty_cash'] ?? 0)
            + ($validated['fines'] ?? 0)
            + ($validated['compensation_notice'] ?? 0)
            + ($validated['other_deductions'] ?? 0);

        // ✅ Step 4: Calculate EOS
        if ($years < 1) {
            $eosAmount = 0;
        } elseif ($years < 5) {
            $eosAmount = $basic_salary * 21 / 30 * $years;
        } else {
            $eosAmount = $basic_salary * 30 / 30 * $years;
        }

        $leavePay = $annual_leave_balance * ($basic_salary / 30);
        $netPay = $eosAmount + $additions + $leavePay - $deductions;

        // ✅ Step 5: Return response
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
