<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $table = 'wallet_transactions';

    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'completed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function employeeStage()
    {
        return $this->belongsTo(EmployeeStage::class);
    }

    /**
     * Scope to get stage payments only
     */
    public function scopeStagePayments($query)
    {
        return $query->where('type', 'stage_payment')->whereNotNull('employee_stage_id');
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $fromDate, $toDate)
    {
        return $query->whereBetween('completed_at', [$fromDate, $toDate]);
    }

    /**
     * Scope to apply filters dynamically.
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        return $query;
    }

}
