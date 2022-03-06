<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\IngredientResource;
use Illuminate\Validation\ValidationException;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return   IngredientResource::collection(Ingredient::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'icon' => 'required|string',
            'unit' => 'required|string',
            'external_id' => 'required|int',
        ]);
        $ingredient = Ingredient::firstOrNew([
            'name' => $request->name,
            'external_id' => $request->external_id,
        ]);
        $ingredient->name = $request->name;
        $ingredient->icon = $request->icon;
        $ingredient->unit = $request->unit;
        $ingredient->external_id = $request->external_id;
        if ($ingredient->save()) {
            return new IngredientResource($ingredient);
        }
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\Ingredient  $ingredient
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Ingredient $ingredient)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\Ingredient  $ingredient
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, Ingredient $ingredient)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Models\Ingredient  $ingredient
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Ingredient $ingredient)
    // {
    //     //
    // }
}
