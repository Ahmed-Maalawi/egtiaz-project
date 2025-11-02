<?php

namespace App\Models;

use App\Traits\ActivityScopeTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Company extends Model
{
    use HasTranslations , ActivityScopeTrait;

    protected $fillable = [
        'name','description','image','banner_image','status'
    ];

    public $translatable = ['name','description'];

    protected $with = ['wallet'];

    protected $appends = [
        'balance',
    ];

    protected static function booted()
    {
        static::created(function($company){
            $company->wallet()->create();
        });
    }

    public function moderators()
    {
        return $this->hasMany(User::class , 'moderator_company_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
    
    public function getBalanceAttribute()
    {
        return $this->wallet->balance ?? 0;
    }


    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }

    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image ? asset('storage/'.$this->banner_image) : null;
    }
}
