<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WalletResource;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use App\Models\Wallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Laravolt\Avatar\Avatar;

class ReportsController extends Controller
{
    public function getEmployeesReport(Request $request)
    {
        $filters = $request->all();

        $sortColumn = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');

        $query = Employee::with([
            'upcomingStage',
            'company',
            'salaries',
            'files',
            'iqamaType',
            'employeeStages',
            'leaves',
            'eos'
        ])->filter($filters);


        if (!Auth::user()->hasRole('super-admin') && !is_null(Auth::user()->companyOfModeration)) {
            $query->where('company_id', Auth::user()->companyOfModeration);
        }


        $employees = $query->orderBy($sortColumn, $sortDirection)
            ->paginate($request->per_page ?? 10);


        return response([
            'message'   => 'list employees report data',
            'data'      => EmployeeResource::collection($employees),
            'pagination' => [
                'total'         => $employees->total(),
                'per_page'      => $employees->perPage(),
                'current_page'  => $employees->currentPage(),
                'last_page'     => $employees->lastPage(),
                'from'          => $employees->firstItem(),
                'to'            => $employees->lastItem()
            ]
        ]);
    }
}
