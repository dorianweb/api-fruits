<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class IngredientCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'quantity' => $this->whenPivotLoaded('ingredient_recipe', function () {
                return $this->pivot->quantity;
            }),
            'id' => $this->whenPivotLoaded('ingredient_recipe', function () {
                return $this->pivot->ingredident_id;
            }),
        ];
    }
}
