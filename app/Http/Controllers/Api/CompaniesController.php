<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\CompanyResource;
use App\Models\City;
use App\Models\Company;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function index(Request $request)
   {
       $per_page = $request->get('per_page', 10);

       $companies = Company::filter($request->all())->paginate($per_page);

       return response()->json([
           'success' => true,
           'data' => CompanyResource::collection($companies),
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
            'data' => CompanyResource::collection($company)
        ]);
    }
}
