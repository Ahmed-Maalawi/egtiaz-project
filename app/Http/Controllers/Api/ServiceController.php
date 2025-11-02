<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceProviderResource;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function allServices(Request $request)
    {
        $limit = $request->query('n') ?? 10 ;
        $services = Service::paginate($limit);
        return ServiceResource::collection($services);
    }

    public function providersByService(Request $request)
    {
        $request->validate([
            'id'                    =>'required|exists:services,id',
        ]);

        $user = Auth::guard('sanctum')->user();

        $providers = ServiceProvider::with(['reviews','nearestServiceProviderBranch'])->whereHas('services',function($query) use ($request){
            return $query->where('id',$request->query('id'));
        })->active()->paginate(10);

        return ServiceProviderResource::collection($providers);
    }

    public function popularServices(Request $request)
    {
        $limit = $request->query('n') ?? 10;
        $services = Service::withCount('serviceProviders')
        ->orderBy('service_providers_count','DESC')->limit($limit)->get();

        return ServiceResource::collection($services);
    }

    
}
