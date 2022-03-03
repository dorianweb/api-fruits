<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        "name",
        "icon",
        "unit",
        "external_id"
    ];
    use HasFactory;
    function recipes()
    {
        return $this->belongsToMany(Recipe::class)->withPivot('quantity');
    }
}
