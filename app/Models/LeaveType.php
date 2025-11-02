<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LeaveType extends Model
{
    use HasTranslations;

    protected $table = 'leave_types';
    protected $guarded = [];

    protected $translatable = ['name', 'description'];

    protected $casts = [
        'name'          => 'json',
        'description'   => 'json',
        'active'        => 'boolean',
    ];

    public function leaves()
    {
        return $this->hasMany(OfficialLeave::class, 'leave_type_id', 'id');
    }


    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }



}
