<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IngredientStockTest extends TestCase
{
    use RefreshDatabase;

    /**
    * test if the stock of ingredients updates correctly in the db
    *
    * @return void
    **/
    public function test_stock_updates_correctly()
    {
        /**
         * Pre-order tests for stock update
        **/
        //product table should contain `Beef` before test runs
        $this->assertDatabaseHas(Product::class, [
            "name" => "Burger"
        ]);

        //ingredient table should contain appropriate ingredient and quantity(s)
        $this->assertDatabaseHas(Ingredient::class, [
            "name" => "Beef", "initial_qty" => 20000, "available_qty" => 20000
        ]);
        $this->assertDatabaseHas(Ingredient::class, [
            "name" => "Cheese", "initial_qty" => 5000, "available_qty" => 5000
        ]);
        $this->assertDatabaseHas(Ingredient::class, [
            "name" => "Onion", "initial_qty" => 1000, "available_qty" => 1000
        ]);

        //Order Operation
        $response = $this->postJson('/api/order', [
            "products" => [
                [ "product_id" => 1, "quantity" => 2 ]
            ]
        ]);

        /**
         * Post-order tests for stock update
        **/
        //since 1 burger uses 150g of Beef, then 2 would use up (150*2)g
        $this->assertDatabaseHas(Ingredient::class, [
            "name" => "Beef", "initial_qty" => 20000, "available_qty" => (20000-300)
        ]);
        //since 1 burger uses 30g of Cheese,, then 2 would use up (30*2)g 
        $this->assertDatabaseHas(Ingredient::class, [
            "name" => "Cheese", "initial_qty" => 5000, "available_qty" => (5000-60)
        ]);
        //since 1 burger uses 20g of Onion, then 2 would use up (20*2)g
        $this->assertDatabaseHas(Ingredient::class, [
            "name" => "Onion", "initial_qty" => 1000, "available_qty" => (1000-40)
        ]);
        
        //assert that the test was successful
        $response->assertStatus(201)->assertJson(["status" => true]);
    }

}
