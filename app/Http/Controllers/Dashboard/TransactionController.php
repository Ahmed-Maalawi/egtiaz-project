<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['paymentAccount', 'employeeStage', 'createdBy'])
            ->latest();

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Transaction::with(['employee', 'paymentAccount'])->findOrFail($id);

        return response()->json([
            'transaction_id' => $transaction->transaction_id,
            'type' => $transaction->type,
            'type_display' => $this->getTypeDisplay($transaction->type),
            'status' => $transaction->status,
            'status_display' => $this->getStatusDisplay($transaction->status),
            'amount' => number_format($transaction->amount, 2),
            'from_balance_before' => number_format($transaction->from_balance_before, 2),
            'from_balance_after' => number_format($transaction->from_balance_after, 2),
            'description' => $transaction->description,
            'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
            'processed_at' => $transaction->processed_at ? $transaction->processed_at->format('Y-m-d H:i:s') : null,
        ]);
    }

    private function getTypeDisplay($type)
    {
        return match($type) {
            'stage_payment' => __('Stage Payment'),
            'salary_payment' => __('Salary Payment'),
            'refund' => __('Refund'),
            'charge' => __('Charge'),
            default => $type,
        };
    }

    private function getStatusDisplay($status)
    {
        return match($status) {
            'pending' => __('Pending'),
            'completed' => __('Completed'),
            'failed' => __('Failed'),
            'refund' => __('Refunded'),
            'canceled' => __('Canceled'),
            default => $status,
        };
    }
}
