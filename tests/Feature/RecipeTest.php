<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    protected const base_url = '/api/recipes/';
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->get($this::base_url);
        $response->assertStatus(200);
    }
    public function test_show()
    {
        $recipe = Recipe::factory()
            ->hasAttached(
                Ingredient::factory()->count(3),
                ['quantity' => 10]
            )
            ->create();

        $response = $this->get($this::base_url . $recipe->id);
        $response->assertStatus(200);
        //  dd($recipe->ingredients()->quantity);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('id', $recipe->id)
                    ->where('name', $recipe->name)
                    ->has(
                        'ingredients.0',
                        fn ($json) =>
                        $json->where('id', $recipe->ingredients[0]->id)
                            ->where('quantity', 10)
                            ->missing('password')
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function test_store()
    {

        $ingredients = Ingredient::factory(3)->create();
        $name = $this->faker()->name();
        $response = $this->postJson($this::base_url, [
            'name' => $name,
            'ingredients' => [
                [$ingredients[0]->id, 10],
                [$ingredients[1]->id, 1],
                [$ingredients[2]->id, 105],
            ],
        ]);
        $response->assertStatus(201);
        $recipe = Recipe::with('ingredients')->first([
            'name' => $name,
        ]);

        // $response
        //     ->assertJson(
        //         fn (AssertableJson $json) =>
        //         $json->where('id', $recipe->id)
        //             ->where('name', $recipe->name)
        //             ->has(
        //                 'ingredients.0',
        //                 fn ($json) =>
        //                 $json->where('id', $recipe->ingredients[0]->id)
        //                     ->where('quantity', 10)
        //                     ->missing('password')
        //                     ->etc()
        //             )
        //             ->etc()
        //     );
    }
}
