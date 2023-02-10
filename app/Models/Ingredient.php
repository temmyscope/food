<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ingredient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'initial_qty',
        'needs_restock',
        'available_qty',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
    **/
    protected $casts = [
        'initial_qty' => 'float',
        'available_qty' => 'float',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ingredients');
    }
}
