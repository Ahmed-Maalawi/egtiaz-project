<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function allCities(Request $request)
    {
        $limit = $request->query('n') ?? 10;
        $cities = City::paginate($limit);

        return CityResource::collection($cities);
    }

    public function search(Request $request)
    {
        $request->validate([
            'q'                 =>'required|string',
        ]);

        $locale = app()->getLocale();
        $search = "%{$request->query('q')}%";
        $cities = City::where("name->{$locale}",'LIKE',$search)
        ->orWhere("description->{$locale}",'LIKE',$search)->paginate(10);

        return CityResource::collection($cities);
    }
}
