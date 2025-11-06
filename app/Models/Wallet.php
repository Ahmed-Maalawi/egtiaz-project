<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    protected $fillable = [
        'balance', 'company_id',
    ];

    protected $casts = [
        'balance' => 'float',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
