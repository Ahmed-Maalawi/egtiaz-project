<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function index(Request $request)
   {
       try {
           $per_page = $request->get('per_page', 10);

           $companies = Company::with(['moderators', 'employees', 'wallet'])->filter($request->all())->paginate($per_page);

           return response()->json([
               'success' => true,
               'data' => CompanyResource::collection($companies),
               'meta' => [
                   'current_page'   => $companies->currentPage(),
                   'last_page'      => $companies->lastPage(),
                   'per_page'       => $companies->perPage(),
                   'total'          => $companies->total(),
               ]
           ]);
       } catch (\Exception $e) {
           return response()->json([
               'error' => $e->getMessage(),
               'success' => false,
           ]);
       }
   }

    public function show(int $id)
    {
        try {
            $company = Company::with(['moderators', 'employees', 'wallet'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => new CompanyResource($company)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false,
            ]);
        }
    }
}
