<?php

namespace App\Providers;

use App\Http\Resources\IngredientCollection;
use App\Http\Resources\IngredientResource;
use App\Http\Resources\RecipeResource;
use App\Models\Ingredient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        RecipeResource::withoutWrapping();
        IngredientResource::withoutWrapping();
        IngredientCollection::withoutWrapping();
    }
}
