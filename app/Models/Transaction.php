<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $guarded = [];


    protected $with = ['paymentAccount', 'employeeStage'];

    protected $casts = [
        'amount'        => 'float',
        'metadata' => 'array',
        'processed_at'  => 'datetime',
    ];

    public function fromPaymentAccount()
    {
        return $this->belongsTo(PaymentAccount::class, 'from_payment_account_id');
    }

    public function toWallet()
    {
        return $this->belongsTo(Wallet::class, 'to_wallet_id', 'id');
    }

    public function paymentAccount()
    {
        return $this->belongsTo(PaymentAccount::class, 'payment_account_id', 'id');
    }

    public function employeeStage()
    {
        return $this->belongsTo(EmployeeStage::class, 'employee_stage_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function transactionable()
    {
        return $this->morphTo();
    }

    /**
     * Get related wallet transaction for stage payments
     */
    public function relatedWalletTransaction()
    {
        if ($this->type === 'stage_payment' && $this->employee_stage_id) {
            return WalletTransaction::where('employee_stage_id', $this->employee_stage_id)
                ->where('type', 'stage_payment')
                ->first();
        }
        return null;
    }

    /**
     * Scope for stage payments
     */
    public function scopeStagePayments($query)
    {
        return $query->where('type', 'stage_payment')->whereNotNull('employee_stage_id');
    }

    public function getTransactionableTypeNameAttribute()
    {
        $class = class_basename($this->transactionable_type);


        return Str::headline($class); // StagePayment → Stage Payment
    }
}
