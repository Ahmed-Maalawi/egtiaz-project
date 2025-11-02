<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class IqamaType extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
    ];

    public $translatable = ['name', 'description'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function stages()
    {
        return $this->hasMany(Stage::class);
    }
}
