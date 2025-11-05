<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index(Request $request)
   {
       $per_page = $request->get('per_page', 10);

       $query = Employee::with([
           'upcomingStage',
           'company',
           'iqamaType',
           'employeeStages',
           'leaves',
           'eos'
       ])
           ->filter($request->all());

           if (Auth::user()){

           }

           $emloyees = $query->paginate($per_page);

       return response()->json([
           'success' => true,
           'data' => EmployeeResource::collection($emloyees),
           'meta' => [
               'current_page' => $emloyees->currentPage(),
               'last_page' => $emloyees->lastPage(),
               'per_page' => $emloyees->perPage(),
               'total' => $emloyees->total(),
           ]
       ]);
   }

    public function show(int $id)
    {
        $employee = Employee::with([
            'iqamaType:id,name',
            'upcomingStage',
            'company',
            'employeeStages.files',
            'leaves',
            'eos'
        ])->findOrFail($id);

       return response()->json([
            'success' => true,
            'data' => new EmployeeResource($employee)
       ]);
    }
}
