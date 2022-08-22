<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
            MunicipalitiesSeeder::class,
            CategoriesSeeder::class,
            SubCategoriesSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
