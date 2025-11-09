<?php

namespace App\Models;


use App\Traits\ActivityScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class   User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, HasApiTokens, ActivityScopeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'image',
        'salary',
        'status',
        'fcm_token',
        'player_id',
        'moderator_company_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'salary' => 'decimal:2',
            'is_active' => 'boolean'
        ];
    }
    //-----------------------------

    protected $appends = [
        'image_url',
    ];

    //-----------------------------------------------

    protected static function booted() {}

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function companyOfModeration()
    {
        return $this->belongsTo(Company::class, 'moderator_company_id');
    }

    public function employeeStages()
    {
        return $this->hasMany(EmployeeStage::class , 'done_by');
    }

    public function paymentAccounts()
    {
        return $this->belongsToMany(PaymentAccount::class , 'payment_account_users');
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    public function eos()
    {
        return $this->hasOne(EndOfService::class, 'user_id', 'id');
    }

    public function leaves()
    {
        return $this->hasMany(OfficialLeave::class, 'user_id', 'id');
    }
}
