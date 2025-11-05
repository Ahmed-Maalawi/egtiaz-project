<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficialLeave extends Model
{
    protected $guarded = [];

    protected $table = 'official_leaves';

    protected $with = ['employee', 'approver'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeCurrentYear($query)
    {
        return $query->whereYear('start_date', date('Y'));
    }
    public function scopeFilter($query, $filter)
    {
        if (isset($filters['employee_id'])) {
            $query->where("employee_id", $filters['employee_id']);
        }

        if (isset($filters['approved_by'])) {
            $query->where("approved_by", $filters['approved_by']);
        }

        if (isset($filters['type'])) {
            $query->where("type", $filters['type']);
        }

        if (isset($filters['reason'])) {
            $query->where("reason", 'like', '%' . $filters['reason'] . '%');
        }

        if (isset($filters['notes'])) {
            $query->where("notes", 'like', '%' . $filters['notes'] . '%');
        }


        return $query;
    }
}
