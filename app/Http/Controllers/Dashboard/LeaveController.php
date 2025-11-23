<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\OfficialLeave;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaves = OfficialLeave::with('user')->latest()->get();
        return view('admin.hr.leaves.index', ['leaves' => $leaves]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('status', 'active')->whereNot('id', Auth::user()->id)->withoutRole('super-admin')->get();
        $leaveTypes = ['annual', 'sick', 'maternity', 'paternity', 'unpaid', 'other'];
        return view('admin.hr.leaves.create', ['users' => $users, 'leaveTypes' => $leaveTypes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id'   => 'required|integer|exists:users,id',
                'start_date'    => 'required|date',
                'end_date'      => 'required|date|gte:start_date',
                'type'          => 'required|in:annual,sick,maternity,paternity,unpaid,other',
                'reason'        => 'nullable|string',
                'notes'         => 'nullable|string',
            ]);

            $startDate = Carbon::parse($validatedData['start_date']);
            $endDate   = Carbon::parse($validatedData['end_date']);

            $days = $startDate->diffInDays($endDate) + 1;

            $leave = OfficialLeave::create([
                'user_id'       => $validatedData['user_id'],
                'start_date'    => $validatedData['start_date'],
                'end_date'      => $validatedData['end_date'],
                'type'          => $validatedData['type'],
                'reason'        => $validatedData['reason'],
                'notes'         => $validatedData['notes'],
                'days_taken'    => $days,
            ]);

            return redirect()->route('admins.hr.leaves.index')
                ->with('success', __('leave Added Successfully'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $leave = OfficialLeave::with('user')->findOrFail($id);
        return view('admin.hr.leaves.show', ['leave' => $leave]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $leave = OfficialLeave::findOrFail($id);

            $users = User::where('status', 'active')->get();

            $leaveTypes = ['annual', 'sick', 'maternity', 'paternity', 'unpaid', 'other'];

            return view('admin.hr.leaves.edit', [
                'leaveTypes' => $leaveTypes,
                'users' => $users,
                'leave' => $leave
            ]);
        } catch (Exception $exception) {
            return redirect()->route('admins.hr.leaves.index');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $validatedData = $request->validate([
                'user_id'   => 'required|integer|exists:users,id',
                'start_date'    => 'required|date',
                'end_date'      => 'required|date|gte:start_date',
                'type'          => 'required|in:annual,sick,maternity,paternity,unpaid,other',
                'reason'        => 'nullable|string',
                'notes'         => 'nullable|string',
                'status'        => 'required|in:pending,approved,rejected',
            ]);

            $leave = OfficialLeave::findOrFail($id);

            $startDate = Carbon::parse($validatedData['start_date']);
            $endDate   = Carbon::parse($validatedData['end_date']);

            $days = $startDate->diffInDays($endDate) + 1;

            $leave->update([
                'user_id'   => $validatedData['user_id'],
                'start_date'    => $validatedData['start_date'],
                'end_date'      => $validatedData['end_date'],
                'type'          => $validatedData['type'],
                'reason'        => $validatedData['reason'],
                'notes'         => $validatedData['notes'],
                'status'        => $validatedData['status'],
                'days_taken'    => $days,
            ]);

            return redirect()->route('admins.hr.leaves.index')
                ->with('success', __('leave Updated Successfully'));
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $leave = OfficialLeave::findOrFail($id);
            $leave->delete();

            return redirect()->route('admins.hr.leaves.index')
                ->with('success', __('Leave Deleted Successfully'));
        } catch (Exception $exception) {
            return redirect()->route('admins.hr.leaves.index');
        }
    }
}
