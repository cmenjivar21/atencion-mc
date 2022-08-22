<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categorie::insert([
            [
                "id" => 1,
                "name_cat" => "Atención al Cliente",
                
            ],
            [
                "id" => 2,
                "name_cat" => "Perfil de RENTCA",
                
            ],
            [
                "id" => 3,
                "name_cat" => "Usuario / Contraseña",
                
            ],
        ]);
    }
}
