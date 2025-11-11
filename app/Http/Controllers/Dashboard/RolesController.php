<?php

namespace App\Http\Controllers\Dashboard;


use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display all roles and users with roles
     */
    public function index()
    {
        $roles = Role::all();
        $users = User::with('roles')->get();

        return view('admin.Roles.index', compact('roles', 'users'));
    }

    /**
     * Store a new role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        Role::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Role created successfully.');
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($request->user_id);
        $role = Role::findOrFail($request->role_id);

        $user->syncRoles([$role->name]); // replaces old roles
        return redirect()->back()->with('success', 'Role assigned successfully.');
    }

    /**
     * Remove role from user
     */
    public function removeRole($userId, $roleId)
    {
        $user = User::findOrFail($userId);
        $role = Role::findOrFail($roleId);

        $user->removeRole($role->name);

        return redirect()->back()->with('success', 'Role removed successfully.');
    }

    /**
     * Delete a role
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->back()->with('success', 'Role deleted successfully.');
    }

    public function permissionsIndex()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        // Group permissions by their 'group' field for better organization
        $groupedPermissions = $permissions->groupBy('group');

        return view('admin.Roles.permissions', compact('roles', 'groupedPermissions', 'permissions'));
    }

    public function updatePermissions(Request $request)
    {
        // Check if it's an AJAX request
        if (!$request->ajax() && !$request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'This route only accepts AJAX requests'
            ], 400);
        }

        try {
            $request->validate([
                'role_id' => 'required|exists:roles,id',
                'permissions' => 'sometimes|array',
                'permissions.*' => 'exists:permissions,id',
            ]);

            $role = Role::findOrFail($request->role_id);

            // Get permission objects
            $permissions = Permission::whereIn('id', $request->permissions ?? [])->get();

            // Sync permissions
            $role->syncPermissions($permissions);

            return response()->json([
                'success' => true,
                'message' => 'Permissions updated successfully for ' . $role->name,
                'role' => $role->name,
                'permissions_count' => $permissions->count()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Permission update error:', [
                'error' => $e->getMessage(),
                'role_id' => $request->role_id,
                'permissions' => $request->permissions
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating permissions: ' . $e->getMessage()
            ], 500);
        }
    }
}
