<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeaveResource;
use App\Models\OfficialLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
   public function index(Request $request)
   {
       $user = Auth::user();
       $per_page = $request->get('per_page', 10);

       $query = OfficialLeave::with([
           'employee.company',
           'employee.iqamaType',
           'approver'
       ]);

       if (! $user->hasRole('super-admin')) {
           $userCompany = $user->companyOfModeration;

           if (is_null($userCompany?->id)) {
               return response()->json([
                   'success' => false,
                   'message' => __('Forbidden: you do not have access to view leaves')
               ], 403);
           }

           $query->whereHas('employee', function ($q) use ($userCompany) {
               $q->where('company_id', $userCompany->id);
           });
       }

       $leaves = $query->filter($request->all())->paginate($per_page);

       return response()->json([
           'success' => true,
           'data' => LeaveResource::collection($leaves),
           'meta' => [
               'current_page' => $leaves->currentPage(),
               'last_page'    => $leaves->lastPage(),
               'per_page'     => $leaves->perPage(),
               'total'        => $leaves->total(),
           ]
       ]);
   }

    public function show(int $id)
    {
        $user = Auth::user();

        if ($user->hasRole('super-admin')) {
            $leave = OfficialLeave::with(['employee', 'approver'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => new LeaveResource($leave),
            ]);
        }

        $userCompany = $user->companyOfModeration;

        if (is_null($userCompany?->id)) {
            return response()->json([
                'success' => false,
                'message' => __('Forbidden: you do not have access to view this leave.'),
            ], 403);
        }

        $leave = OfficialLeave::with(['employee', 'approver'])
            ->whereHas('employee', function ($query) use ($userCompany) {
                $query->where('company_id', $userCompany->id);
            })
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new LeaveResource($leave),
        ]);
    }


}
