<?php

namespace App\Services;


use App\Models\EmployeeStage;
use App\Models\PaymentAccount;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use GPBMetadata\Google\Api\Log;
use Illuminate\Support\Facades\DB;


class TransactionService
{
    public function processTransaction(EmployeeStage $employeeStage,User $user, PaymentAccount $paymentAccount, Wallet $wallet = null)
    {
        return DB::transaction(function () use ($employeeStage, $user, $paymentAccount) {
            try {

                $amount = $employeeStage->stage->price;


                // check balance
                if (!$this->hasSufficientBalance($paymentAccount, $amount)) {
                    throw new \Exception(__("balance not sufficient"));
                }


                // Check if stage has already been paid for this employee
                $existingTransaction = Transaction::where('employee_id', $employeeStage->employee->id)
                    ->where('stage_id', $employeeStage->stage->id)
                    ->where('status', 'completed')
                    ->exists();

                if ($existingTransaction) {
                    throw new \Exception(__("Stage has already been paid for this employee"));
                }


            } catch (\Exception $e) {
                if (isset($transaction)) {
                    Log::error('stage payment failed:',[
                        'employee_id' => $employeeStage->employee->id,
                        'employee_name' => $employeeStage->employee->name,
                        'stage_id' => $employeeStage->stage->id,
                        'stage_name' => $employeeStage->stage->name,
                        'employee_stage_id' => $employeeStage->id,
//                        'error' => $e->getMessage(),
//                        'trace' => $e->getTraceAsString()
                    ]);
                }
                throw $e;
            }
        });
    }


    public function hasSufficientBalance(PaymentAccount $paymentAccount, float $amount): bool
    {
        return $paymentAccount->balance >= $amount;
    }
}
