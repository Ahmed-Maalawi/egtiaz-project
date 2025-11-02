<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

abstract class Controller
{
    public static function deleteFile($file){
        Storage::delete($file);
    }
}
