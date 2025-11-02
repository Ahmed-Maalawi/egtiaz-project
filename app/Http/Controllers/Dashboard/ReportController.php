<?php

namespace App\Http\Controllers\Dashboard;


use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EndOfService;
use App\Models\IqamaType;
use App\Models\OfficialLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function EOSReport(Request $request)
    {
        $perPage = 10;

        $query = EndOfService::with([
            'employee.files',
            'employee.iqamaType',
            'employee.leaves',
        ]);

        if ($request->has('employee_id') && $request->employee_id) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('id', $request->employee_id);
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

        $employees = Employee::whereHas('eos')->get(); // Only employees with EOS records

        $eosRecords = $query->paginate($perPage);

        // For filter counts with null checks
        $filterData = [
            'min_year' => EndOfService::min('joining_date') ? Carbon::parse(EndOfService::min('joining_date'))->year : date('Y') - 10,
            'max_year' => EndOfService::max('leaving_date') ? Carbon::parse(EndOfService::max('leaving_date'))->year : date('Y'),
            'min_net_pay' => EndOfService::min('net_pay') ?? 0,
            'max_net_pay' => EndOfService::max('net_pay') ?? 100000,
        ];

        return view('admin.reports.eos-report', compact('eosRecords', 'employees', 'filterData'));
    }

    public function LeavesReport(Request $request)
    {
        $query = OfficialLeave::with(['employee', 'approver']);

        $perPage = $request->per_page ?? 10;
        // Apply filters
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
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
        $employees = Employee::whereHas('leaves')->get();


        return view('admin.reports.leaves-report', compact('leaves', 'employees'));
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
}
