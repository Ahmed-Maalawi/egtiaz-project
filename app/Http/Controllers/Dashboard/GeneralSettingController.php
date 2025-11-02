<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;


class GeneralSettingController extends Controller
{
    public function switchLanguage(Request $request)
    {
        $request->validate(['locale' => 'required|in:en,ar']);
        App::setLocale($request->locale);
        Session::put('locale',$request->locale);
        return redirect()->back();
    }
}
