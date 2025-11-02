<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = [
            'categories',
            'providers',
            'users',
            'cities',
            'banners',
            'cashiers',
            'cards',
            'orders',
            'services',
            'notifications',
            'provider.moderator'
        ];

        foreach ($array as $permission){
            Permission::create(['name'=>$permission]);
        }
    }
}
