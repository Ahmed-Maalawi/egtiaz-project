<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::latest()->get();

        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar'                   => 'required|string',
            'name_en'                   => 'required|string',
            'description_ar'            => 'required|string',
            'description_en'            => 'required|string',
            'status'                    => 'required|in:active,inactive',
            'image'                     => 'required|image|max:5120',
            'banner_image'              => 'nullable|image|max:5120',
        ]);

        $image_path = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('companies', 'public');
        }

        $banner_path = null;
        if ($request->hasFile('banner_image')) {
            $banner_image = $request->file('banner_image');
            $banner_path = $banner_image->store('companies', 'public');
        }

        Company::create([
            'name'                  => [
                'ar'                    => $request->name_ar,
                'en'                    => $request->name_en,
            ],
            'description'           => [
                'ar'                    => $request->description_ar,
                'en'                    => $request->description_en,
            ],
            'status'                => $request->status,
            'image'                 => $image_path,
            'banner_image'          => $banner_path,
        ]);

        return redirect()->route('admins.companies.index')
            ->with('success', __('Company Added Successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name_ar'                   => 'required|string',
            'name_en'                   => 'required|string',
            'description_ar'            => 'required|string',
            'description_en'            => 'required|string',
            'status'                    => 'required|in:active,inactive',
            'image'                     => 'nullable|image|max:5120',
            'banner_image'              => 'nullable|image|max:5120',
        ]);

        $image_path = $company->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('companies', 'public');

            if ($company->image) {
                Controller::deleteFile($company->image);
            }
        }

        $banner_path = $company->banner_image;
        if ($request->hasFile('banner_image')) {
            $banner_image = $request->file('banner_image');
            $banner_path = $banner_image->store('companies', 'public');
            if ($company->banner_image) {
                Controller::deleteFile($company->banner_image);
            }
        }

        $company->update([
            'name'                  => [
                'ar'                    => $request->name_ar,
                'en'                    => $request->name_en,
            ],
            'description'           => [
                'ar'                    => $request->description_ar,
                'en'                    => $request->description_en,
            ],
            'status'                => $request->status,
            'image'                 => $image_path,
            'banner_image'          => $banner_path,
        ]);

        return redirect()->route('admins.companies.index')
            ->with('success', __('Company Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        if ($company->image) {
            Controller::deleteFile($company->image);
        }

        if ($company->banner_image) {
            Controller::deleteFile($company->banner_image);
        }

        $company->delete();

        return redirect()->route('admins.companies.index')
            ->with('success', __('Company Deleted Successfully'));
    }

    public function toggleStatus($id)
    {
        $company = Company::findOrFail($id);

        $company->status = $company->status == 'active' ? 'inactive' : 'active';
        $company->save();

        return response()->json([
            'message'               => __('company status updated')
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'q'                 =>'nullable|string',
        ]);

        $locale = app()->getLocale();

        $search = "%{$request->query('q')}%";

        $companies = Company::where("name->{$locale}",'like',$search)->limit(10)->get();

        return response()->json(
            $companies->map(function($company) use ($locale){
                return [
                    'id'                =>$company->id,
                    'name'              =>$company->getTranslation('name',$locale),
                ];
            })
        );
    }
}
