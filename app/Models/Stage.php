<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Stage extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'iqama_type_id',
        'description',
        'order',
        'price',
        'cost',
        'estimated_time_in_days',
        'image',
        'options',
        'file'
    ];

    protected $casts = [
        'price'                         => 'float',
        'cost'                         => 'float',
        'options'                       => 'array',
        'estimated_time_in_days'        => 'integer',
    ];

    public $translatable = [
        'name',
        'description',
    ];

    protected static function booted()
    {
        static::addGlobalScope('ordered',function(Builder $builder){
            $builder->orderBy('order');
        });
    }

    public function iqamaType()
    {
        return $this->belongsTo(IqamaType::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getFileUrlAttribute()
    {
        return $this->file ? asset('storage/' . $this->file) : null;
    }

    public function employeeStages()
    {
        return $this->hasMany(EmployeeStage::class);
    }

    public function upcomingStage()
    {
        return $this->employeeStages()->where('status', 'pending')->limit(1);
    }

}
