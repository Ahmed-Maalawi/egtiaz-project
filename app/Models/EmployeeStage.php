<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EmployeeStage extends Model
{
    protected $fillable = [
        'options',
        'expired_at',
        'done_by',
        'completed_at',
        'status',
        'stage_id',
        'employee_id',
        'currently_type',
        'payment_status',
        'paid_at',
        //        'amount_paid',
        'amount_cost',
        'price_amount',
        'transaction_id',
        'wallet_transaction_id',
    ];

    protected $appends = ['profit'];

    protected $casts = [
        'options' => 'array',
        'completed_at' => 'datetime',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'price_amount' => 'decimal:2',
    ];

    protected $with = 'files';

    protected static function booted()
    {
        static::deleted(function ($employeeStage) {
            if ($employeeStage->files()) {
                foreach ($employeeStage->files as $file) {
                    Controller::deleteFile($file->path);
                }
            }
        });
    }

    public function doneBy()
    {
        return $this->belongsTo(User::class, 'done_by')->withDefault([
            'name' => __('User Deleted'),
        ]);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeCurrent(Builder $builder)
    {
        return $builder->where('currently_type', 1);
    }

    public function files()
    {
        return $this->hasMany(EmployeeStageFile::class);
    }

    public function transactions()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function markAsPaid($amount): void
    {
        $this->update([
            'payment_status' => 'paid',
            'price_amount' => $amount,
            'paid_at' => now(),
        ]);
    }

    public function getCostAttribute()
    {
        return $this->stage->cost ?? 0;
    }

    public function getProfitAttribute()
    {
        //        // Make sure the related stage is loaded
        //        if (!$this->relationLoaded('stage')) {
        //            $this->load('stage');
        //        }
        //
        //        $stage = $this->stage;
        //
        //        if (!$stage || !isset($stage->price) || !isset($stage->cost)) {
        //            return 0;
        //        }

        return (float) $this->amont_paid - (float) $this->amount_cost;
    }

    /**
     * Get the wallet transaction (price)
     */
    public function walletTransaction()
    {
        return $this->belongsTo(WalletTransaction::class, 'wallet_transaction_id');
    }

    /**
     * Get both transactions
     */
    public function allTransactions()
    {
        return [
            'cost_transaction' => $this->transaction,
            'price_transaction' => $this->walletTransaction,
            'profit' => $this->price_amount - $this->amount_cost,
        ];
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePendingPayment($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope for completed stages
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending stages
     */
    public function scopePending($query)
    {
        return $query->where('status', '!=', 'completed');
    }

    public function scopeProfitReport($query, array $filters)
    {

        if (! empty($filters['company_id'])) {
            $query->whereHas('employee', function ($q) use ($filters) {
                $q->where('company_id', $filters['company_id']);
            });
        }

        if (! empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        if (! empty($filters['from_date'])) {
            $query->whereDate('completed_at', '>=', $filters['from_date']);
        }

        if (! empty($filters['to_date'])) {
            $query->whereDate('completed_at', '<=', $filters['to_date']);
        }

        return $query->orderByDesc('completed_at');
    }
}
