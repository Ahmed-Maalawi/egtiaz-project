<?php

namespace App\Models;

use App\Traits\ActivityScopeTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Employee extends Model
{
    use ActivityScopeTrait;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'image',
        'passport_image',
        'passport_number',
        'gender',
        'status',
        'company_id',
        'iqama_type_id',
        'expired_date'
    ];

    protected $casts = [
        'expired_date'  => 'date',
    ];

    protected $with = ['files'];

    protected static function booted()
    {
        static::created(function ($employee) {
            $stages = Stage::where('iqama_type_id', $employee->iqama_type_id)->get();
            foreach ($stages as $stage) {
                EmployeeStage::create([
                    'employee_id'                   => $employee->id,
                    'stage_id'                      => $stage->id,
                    'status'                        => 'pending',
                ]);
            }
        });
    }

    public function upcomingStage()
    {
        return $this->hasOne(EmployeeStage::class)
            ->where('status', 'pending')
            ->join('stages', 'stages.id', '=', 'employee_stages.stage_id')
            ->orderBy('stages.order', 'asc')
            ->select('employee_stages.*'); // important so you get only EmployeeStage columns
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function files()
    {
        return $this->hasMany(EmployeeFile::class);
    }

    public function iqamaType()
    {
        return $this->belongsTo(IqamaType::class);
    }

    public function gender(): Attribute
    {
        return Attribute::make(
            function ($value) {
                if ($value == 'm') {
                    return __('Male');
                } else {
                    return __('Female');
                }
            },
        );
    }

    public function employeeStages()
    {
        return $this->hasMany(EmployeeStage::class);
    }

    public function scopeActive($query)
    {
        return $query->whereStatus('active');
    }

    public function leaves()
    {
        return $this->belongsTo(OfficialLeave::class, 'id', 'employee_id');
    }

    public function eos()
    {
        return $this->hasOne(EndOfService::class, 'employee_id', 'id');
    }

    public function scopeFilter($query, array $filters)
    {
        $locale = App::getLocale();

        if (isset($filters['name'])) {
            $query->where("name->$locale", 'like', '%' . $filters['name'] . '%');
        }

        return $query;
    }
//    public function getCurrentMonthSalaryAttribute()
//    {
//        return $this->salaries()
//            ->where('month', now()->format('Y-m'))
//            ->first();
//    }
}
