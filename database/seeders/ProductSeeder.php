<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductIngredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $product = new Product();
        $product->name = "Burger";
        $product->save();

        $product->ingredients()->saveMany([
            new ProductIngredient([
                'quantity' => 150,
                'ingredient_id' => (Ingredient::where('name', 'Beef')->first())?->id
            ]),
            new ProductIngredient([
                'quantity' => 30,
                'ingredient_id' => (Ingredient::where('name', 'Cheese')->first())?->id
            ]),
            new ProductIngredient([
                'quantity' => 20,
                'ingredient_id' => (Ingredient::where('name', 'Onion')->first())?->id
            ]),
        ]);
    }
}
