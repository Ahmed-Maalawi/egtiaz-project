<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeStage;
use App\Models\EndOfService;
use App\Models\OfficialLeave;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
//        $isSuperAdmin = Auth::user()->hasRole('super-admin');

        $data = [];

//        if ($isSuperAdmin) {
            $data['total_users'] = User::all()->except(Auth::id())->count();
//        }

        $data['total_employee'] = Employee::all()->count();

        $data['total_stages'] = Stage::all()->count();

        $data['total_employee_stages'] = EmployeeStage::all()->count();

        $data['total_leaves'] = OfficialLeave::all()->count();
        $data['total_leaves_approved'] = OfficialLeave::where('status', 'approved')->count();

        $data['total_eos'] = EndOfService::all()->count();

        $data['latest_eos'] = EndOfService::with('user')->latest()->take(10)->get();

        $data['latest_leaves'] = OfficialLeave::with(['user', 'approver'])->latest()->take(10)->get();

        $data['latest_paid_stages'] = EmployeeStage::with(['employee', 'stage'])->latest()->take(10)->get();

        $data['total_profit'] = EmployeeStage::where('status', 'completed')->get()->sum('profit');

        $data['employees_have_expired_documents'] = Employee::where('expired_date', '<', now())->get()->take(10);

        $data['employees_will_have_documents_expiration'] = Employee::whereBetween('expired_date', [
            now(),
            now()->addMonth()
        ])->get()->take(10);


        return view('admin.dashboard', compact('data'));
    }
}
