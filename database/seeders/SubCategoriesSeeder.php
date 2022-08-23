<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubCategorie;

class SubCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubCategorie::insert([
            [
                "id" => 1,
                "name_sub_cat" => "Preguntas Generales",
                "categorie_id" => 1,
            ],
            [
                "id" => 2,
                "name_sub_cat" => "¿Cómo funciona?",
                "categorie_id" => 1,
            ],
            [
                "id" => 3,
                "name_sub_cat" => "Creación de Perfil",
                "categorie_id" => 2,
            ],
            [
                "id" => 4,
                "name_sub_cat" => "Perfil Existente",
                "categorie_id" => 2,
            ],
            [
                "id" => 5,
                "name_sub_cat" => "Olvido de Usuario",
                "categorie_id" => 3,
            ],
            [
                "id" => 6,
                "name_sub_cat" => "Olvido de Contraseña",
                "categorie_id" => 3,
            ],
            [
                "id" => 7,
                "name_sub_cat" => "Apoyo creación LoginSV",
                "categorie_id" => 3,
            ],
        ]);
    }
}
