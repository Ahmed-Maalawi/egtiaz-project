<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        if(Cache::has('banners'))
        {
            $banners = Cache::get('banners');
        }else{
            $banners = Banner::get();
        }

        return BannerResource::collection($banners);
    }
}
