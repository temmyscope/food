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
        $product = new Product();
        $product->name = "Burger";
        $product->save();

        $product->refresh();

        $product_ingredients1 = new ProductIngredient([
            'quantity' => 150, 'product_id' => $product->id,
            'ingredient_id' => (Ingredient::where('name', 'Beef')->first())?->id
        ]);
        $product_ingredients2 = new ProductIngredient([
            'quantity' => 30, 'product_id' => $product->id,
            'ingredient_id' => (Ingredient::where('name', 'Cheese')->first())?->id
        ]);
        $product_ingredients3 = new ProductIngredient([
            'quantity' => 20, 'product_id' => $product->id,
            'ingredient_id' => (Ingredient::where('name', 'Onion')->first())?->id
        ]);

        $product_ingredients1->save();
        $product_ingredients2->save();
        $product_ingredients3->save();
    }
}
