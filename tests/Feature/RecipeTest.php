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
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function test_update()
    {
        $recipe = Recipe::factory()
            ->hasAttached(
                Ingredient::factory()->count(3),
                ['quantity' => 10]
            )
            ->create();
        $response = $this->putJson($this::base_url . $recipe->id, [
            'name' => 'bonjour les modifs',
            'ingredients' => [[$recipe->ingredients[0]->id, 140]],
        ]);

        $recipe = Recipe::with('ingredients')->find($response->getData()->id);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('id', $recipe->id)
                    ->where('name', $recipe->name)
                    ->has(
                        'ingredients.0',
                        fn ($json) =>
                        $json->where('id', $recipe->ingredients[0]->id)
                            ->where('quantity', 140)
                            ->etc()
                    )
                    ->etc()
            );
    }
    public function test_update_400()
    {
        $recipe = Recipe::factory()
            ->hasAttached(
                Ingredient::factory()->count(3),
                ['quantity' => 10]
            )
            ->create();
        $response = $this->putJson($this::base_url . $recipe->id, [
            'name' => 'bonjour les modifs',
            'ingredients' => [[$recipe->ingredients[0]->id, 'dd1d40']],
        ]);


        $response->assertStatus(400);
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

        $recipe = Recipe::with('ingredients')->find($response->getData()->id);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('id', $recipe->id)
                    ->where('name', $recipe->name)
                    ->etc()
            );
    }
    public function test_store_error()
    {

        $ingredients = Ingredient::factory(3)->create();
        $name = $this->faker()->name();
        $response = $this->postJson($this::base_url, [
            'name' => $name,
            'ingredients' => [
                [$ingredients[0]->id, "dddd"],
                [$ingredients[1]->id, 1],
                [$ingredients[2]->id, 105],

            ],
        ]);
        $response->assertStatus(400);
    }
    public function test_destroy()
    {
        $recipe = Recipe::factory()
            ->hasAttached(
                Ingredient::factory()->count(3),
                ['quantity' => 10]
            )
            ->create();
        $response = $this->deleteJson($this::base_url . $recipe->id, []);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('deleted', true)
                    ->etc()
            );
    }
    public function test_destroy_error()
    {
        $recipe = Recipe::factory()
            ->hasAttached(
                Ingredient::factory()->count(3),
                ['quantity' => 10]
            )
            ->create();
        $response = $this->deleteJson($this::base_url . 'dddff', []);
        $response->assertStatus(404);
    }
}
