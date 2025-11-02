<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MainCategoryResource;
use App\Http\Resources\ServiceProviderResource;
use App\Models\Category;
use App\Models\MainCategory;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function mainCategories()
    {
        $categories = MainCategory::active()->paginate(10);
        return MainCategoryResource::collection($categories);
    }

    public function subCategories(Request $request)
    {
        $categories = MainCategory::active()->pluck('id')->toArray();
        $request->validate([
            'category_id'               =>['required',Rule::in($categories)],
        ]);

        $id = $request->query('category_id');

        $main_category = MainCategory::findOrFail($id);

        $sub_categories = Category::active()->latest()->where('main_category_id',$id)->get();

        return response()->json([
            'mainCategory'                      =>new MainCategoryResource($main_category),
            'subCategories'                     =>CategoryResource::collection($sub_categories),
        ]);
    }

    public function providersByCategory(Request $request)
    {
        $sub_categories = Category::pluck('id')->toArray();

        $request->validate([
            'category_id'                       =>['required',Rule::in($sub_categories)],
        ]);

        $user = Auth::guard('sanctum')->user();

        $providers = ServiceProvider::with('category','nearestServiceProviderBranch')
        ->withCount('reviews')
        ->active()
        ->where('category_id',$request->query('category_id'));

        $providers = $providers->paginate(10);


        return ServiceProviderResource::collection($providers);
    }
}
