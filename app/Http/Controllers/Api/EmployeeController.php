<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\EmployeeResource;
use App\Models\City;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
   {
       $per_page = $request->get('per_page', 10);

       $companies = Employee::with([
           'upcomingStage',
           'company',
           'iqamaType',
           'employeeStages',
           'leaves',
           'eos'
       ])
           ->filter($request->all())
           ->paginate($per_page);

       return response()->json([
           'success' => true,
           'data' => EmployeeResource::collection($companies),
           'meta' => [
               'current_page' => $companies->currentPage(),
               'last_page' => $companies->lastPage(),
               'per_page' => $companies->perPage(),
               'total' => $companies->total(),
           ]
       ]);
   }

    public function show(Company $company)
    {
        $company->load(['moderators', 'employees', 'wallet', 'getBalanceAttribute']);

        response()->json([
            'success' => true,
            'data' => EmployeeResource::collection($company)
        ]);
    }
}
