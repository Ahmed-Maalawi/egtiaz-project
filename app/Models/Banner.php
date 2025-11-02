<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class Banner extends Model
{
    use HasTranslations;
    protected $guarded = [];

    public $translatable = ['title'];

}
