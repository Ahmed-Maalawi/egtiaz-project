<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EndOfServiceResource;
use App\Http\Resources\LeaveResource;
use App\Http\Resources\PaymentAccountResource;
use App\Models\EndOfService;
use App\Models\OfficialLeave;
use App\Models\PaymentAccount;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class EOSController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $per_page = $request->get('per_page', 10);

            $query = EndOfService::with(['employee']);

            if (! $user->hasAnyRole(['super-admin', 'admin'])) {
                $company = $user->companyOfModeration;

                if (is_null($company?->id)) {
                    return response()->json([
                        'success' => false,
                        'message' => __('Forbidden: You do not have access to view end of service records.'),
                    ], 403);
                }

                $query->whereHas('employee', function ($q) use ($company) {
                    $q->where('company_id', $company->id);
                });
            }

            $eoss = $query->paginate($per_page);

            return response()->json([
                'success' => true,
                'data' => EndOfServiceResource::collection($eoss),
                'meta' => [
                    'current_page' => $eoss->currentPage(),
                    'last_page'    => $eoss->lastPage(),
                    'per_page'     => $eoss->perPage(),
                    'total'        => $eoss->total(),
                ],
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => __('Record not found.'),
            ], 404);
        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $user = Auth::user();

            $query = EndOfService::with(['employee']);

            if (! $user->hasAnyRole(['super-admin', 'admin'])) {
                $company = $user->companyOfModeration;

                if (is_null($company?->id)) {
                    return response()->json([
                        'success' => false,
                        'message' => __('Forbidden: You do not have access to view this record.'),
                    ], 403);
                }

                $query->whereHas('employee', function ($q) use ($company) {
                    $q->where('company_id', $company->id);
                });
            }

            $eos = $query->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => new EndOfServiceResource($eos),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => __('End of Service record not found.'),
            ], 404);
        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }



}
