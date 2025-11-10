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
      $payment_accounts = Auth::user()->paymentAccounts()->get();

       return response()->json([
           'success' => true,
           'data' => PaymentAccountResource::collection($payment_accounts)
       ]);
   }

    public function show(int $id)
    {
        $paymentAccount = PaymentAccount::with(['users', 'transactions'])
            ->findOrFail($id);

        // Eager load relationships
        $paymentAccount->load([
            'transactions' => function($query) {
                $query->latest()->with(['user', 'createdBy']);
            },
            'users.roles',
            'transactions.user.roles',
            'transactions.createdBy.roles'
        ]);

        // Calculate statistics
        $totalTransactions = $paymentAccount->transactions->count();
        $totalAmount = $paymentAccount->transactions->sum('amount');
        $completedTransactions = $paymentAccount->transactions->where('status', 'completed')->count();
        $pendingTransactions = $paymentAccount->transactions->where('status', 'pending')->count();

        // Get all users associated with this payment account
        $assignedUsers = $paymentAccount->users;
        $transactionUsers = $paymentAccount->transactions->pluck('user')->filter()->unique('id');

        // Combine all users (assigned + transaction users)
        $allUsers = $assignedUsers->merge($transactionUsers)->unique('id');

        // Monthly breakdown
        $monthlyBreakdown = $paymentAccount->transactions()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total_amount, COUNT(*) as transaction_count')
            ->where('status', 'completed')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $data['paymentAccount'] = $paymentAccount;
        $data['totalTransactions'] = $totalTransactions;
        $data['totalAmount'] = $totalAmount;
        $data['completedTransactions'] = $completedTransactions;
        $data['pendingTransactions'] = $pendingTransactions;
        $data['monthlyBreakdown'] = $monthlyBreakdown;
        $data['allUsers'] = $allUsers;

        return response()->json([
            'success' => true,
            'message' => 'get payment account details',
            'data' => $data
        ]);
    }


}
