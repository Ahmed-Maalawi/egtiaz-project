<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EndOfService extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getServiceYearsAttribute()
    {
        return \Carbon\Carbon::parse($this->joining_date)
            ->diffInYears(\Carbon\Carbon::parse($this->leaving_date));
    }

}
