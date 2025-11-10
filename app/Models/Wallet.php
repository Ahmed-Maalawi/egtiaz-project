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

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function paymentTransactions()
    {
        return $this->hasMany(Transaction::class, 'to_wallet_id', 'id');
    }
}
