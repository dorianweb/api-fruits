<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PartialIngredient;
use App\Http\Resources\RecipeResource;
use App\Http\Resources\PartialRecipe;
use Exception;
use Illuminate\Validation\ValidationException;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return   PartialRecipe::collection(Recipe::with('ingredients')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => '',
                'ingredient' => 'array',
                'ingredients.*' => 'array:0,1|size:2',
                'ingredients.*.0' => 'int',
                'ingredients.*.1' => 'int'
            ]);
            $recipe = Recipe::firstOrNew(['name' => $request->name]);
            $recipe->name = $request->name;
            if ($recipe->save()) {
                foreach ($request->ingredients as $ingredient) {
                    $recipe->ingredients()->attach($ingredient[0], ['quantity' => $ingredient[1]]);
                }
                return new PartialRecipe($recipe);
            }
        } catch (Exception $e) {
            return response(['error' => $e], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
        return new PartialRecipe($recipe->load('ingredients'));
    }

    /**
     * 
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe)
    {
        try {

            $request->validate([
                'name' => '',
                'ingredient' => 'array',
                'ingredients.*' => 'array:0,1|size:2',
                'ingredients.*.0' => 'int',
                'ingredients.*.1' => 'int'
            ]);
            $recipe = $recipe->load('ingredients');
            $recipe->name = $request->name;
            $recipe->ingredients()->detach();

            if ($recipe->save()) {
                foreach ($request->ingredients as $ingredient) {
                    $recipe->ingredients()->attach($ingredient[0], ['quantity' => $ingredient[1]]);
                }
                return new PartialRecipe(Recipe::with('ingredients')->find($recipe->id));
            }
        } catch (Exception $e) {
            return response(['error' => $e], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe)
    {
        $recipe = $recipe->load('ingredients');
        $recipe->ingredients()->detach();
        if ($recipe->delete()) {
            return ["deleted" => true];
        }
    }
}
