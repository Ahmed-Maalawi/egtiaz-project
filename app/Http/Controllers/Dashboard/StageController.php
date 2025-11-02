<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Stage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\IqamaType;
use Illuminate\Support\Facades\DB;
use Throwable;

class StageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $types = IqamaType::latest()->select('id', 'name')->get();

        return view('admin.stages.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = IqamaType::latest()->select('id', 'name')->get();

        return view('admin.stages.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'type_id'                       => 'required|exists:iqama_types,id',
            'name_ar'                       => 'required|string',
            'name_en'                       => 'required|string',
            'description_ar'                => 'required|string',
            'description_en'                => 'required|string',
            'price'                         => 'nullable|numeric|min:0',
            'cost'                          => 'nullable|numeric|min:0',
            'estimated_days'                => 'nullable|numeric|integer|min:0',
            'image'                         => 'nullable|image|max:5120',
            'file'                          => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $max_order = Stage::where('iqama_type_id', $request->type_id)
            ->max('order');

        $order = ($max_order ?? 0) + 1;

        $image_path = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('stages', 'public');
        };
        $file_path = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_path = $file->store('stages', 'public');
        };

        Stage::create([
            'iqama_type_id'                 => $request->type_id,
            'name'                          => [
                'ar'                            => $request->name_ar,
                'en'                            => $request->name_en,
            ],
            'description'                   => [
                'ar'                            => $request->description_ar,
                'en'                            => $request->description_en,
            ],
            'order'                         => $order,
            'price'                         => $request->price,
            'cost'                         => $request->price ?? null,
            'estimated_time_in_days'        => $request->estimated_days,
            'image'                         => $image_path,
            'file'                          => $file_path,
        ]);

        return redirect()->route('admins.stages.index')
            ->with('success', __('Stage Added Successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Stage $stage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stage $stage)
    {
        $types = IqamaType::select('id', 'name')->get();

        return view('admin.stages.edit', compact('stage', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stage $stage)
    {
        $request->validate([
            'name_ar'                       => 'required|string',
            'name_en'                       => 'required|string',
            'description_ar'                => 'required|string',
            'description_en'                => 'required|string',
            'price'                         => 'nullable|numeric|min:0',
            'cost'                          => 'nullable|numeric|min:0',
            'estimated_days'                => 'nullable|numeric|integer|min:0',
            'image'                         => 'nullable|image|max:5120',
            'file'                          => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $image_path = $stage->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('stages', 'public');
            if ($stage->image) {
                Controller::deleteFile($stage->image);
            }
        };

        $file_path = $stage->file;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_path = $file->store('stages', 'public');
            if ($stage->file) {
                Controller::deleteFile($stage->file);
            }
        };

        $stage->update([
            'name'                          => [
                'ar'                            => $request->name_ar,
                'en'                            => $request->name_en,
            ],
            'description'                   => [
                'ar'                            => $request->description_ar,
                'en'                            => $request->description_en,
            ],
            'price'                         => $request->price,
            'cost'                          => $request->cost,
            'estimated_time_in_days'        => $request->estimated_days,
            'image'                         => $image_path,
            'file'                          => $file_path,
        ]);

        return redirect()->route('admins.stages.index')
            ->with('success', __('Stage Updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stage $stage)
    {
        $stages_to_update_orders = Stage::where('iqama_type_id', $stage->iqama_type_id)
            ->where('order', '>', $stage->order)->get();

        try {
            DB::beginTransaction();
            if ($stage->image) {
                Controller::deleteFile($stage->image);
            }
            if ($stage->file) {
                Controller::deleteFile($stage->file);
            }
            $stage->delete();

            Stage::where('iqama_type_id', $stage->iqama_type_id)
                ->where('order', '>', $stage->order)
                ->update([
                    'order' => DB::raw('`order` - 1')
                ]);


            DB::commit();

            return redirect()->route('admins.stages.index')
                ->with('success', __('Stage Deleted Successfully'));
        } catch (Throwable $th) {
            DB::rollBack();

            throw $th->getMessage();
        }
    }

    public function getMaxOrder(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:iqama_types,id',
        ]);

        $max_order = Stage::where('iqama_type_id', $request->id)
            ->max('order');

        return ($max_order ?? 0) + 1;
    }
}
