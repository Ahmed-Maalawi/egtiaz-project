<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EndOfService extends Model
{
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getServiceYearsAttribute()
    {
        return \Carbon\Carbon::parse($this->joining_date)
            ->diffInYears(\Carbon\Carbon::parse($this->leaving_date));
    }

}
