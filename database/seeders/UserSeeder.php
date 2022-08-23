<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Hash;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleAdmin = Role::findOrFail(1);
        $roleUser = Role::findOrFail(2);

        $admin = User::create([
            'id' => 1,
            'name' => 'Leonel',
            'last_name' => 'Lopez',
            'dui' => '12345678-1',
            'email' => 'leonellopez647@gmail.com',
            'password' => Hash::make('Leonel23'),
            'name' => 'Leonel',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($roleUser);

        $user = User::create([
            'id' => 2,
            'name' => 'Claudia',
            'last_name' => 'Menjivar',
            'dui' => '00850195-3',
            'email' => 'aplicaciones.micultura@gmail.com',
            'password' => Hash::make('Admin123'),
            'name' => 'Claudia',
            'email_verified_at' => now(),
        ]);
        $user->assignRole($roleAdmin);

        $user = User::create([
            'id' => 3,
            'name' => 'Oscar',
            'last_name' => 'Ceballos',
            'dui' => '02475605-7',
            'email' => 'oceballos@cultura.gob.sv',
            'password' => Hash::make('12345678'),
            'name' => 'Oscar',
            'email_verified_at' => now(),
        ]);
        $user->assignRole($roleAdmin);

        $permissions = Permission::all();

        // Creating the admin role
        foreach ($permissions as $permission) {
            $roleAdmin->givePermissionTo($permission);
        }

        $userPermissions = [
            'tickets',
            'create-tickets',
            'update-tickets',
            'municipalities',
            'sub_categorie',
        ];

        foreach ($userPermissions as $permission) {
            $roleUser->givePermissionTo($permission);
        }
    }
}
