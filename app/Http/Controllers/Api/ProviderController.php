<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\ServiceProviderResource;
use App\Models\Category;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class ProviderController extends Controller
{
    public function singleProvider(Request $request)
    {
        $request->validate([
            'id'                => 'required|exists:service_providers,id',
        ]);

        $provider = ServiceProvider::withCount('reviews')
            ->with(['category', 'services','nearestServiceProviderBranch','serviceProviderBranches'])->active()->findOrFail($request->query('id'));

        return new ServiceProviderResource($provider);
    }

    public function search(Request $request)
    {
        $request->validate([
            'q'                     => 'required|string',
        ]);

        $search = "%{$request->query('q')}%";

        $locale = app()->getLocale();
        $user = Auth::guard('sanctum')->user();

        $providers = ServiceProvider::with('category', 'nearestServiceProviderBranch')
            ->active()
            ->where("name->{$locale}", 'LIKE', $search)
            ->orWhere("description->{$locale}", 'LIKE', $search);

        $providers = $providers->paginate(10);

        return ServiceProviderResource::collection($providers);
    }

    public function likeToggle(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:service_providers,id',
        ]);

        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json([
                'message' => __('unauthenticated'),
            ], 401);
        }

        $liked = DB::table('service_provider_users')
            ->where('user_id', $user->id)
            ->where('service_provider_id', $request->id)
            ->exists();

        if ($liked) {
            // Detach if already liked
            $user->serviceProviders()->detach($request->id);

            return response()->json([
                'message' => __('Like Removed'),
            ]);
        } else {
            // Attach if not liked yet
            $user->serviceProviders()->attach($request->id);

            return response()->json([
                'message' => __('Like Added'),
            ]);
        }
    }

    public function addReview(Request $request)
    {
        $request->validate([
            'id'                    => 'required|exists:service_providers,id',
            'content'               => 'required|string',
        ]);

        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json([
                'message'                       => __('unauthenticated'),
            ], 401);
        }

        $review = Review::create([
            'user_id'                       => $user->id,
            'service_provider_id'           => $request->input('id'),
            'content'                       => $request->input('content'),
        ]);

        return response()->json([
            'message'                   => __('Review Added Successfully'),
            'review'                    => new ReviewResource($review),
        ]);
    }

    public function myProviders()
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'message'                   => __('unauthenticated'),
            ], 401);
        }

        $providers = ServiceProvider::active()
        ->whereHas('users', function ($query) use ($user) {
            return $query->where('id', $user->id);
        })->active()->paginate(10);

        return ServiceProviderResource::collection($providers);
    }

    public function store(Request $request)
    {
        $categories_ids = Category::active()->pluck('id')->toArray();
        $services_ids = Service::get()->pluck('id')->toArray();

        $request->validate([
            'name_ar'               => 'required|string|max:255',
            'name_en'               => 'required|string|max:255',
            'description_en'        => 'nullable|required_with:description_ar|string',
            'description_ar'        => 'nullable|required_with:description_en|string',
            'address_ar'            => 'nullable|required_with:address_en|string',
            'address_en'            => 'nullable|required_with:address_ar|string',
            'phone'                 => ['required', 'string', 'max:15'],
            'category_id'           => ['required', Rule::in($categories_ids)],
            'image'                 => 'required|image|max:2048',
            'discount_percent'      => 'required|numeric|min:1|max:100',
            'latitude'              => 'required|numeric|between:-90,90',
            'longitude'             => 'required|numeric|between:-180,180',
            'open_at'               => 'required|date_format:H:i',
            'close_at'              => 'required|date_format:H:i',
            'city_id'               => 'required|exists:cities,id',
            'services_ids'          => 'nullable|array',
            'services_ids.*'        => ['required', 'integer', Rule::in($services_ids)],
        ]);

        $image = $request->file('image');
        $image_path = $image->store('providers', 'public');

        $user = Auth::guard('sanctum')->user();

        if (!$user || !$user->hasRole('provider_owner')) {
            return response()->json([
                'message'               => __('Unauthorized Or Normal User'),
            ], 401);
        }

        try {
            DB::beginTransaction();
            $service_provider = ServiceProvider::create([
                'name'                  => [
                    'en'                    => $request->name_en,
                    'ar'                    => $request->name_ar,
                ],
                'description'           => [
                    'en'                    => $request->description_en,
                    'ar'                    => $request->description_ar,
                ],
                'address'               => [
                    'en'                    => $request->address_en,
                    'ar'                    => $request->address_ar,
                ],
                'phone'                 => $request->phone,
                'category_id'           => $request->category_id,
                'image'                 => $image_path,
                'discount_percent'      => $request->discount_percent,
                'latitude'              => $request->latitude,
                'longitude'             => $request->longitude,
                'options'                   => [
                    'open_at'                   => $request->open_at,
                    'close_at'                  => $request->close_at,
                ],
                'city_id'               => $request->city_id,
                'status'                => 'inactive',
                'owner_id'              => $user->id,
            ]);

            if ($request->has('services')) {
                $service_provider->services()->attach($request->services);
            }

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message'               => __('An Error Occurred'),
            ], 403);
        }


        return response()->json([
            'message'               => __('Service Provider Added Successfully'),
            'service_provider'      => new ServiceProviderResource($service_provider),
        ]);
    }

    public function providersOwned(Request $request)
    {
        $request->validate([
            'n'                 => 'nullable|integer|min:1',
        ]);

        $limit = $request->query('n') ?? 10;

        $user = Auth::guard('sanctum')->user();

        if (!$user || $user->status == 'inactive') {
            return response()->json([
                'message'                   => __('Unauthenticated or banned user')
            ], 401);
        }

        $providers = ServiceProvider::with(['category', 'services'])->where('owner_id', $user->id)->paginate($limit);

        return response()->json(ServiceProviderResource::collection($providers));
    }

    public function update(Request $request, ServiceProvider $serviceProvider)
    {
        $categories_ids = Category::active()->pluck('id')->toArray();
        $services_ids = Service::get()->pluck('id')->toArray();
        $user = Auth::guard('sanctum')->user();

        $request->validate([
            'name_ar'               => 'required|string|max:255',
            'name_en'               => 'required|string|max:255',
            'description_en'        => 'nullable|required_with:description_ar|string',
            'description_ar'        => 'nullable|required_with:description_en|string',
            'address_ar'            => 'nullable|required_with:address_en|string',
            'address_en'            => 'nullable|required_with:address_ar|string',
            'phone'                 => ['sometimes','required', 'string', 'max:15'],
            'category_id'           => ['sometimes','required', Rule::in($categories_ids)],
            'image'                 => 'sometimes|required|image|max:2048',
            'discount_percent'      => 'sometimes|required|numeric|min:1|max:100',
            'latitude'              => 'sometimes|required|numeric|between:-90,90',
            'longitude'             => 'sometimes|required|numeric|between:-180,180',
            'open_at'               => 'sometimes|required_with:close_at|date_format:H:i',
            'close_at'              => 'sometimes|required_with:open_at|date_format:H:i',
            'city_id'               => 'sometimes|required|exists:cities,id',
            'services_ids'          => 'nullable|array',
            'services_ids.*'        => ['required', 'integer', Rule::in($services_ids)],
        ]);


        if (!$user || !$user->hasRole('provider_owner')) {
            return response()->json([
                'message'               => __('Unauthorized Or Normal User'),
            ], 401);
        }
        
        if ($serviceProvider->owner_id != $user->id) {
            return response()->json([
                'message'               => __('Not the Owner'),
            ], 401);
        }


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('providers', 'public');
            if ($serviceProvider->image) {
                Controller::deleteFile($serviceProvider->image);
            }
            $serviceProvider->image = $image_path;
        }

        $serviceProvider->name = [
            'en'                => $request->name_en,
            'ar'                => $request->name_ar,
        ];

        if ($request->filled('description_en')) {
            $serviceProvider->description = [
                'en'                => $request->description_en,
                'ar'                => $request->description_ar,
            ];
        }

        if ($request->filled('address_en')) {
            $serviceProvider->address = [
                'en'                    => $request->address_en,
                'ar'                    => $request->address_ar,
            ];
        }

        if ($request->filled('latitude')) {
            $serviceProvider->latitude = $request->latitude;
            $serviceProvider->longitude = $request->longitude;
        }

        if ($request->filled('phone')) {
            $serviceProvider->phone = $request->phone;
        }

        if ($request->filled('category_id')) {
            $serviceProvider->category_id = $request->category_id;
        }

        if ($request->filled('city_id')) {
            $serviceProvider->city_id = $request->city_id;
        }

        try {
            DB::beginTransaction();
            $serviceProvider->save();

            if ($request->has('services')) {
                $serviceProvider->services()->sync($request->services);
            }

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message'               => __('An Error Occurred'),
            ], 403);
        }

        return response()->json([
            'message'               => __('Service Provider Updated Successfully'),
            'service_provider'      => new ServiceProviderResource($serviceProvider),
        ]);
    }

    public function nearbyProvider()
    {
         $user = Auth::guard('sanctum')->user();

        $providers = ServiceProvider::with('nearestServiceProviderBranch')
            ->active()
            ->paginate(10);
            
        return ServiceProviderResource::collection($providers);
    }

}
