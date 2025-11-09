<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to safely truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $roles = [
            'super-admin',
            'admin',
            'moderator',
        ];


        foreach ($roles as $roleName) {
            Role::create(['name' => $roleName]);
        }


        $permissions = [
            'admins',
            'iqamaTypes',
            'employees',
            'users',
            'companies',
            'stages',
            'paymentAccounts',
            'moderators',
            'leaves',
            'eos',
            'reports',
            'roles',
        ];


        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        $allPermissions = Permission::all();

        $superAdminRole = Role::where('name', 'super-admin')->first();
        $adminRole = Role::where('name', 'admin')->first();

        $superAdminRole->syncPermissions($allPermissions);
        $adminRole->syncPermissions($allPermissions);

        // Optional: assign limited permissions to moderator
        $moderatorRole = Role::where('name', 'moderator')->first();
        $moderatorRole->givePermissionTo(['reports', 'iqamaTypes', 'employees', 'stages']);

        $admin = User::where('name', 'Super Admin')->first();

        $admin->assignRole('super-admin');
    }
}
