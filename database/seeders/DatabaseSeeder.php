<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $ing = array();
        collect([
            [
                'name' => 'salade',
                'icon' => 'xxxx.png',
                'unit' => 'g',
                'external_id' => 125,
            ],
            [
                'name' => 'tomate',
                'icon' => 'yyy.png',
                'unit' => 'g',
                'external_id' => 158,
            ],
            [
                'name' => 'concombre',
                'icon' => 'zzzz.png',
                'unit' => 'g',
                'external_id' => 15,
            ],
            [
                'name' => 'kebab',
                'icon' => '1111.png',
                'unit' => 'g',
                'external_id' => 189,
            ],

            [
                'name' => 'lait',
                'icon' => 'gggg.png',
                'unit' => 'cl',
                'external_id' => 1025,
            ],

        ])->each(function ($ingredient, $index) {
            $id = DB::table('ingredients')->insertGetId([
                'name' => $ingredient["name"],
                'icon' => $ingredient["icon"],
                'unit' => $ingredient["unit"],
                'external_id' => $ingredient['external_id'],
                'created_at' => Carbon::now(),
            ]);
        });

        $ingredientsLimit = count($ing) - 1;
        collect([
            [
                'name' => 'salade composer'
            ],
            [
                'name' => 'Kebab'
            ],
            [
                'name' => 'lait tue'
            ],
        ])->each(function ($recipe) {
            $recipe = DB::table('recipes')->insertGetId([
                'name' => $recipe["name"],
                'created_at' => Carbon::now(),
            ]);
            $usedingredient = [];
            for ($i = 0; $i <= 2; $i++) {
                $ingredient = rand(1, 5);
                if (!in_array($ingredient, $usedingredient)) {
                    $pivotLine = DB::table('ingredient_recipe')->insertGetId([
                        'ingredient_id' => $ingredient,
                        'recipe_id' => $recipe,
                        'quantity' => rand(1, 4),
                        'created_at' => Carbon::now(),
                    ]);
                    $usedingredient[] = $ingredient;
                }
            }
        });
    }
}
