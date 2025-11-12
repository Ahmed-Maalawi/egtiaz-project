<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\PaymentAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payment_accounts = PaymentAccount::latest()->get();

        return view('admin.payment-accounts.index', compact('payment_accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.payment-accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar'           => 'required|string',
            'name_en'           => 'required|string',
            'balance'           => 'required|numeric',
            'description_ar'    => 'nullable|required_with:description_en|string',
            'description_en'    => 'nullable|required_with:description_ar|string',
        ]);

        PaymentAccount::create([
            'name'  => [
                'en'    => $request->name_en,
                'ar'    => $request->name_ar,
            ],
            'description'               => [
                'ar'    => $request->description_ar,
                'en'    => $request->description_en,
            ],
            'balance' => $request->balance,
        ]);

        return redirect()->route('admins.paymentAccounts.index')
            ->with('success', __('Payment Account Added Successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentAccount $paymentAccount)
    {
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

        return view('admin.payment-accounts.show', compact(
            'paymentAccount',
            'totalTransactions',
            'totalAmount',
            'completedTransactions',
            'pendingTransactions',
            'monthlyBreakdown',
            'allUsers'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentAccount $paymentAccount)
    {
        return view('admin.payment-accounts.edit', compact('paymentAccount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentAccount $paymentAccount)
    {
        $request->validate([
            'name_ar'                       => 'required|string',
            'name_en'                       => 'required|string',
            'name_en'                       => 'required|string',
            'balance'           => 'required|numeric',
            'description_ar'                => 'nullable|required_with:description_en|string',
            'description_en'                => 'nullable|required_with:description_ar|string',
        ]);

        $paymentAccount->update([
            'name'                      => [
                'en'                        => $request->name_en,
                'ar'                        => $request->name_ar,
            ],
            'description'               => [
                'ar'                        => $request->description_ar,
                'en'                        => $request->description_en,
            ],
            'balance' => $request->balance,
        ]);

        return redirect()->route('admins.paymentAccounts.index')
            ->with('success', __('Payment Account Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentAccount $paymentAccount)
    {
        if ($paymentAccount->balance != 0) {
            return redirect()->back()
                ->with('error', __('Payment Account Balance Must Be 0'));
        }

        $paymentAccount->delete();

        return redirect()->route('admins.paymentAccounts.index')
            ->with('success', __('Payment Account Deleted Successfully'));
    }
}
