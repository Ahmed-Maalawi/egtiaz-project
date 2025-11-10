<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Exports\UserExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::with(['employeeStages', 'paymentAccounts', 'salaries', 'eos', 'leaves'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully',
            'data' => $users,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($user->image) {
            self::deleteFile($user->image);
        }

        $user->delete();


        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ], 200);
    }

    /**
     * Toggle user active/inactive status.
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status == 'active' ? 'inactive' : 'active';
        $user->save();


        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => [
                'user_id' => $user->id,
                'new_status' => $user->status,
            ],
        ], 200);
    }

    /**
     * Export users.
     */
//    public function export(Request $request)
//    {
//
//        $users = User::latest()->get();
//
//        return response()->json([
//            'success' => true,
//            'message' => 'Export data ready',
//            'data' => $users,
//        ], 200);
//
//
//        return Excel::download(new UserExport(), 'users.xlsx');
//    }

    /**
     * Delete stored file (helper)
     */
    public static function deleteFile($path)
    {
        if (\Storage::disk('public')->exists($path)) {
            \Storage::disk('public')->delete($path);
        }
    }
}
