<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PaymentAccount extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name','description','balance',
    ];

    public $translatable = [
        'name','description',
    ];

    protected $casts = [
        'balance' =>'float',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'payment_account_users');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
