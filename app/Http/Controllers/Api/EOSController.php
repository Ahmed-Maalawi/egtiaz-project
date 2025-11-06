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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'joining_date' => 'required|date',
            'leaving_date' => 'required|date|after:joining_date',
            'basic_salary' => 'required|numeric|min:0',
            'gross_salary' => 'nullable|numeric|min:0',
            'annual_leave_balance' => 'required|numeric|min:0',
            'incentive' => 'nullable|numeric|min:0',
            'rewards' => 'nullable|numeric|min:0',
            'other_additions' => 'nullable|numeric|min:0',
            'cash_advance' => 'nullable|numeric|min:0',
            'petty_cash' => 'nullable|numeric|min:0',
            'fines' => 'nullable|numeric|min:0',
            'compensation_notice' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();

            $joining = Carbon::parse($data['joining_date']);
            $leaving = Carbon::parse($data['leaving_date']);
            $years = $joining->diffInYears($leaving);

            // EOS Formula - EXACTLY like your blade version
            if ($years < 1) {
                $eosAmount = 0;
            } elseif ($years < 5) {
                $eosAmount = $data['basic_salary'] * 21 / 30 * $years; // 21 days per year
            } else {
                $eosAmount = $data['basic_salary'] * 30 / 30 * $years; // 30 days per year
            }

            $additions = ($data['incentive'] ?? 0) + ($data['rewards'] ?? 0) + ($data['other_additions'] ?? 0);
            $deductions = ($data['cash_advance'] ?? 0) + ($data['petty_cash'] ?? 0) + ($data['fines'] ?? 0) + ($data['compensation_notice'] ?? 0) + ($data['other_deductions'] ?? 0);
            $leavePay = $data['annual_leave_balance'] * ($data['basic_salary'] / 30);

            $netPay = $eosAmount + $additions + $leavePay - $deductions;

            $data['net_pay'] = $netPay;

            $endOfService = EndOfService::create($data);

            return response()->json([
                'success' => true,
                'message' => 'End of Service record saved successfully.',
                'data' => $endOfService
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to create End of Service record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $endOfService = EndOfService::find($id);

            if (!$endOfService) {
                return response()->json([
                    'success' => false,
                    'message' => 'End of Service record not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'employee_id' => 'required|exists:employees,id',
                'joining_date' => 'required|date',
                'leaving_date' => 'required|date|after:joining_date',
                'basic_salary' => 'required|numeric|min:0',
                'gross_salary' => 'nullable|numeric|min:0',
                'annual_leave_balance' => 'required|numeric|min:0',
                'incentive' => 'nullable|numeric|min:0',
                'rewards' => 'nullable|numeric|min:0',
                'other_additions' => 'nullable|numeric|min:0',
                'cash_advance' => 'nullable|numeric|min:0',
                'petty_cash' => 'nullable|numeric|min:0',
                'fines' => 'nullable|numeric|min:0',
                'compensation_notice' => 'nullable|numeric|min:0',
                'other_deductions' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();


            $joining = Carbon::parse($data['joining_date']);
            $leaving = Carbon::parse($data['leaving_date']);
            $years = $joining->diffInYears($leaving);


            if ($years < 1) $eosAmount = 0;
            elseif ($years < 5) $eosAmount = $data['basic_salary'] * 21 / 30 * $years;
            else $eosAmount = $data['basic_salary'] * 30 / 30 * $years;


            $additions = ($data['incentive'] ?? 0) + ($data['rewards'] ?? 0) + ($data['other_additions'] ?? 0);
            $deductions = ($data['cash_advance'] ?? 0) + ($data['petty_cash'] ?? 0) + ($data['fines'] ?? 0) + ($data['compensation_notice'] ?? 0) + ($data['other_deductions'] ?? 0);

            $leavePay = $data['annual_leave_balance'] * ($data['basic_salary'] / 30);
            $netPay = $eosAmount + $additions + $leavePay - $deductions;

            $data['net_pay'] = $netPay;

            $endOfService->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Record updated successfully.',
                'data' => $endOfService
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to update End of Service record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $endOfService = EndOfService::find($id);

            if (!$endOfService) {
                return response()->json([
                    'success' => false,
                    'message' => 'End of Service record not found'
                ], 404);
            }

            $endOfService->delete();

            return response()->json([
                'success' => true,
                'message' => 'Record deleted successfully.'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete End of Service record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
