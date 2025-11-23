<?php

namespace App\Http\Controllers\Dashboard;


use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeStage;
use App\Models\EndOfService;
use App\Models\IqamaType;
use App\Models\OfficialLeave;
use App\Models\PaymentAccount;
use App\Models\Salary;
use App\Models\Stage;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function EOSReport(Request $request)
    {
        $perPage = 10;

        $query = EndOfService::with(['user.leaves']);

        if ($request->has('user_id') && $request->user_id) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('id', $request->user_id);
            });
        }

        if ($request->has('year_from') && $request->year_from) {
            $query->whereYear('joining_date', '>=', $request->year_from);
        }

        if ($request->has('year_to') && $request->year_to) {
            $query->whereYear('leaving_date', '<=', $request->year_to);
        }

        if ($request->has('service_years') && $request->service_years) {
            $query->whereRaw('TIMESTAMPDIFF(YEAR, joining_date, leaving_date) >= ?', [$request->service_years]);
        }

        if ($request->has('net_pay_min') && $request->net_pay_min) {
            $query->where('net_pay', '>=', $request->net_pay_min);
        }

        if ($request->has('net_pay_max') && $request->net_pay_max) {
            $query->where('net_pay', '<=', $request->net_pay_max);
        }

        // Date range filter
        if ($request->has('date_range') && $request->date_range) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('leaving_date', [Carbon::parse($dates[0]), Carbon::parse($dates[1])]);
            }
        }

        $users = User::whereHas('eos')->get();

        $eosRecords = $query->paginate($perPage);


        $filterData = [
            'min_year' => EndOfService::min('joining_date') ? Carbon::parse(EndOfService::min('joining_date'))->year : date('Y') - 10,
            'max_year' => EndOfService::max('leaving_date') ? Carbon::parse(EndOfService::max('leaving_date'))->year : date('Y'),
            'min_net_pay' => EndOfService::min('net_pay') ?? 0,
            'max_net_pay' => EndOfService::max('net_pay') ?? 100000,
        ];

        return view('admin.reports.eos-report', compact('eosRecords', 'users', 'filterData'));
    }

    public function LeavesReport(Request $request)
    {
        $query = OfficialLeave::with(['user', 'approver']);

        $perPage = $request->per_page ?? 10;
        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('leave_type')) {
            $query->where('type', $request->leave_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('days_min')) {
            $query->where('days_taken', '>=', $request->days_min);
        }

        if ($request->filled('days_max')) {
            $query->where('days_taken', '<=', $request->days_max);
        }

        if ($request->filled('year_from')) {
            $query->whereYear('start_date', '>=', $request->year_from);
        }

        if ($request->filled('year_to')) {
            $query->whereYear('end_date', '<=', $request->year_to);
        }

        // Date range filter
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('start_date', [Carbon::parse($dates[0]), Carbon::parse($dates[1])]);
            }
        }

        $leaves = $query->paginate($perPage);
        $users = User::whereHas('leaves')->get();


        return view('admin.reports.leaves-report', compact('leaves', 'users'));
    }

    public function EmployeesReport(Request $request)
    {
        $perPage = $request->per_page ?? 10;

        $query = Employee::query()->with(['company', 'iqamaType', 'upcomingStage']);

        // Filters
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('iqama_type_id')) {
            $query->where('iqama_type_id', $request->iqama_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_range')) {
            $range = explode(' to ', $request->date_range);
            if (count($range) === 2) {
                $query->whereBetween('expired_date', [$range[0], $range[1]]);
            }
        }

        $employees = $query->paginate($perPage);

        $companies = Company::select('id', 'name')->get();
        $iqamaTypes = IqamaType::select('id', 'name')->get();

        return view('admin.reports.employees-report', compact('employees', 'companies', 'iqamaTypes'));
    }

    public function SalaryReport(Request $request)
    {
        $perPage = $request->per_page ?? 10;

        $query = Salary::query()->with(['company', 'iqamaType', 'upcomingStage']);

        $salaries = $query->paginate($perPage);


        return view('admin.reports.employees-report', compact('salaries'));
    }


    public function TransactionsReport(Request $request)
    {
        $perPage = $request->per_page ?? 10;

        $query = Transaction::with([
            'fromPaymentAccount',
            'toWallet',
            'paymentAccount',
            'user',
            'createdBy',
            'transactionable'
        ]);

        // Filters
        if ($request->filled('transaction_id')) {
            $query->where('transaction_id', 'like', '%' . $request->transaction_id . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('payment_account_id')) {
            $query->where('payment_account_id', $request->payment_account_id);
        }

        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }

        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [
                    Carbon::parse($dates[0])->startOfDay(),
                    Carbon::parse($dates[1])->endOfDay()
                ]);
            }
        }

        $transactions = $query->latest()->paginate($perPage);

        return view('admin.reports.transactions-report', [
            'transactions' => $transactions,
            'accounts' => PaymentAccount::all(),
            'statuses' => ['pending', 'completed', 'failed', 'refund', 'canceled'],
            'types' => ['stage_payment', 'salary_payment', 'refund', 'charge'],
            'stages' => Stage::all()
        ]);
    }

    public function getEmployeeDetails(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'nullable|integer|exists:employees,id',
        ]);

        $employees = Employee::get();
        $employee = null;
        if (isset($validatedData['employee_id'])) {
            $employee = Employee::with([
                'company',
                'iqamaType',
                'iqamaType.stages',
                'upcomingStage',
                'files',
                'employeeStages',
                'employeeStages.doneBy',
                'employeeStages.files',
                'employeeStages.transactions',
            ])
                ->findOrFail($request->employee_id);
        }



        return view('admin.reports.employee-details', compact('employee', 'employees'));
    }

    public function getProfitReport(Request $request)
    {
        $filters = $request->validate([
            'employee_id' => 'nullable|integer|exists:employees,id',
            'company_id' => 'nullable|integer|exists:companies,id',
            'from_date' => 'nullable|date|date_format:Y-m-d',
            'to_date' => 'nullable|date|date_format:Y-m-d|after_or_equal:from_date'
        ]);

        $employeeStages = EmployeeStage::with(['stage', 'employee'])
            ->where('status', 'completed')
            ->ProfitReport($filters)
            ->get();

        $totalProfit = collect($employeeStages)->sum(fn($stage) => $stage->profit);

        $employees = Employee::all();
        $companies = Company::all();

        return view('admin.reports.profit-report', compact('employeeStages', 'totalProfit', 'employees', 'companies'));
    }


    public function getWalletTransactionReport(Request $request)
    {
        $filters = $request->only(['user_id', 'status', 'from_date', 'to_date']);

        $baseQuery = WalletTransaction::with('user', 'wallet.company', 'employeeStage.employee', 'employeeStage.stage')
            ->filter($filters)
            ->latest();

        // Get all transactions
        $transactions = (clone $baseQuery)->get();

        // Debit transactions: where type = 'stage_payment'
        $debitTransactions = (clone $baseQuery)
            ->where('type', 'stage_payment')
            ->get();

        // Credit transactions: where type is null (wallet charges)
        $creditTransactions = (clone $baseQuery)
            ->whereNull('type')
            ->get();

        $users = User::all();
        $totalAmount = $transactions->sum('amount');
        $totalCredit = $creditTransactions->sum('amount');
        $totalDebit = $debitTransactions->sum('amount');

        return view('admin.reports.wallets-transactions-report', compact(
            'transactions',
            'debitTransactions',
            'creditTransactions',
            'users',
            'totalAmount',
            'totalCredit',
            'totalDebit'
        ));
    }
}
