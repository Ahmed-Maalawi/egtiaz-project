<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeStageFile extends Model
{
    protected $fillable = 
    [
        'employee_stage_id','path',
    ];

    protected $appends = ['file_path'];

    public function employeeStage()
    {
        return $this->belongsTo(EmployeeStage::class);
    }

    public function getFilePathAttribute()
    {
        return $this->path ? asset('storage/'.$this->path) : null ;
    }

    
}
