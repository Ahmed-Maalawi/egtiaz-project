<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeaveTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveTypes = LeaveType::paginate(10);
        return view('admin.hr.leave-types.index', compact('leaveTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.hr.leave-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string'
        ]);


        if ($request->filled('name_ar')) {
            $name['ar'] = $request->name_ar;
        }

        if ($request->filled('name_en')) {
            $name['en'] = $request->name_en;
        }

        if ($request->filled('description_ar')) {
            $description['ar'] = $request->description_ar;
        }

        if ($request->filled('description_en')) {
            $description['en'] = $request->description_en;
        }

        LeaveType::create([
            'name' => empty($name) ? null : $name,
            'description' => empty($description) ? null : $description,
        ]);

        return redirect()->route('admins.hr.leaveType.index')
            ->with('success', __('LeaveType Added Successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveType $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveType $banner)
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
            ->with('success', __('LeaveType Updated Successfully'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveType $banner)
    {
        if ($banner->image) {
            Controller::deleteFile($banner->image);
        }
        $banner->delete();

        return redirect()->route('admins.banners.index')
            ->with('success', __('LeaveType Deleted Successfully'));
    }
}
