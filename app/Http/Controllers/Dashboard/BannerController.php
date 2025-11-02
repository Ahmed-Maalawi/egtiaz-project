<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Banner;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_ar' => 'nullable|required_with:title_en|string|max:255',
            'title_en' => 'nullable|required_with:title_ar|string|max:255',
            'image'    => 'required|image|max:2048',
        ]);

        $image_path = $request->file('image')->store('banners', 'public');

        $title = [];

        if ($request->filled('title_ar')) {
            $title['ar'] = $request->title_ar;
        }

        if ($request->filled('title_en')) {
            $title['en'] = $request->title_en;
        }

        Banner::create([
            'title' => empty($title) ? null : $title,
            'image' => $image_path,
        ]);

        return redirect()->route('admins.banners.index')
            ->with('success', __('Banner Added Successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title_ar' => 'nullable|required_with:title_en|string|max:255',
            'title_en' => 'nullable|required_with:title_ar|string|max:255',
            'image'    => 'nullable|image|max:2048',
        ]);

        $image_path = $banner->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('banners', 'public');
            Controller::deleteFile($banner->image);
        }

        $title = [];
        if ($request->filled('title_ar')) {
            $title['ar'] = $request->title_ar;
        }
        if ($request->filled('title_en')) {
            $title['en'] = $request->title_en;
        }

        $banner->update([
            'title' => empty($title) ? null : $title,
            'image' => $image_path,
        ]);

        return redirect()->route('admins.banners.index')
            ->with('success', __('Banner Updated Successfully'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            Controller::deleteFile($banner->image);
        }
        $banner->delete();

        return redirect()->route('admins.banners.index')
            ->with('success', __('Banner Deleted Successfully'));
    }
}
