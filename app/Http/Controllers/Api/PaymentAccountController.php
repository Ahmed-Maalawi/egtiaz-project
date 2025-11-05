<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeaveResource;
use App\Http\Resources\PaymentAccountResource;
use App\Models\OfficialLeave;
use App\Models\PaymentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentAccountController extends Controller
{
   public function index(Request $request)
   {
       $user = Auth::user();
       $per_page = $request->get('per_page', 10);

       $query = PaymentAccount::with([
           'users',
           'transactions'
       ]);

       if (! $user->hasAnyRole(['super-admin', 'admin'])) {
           $query->whereHas('users', function ($q) use ($user) {
               $q->where('users.id', $user->id);
           });
       }

       $payment_accounts = $query->paginate($per_page);

       return response()->json([
           'success' => true,
           'data' => PaymentAccountResource::collection($payment_accounts),
           'meta' => [
               'current_page' => $payment_accounts->currentPage(),
               'last_page'    => $payment_accounts->lastPage(),
               'per_page'     => $payment_accounts->perPage(),
               'total'        => $payment_accounts->total(),
           ]
       ]);
   }

    public function show(int $id)
    {
        $user = Auth::user();

        if ($user->hasAnyRole(['super-admin', 'admin'])) {
            $payment_account = PaymentAccount::with(['users', 'transactions'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => new PaymentAccountResource($payment_account),
            ]);
        }

        $payment_account = PaymentAccount::with(['users', 'transactions'])
            ->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->find($id);

        if (! $payment_account) {
            return response()->json([
                'success' => false,
                'message' => __('Forbidden: You do not have access to this payment account.'),
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new PaymentAccountResource($payment_account),
        ]);
    }


}
