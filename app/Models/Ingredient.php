<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
    **/
    protected $casts = [
        'initial_qty' => 'float',
        'needs_restock' => 'bool',
        'available_qty' => 'float',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ingredients');
    }
}
