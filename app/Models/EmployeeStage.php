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
        'amount_paid',
    ];

    protected $appends = ['profit'];

    protected $casts = [
        'options' => 'array',
        'completed_at' => 'datetime',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'amount_paid' => 'decimal:2'
    ];

    protected $with = 'files';

    protected static function booted()
    {
        static::deleted(function($employeeStage){
            if($employeeStage->files()){
                foreach($employeeStage->files as $file){
                    Controller::deleteFile($file->path);
                }
            }
        });
    }

    public function doneBy()
    {
        return $this->belongsTo(User::class , 'done_by')->withDefault([
            'name'  => __('User Deleted'),
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
        return $builder->where('currently_type',1);
    }

    public function files()
    {
        return $this->hasMany(EmployeeStageFile::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function markAsPaid($amount): void
    {
        $this->update([
            'payment_status' => 'paid',
            'amount_paid' => $amount,
            'paid_at' => now()
        ]);
    }

    public function getCostAttribute()
    {
        return $this->stage->cost ?? 0;
    }

    public function getProfitAttribute()
    {
        // Make sure the related stage is loaded
        if (!$this->relationLoaded('stage')) {
            $this->load('stage');
        }

        $stage = $this->stage;

        if (!$stage || !isset($stage->price) || !isset($stage->cost)) {
            return 0;
        }

        return (float) $stage->price - (float) $stage->cost;
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

    public function scopeProfitReport($query, array $filters)
    {

        if (!empty($filters['company_id'])) {
            $query->whereHas('employee', function ($q) use ($filters) {
                $q->where('company_id', $filters['company_id']);
            });
        }


        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }


        if (!empty($filters['from_date'])) {
            dd('tes');
            $query->whereDate('completed_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('completed_at', '<=', $filters['to_date']);
        }

        return $query->orderByDesc('completed_at');
    }
}
