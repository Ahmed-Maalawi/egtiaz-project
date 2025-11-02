<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Models\Permission as ModelsPermission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesSeeder::class);

        $user = User::create([
            'name'                      =>'Super Admin',
            'email'                     =>'admin@egtiaz.com',
            'password'                  =>Hash::make('9449'),
        ]);

        $user->assignRole('super-admin');

        // $this->call(PermissionsSeeder::class);
    }
}
