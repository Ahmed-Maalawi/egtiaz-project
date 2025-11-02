<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::withCount(['reviews','serviceProviders','refers'])
        ->latest()->whereDoesntHave('roles')->get();
        return view('admin.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if($user->image){
            Controller::deleteFile($user->image);
        }
        $user->delete();
        return redirect()->route('admins.users.index');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        $user->status = $user->status =='active' ? 'inactive' : 'active';
        $user->save();

        return response()->json([
            'message'                   =>'updated successfully',
        ]);
    }

    public function export()
    {
        return Excel::download(new UserExport(), 'users.xlsx');
    }
}
