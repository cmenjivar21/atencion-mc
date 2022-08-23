<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert([
            // Departments
            ['name' => 'departments', 'guard_name' => 'web'],
            ['name' => 'municipalities', 'guard_name' => 'web'],
            ['name' => 'users', 'guard_name' => 'web'],
            ['name' => 'permissions', 'guard_name' => 'web'],
            ['name' => 'roles', 'guard_name' => 'web'],
            ['name' => 'categories', 'guard_name' => 'web'],
            ['name' => 'sub_categorie', 'guard_name' => 'web'],
            ['name' => 'tickets', 'guard_name' => 'web'],
            ['name' => 'create-tickets', 'guard_name' => 'web'],
            ['name' => 'update-tickets', 'guard_name' => 'web'],
            ['name' => 'delete-tickets', 'guard_name' => 'web'],

        ]);
    }
}
