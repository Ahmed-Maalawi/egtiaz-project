<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $guarded = [];


    protected $with = ['paymentAccount', 'employeeStage'];

    protected $casts = [
        'amount'        => 'float',
        'processed_at'  => 'datetime',
    ];


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
}
