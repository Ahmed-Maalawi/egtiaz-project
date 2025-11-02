<?php

namespace App\Http\Controllers;


use App\Models\EmployeeStage;
use App\Models\PaymentAccount;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

//        $validated = $request->validate([
//            'user_id'            => 'required|integer|exists:employees,id',
//            'employee_stage_id'  => 'required|integer|exists:employee_stages,id',
//            'payment_account_id' => 'required|integer|exists:payment_accounts,id',
//        ]);



        // this selecting will be changed to returned for views
        $employeeStage = EmployeeStage::with(['stage', 'employee'])->findOrFail($request['employee_stage_id']);

        $paymentAccount = PaymentAccount::findOrFail($request->payment_account_id);

        $user = User::findOrFail($request->user_id);

        $transactionData = $this->transactionService->processTransaction($employeeStage, $user, $paymentAccount);

        return response()->json([
            'message'   => 'Success',
            'data'      => $transactionData
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
