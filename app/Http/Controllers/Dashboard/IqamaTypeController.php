<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\IqamaType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IqamaTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = IqamaType::get();

        return view('admin.types.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.types.create');
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
        ]);

        IqamaType::create([
            'name'                      => [
                'ar'                        => $request->name_ar,
                'en'                        => $request->name_en,
            ],
            'description'               => [
                'ar'                        => $request->description_ar,
                'en'                        => $request->description_en,
            ],
        ]);

        return redirect()->route('admins.types.index')
            ->with('success', __('Iqama Type Added'));
    }

    /**
     * Display the specified resource.
     */
    public function show(IqamaType $iqamaType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IqamaType $iqamaType)
    {
        return view('admin.types.edit', [
            'type'                  => $iqamaType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IqamaType $iqamaType)
    {
        $request->validate([
            'name_ar'                   => 'required|string',
            'name_en'                   => 'required|string',
            'description_ar'            => 'required|string',
            'description_en'            => 'required|string',
        ]);

        $iqamaType->update([
            'name'                      => [
                'ar'                        => $request->name_ar,
                'en'                        => $request->name_en,
            ],
            'description'               => [
                'ar'                        => $request->description_ar,
                'en'                        => $request->description_en,
            ],
        ]);

        return redirect()->route('admins.types.index')
            ->with('success', __('Iqama Type Updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IqamaType $iqamaType)
    {
        //TODO: check if users already on this type before deletion

        $iqamaType->delete();

        return redirect()->route('admins.types.index')
            ->with('success', __('Iqama Type Deleted'));
    }
}
