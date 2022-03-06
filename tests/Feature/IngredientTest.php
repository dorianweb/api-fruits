<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use function PHPUnit\Framework\assertSame;

class IngredientTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    protected const base_url = '/api/ingredients/';
    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->json('GET', $this::base_url);
        $response->assertStatus(200);
    }
    public function test_store()
    {

        $ingredients = Ingredient::factory()->make();
        $name = $this->faker()->name();
        $response = $this->postJson($this::base_url, [
            'name' => $ingredients->name,
            'icon' => $ingredients->icon,
            'unit' => $ingredients->unit,
            'external_id' => $ingredients->external_id,
        ]);

        $ingredient = Ingredient::find($response->getData()->id);
        $response->assertStatus(201)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('id', $ingredient->id)
                    ->where('name', $ingredient->name)
                    ->where('icon', $ingredient->icon)
                    ->where('unit', $ingredient->unit)
                    ->where('external_id', $ingredient->external_id)
                    ->etc()
            );
    }
    public function test_store_error()
    {

        $ingredients = Ingredient::factory()->make();
        $name = $this->faker()->name();
        $response = $this->postJson($this::base_url, [
            'name' => 454,
            'external_id' => 'xxxxcc',
        ]);
        $response->assertStatus(422);
    }
}
