<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class OrderTest extends TestCase
{

    use RefreshDatabase;

    public function test_order_is_valid()
    {
        //this order should fail because its structure would fail validation
        $response = $this->postJson('/api/order', [ "products" => [] ]);
        $response->assertStatus(422);

        //this order should fail because it orders above the available ingredient stock
        $response = $this->postJson('/api/order', [
            "products" => [
                [ "product_id" => 1, "quantity" => 3000 ]
            ]
        ]);
        $response->assertStatus(422);

        //this order should be successful because it's valid in structure and product quantity
        $response = $this->postJson('/api/order', [
            "products" => [
                [ "product_id" => 1, "quantity" => 1 ]
            ]
        ]);
        $response->assertStatus(201)->assertJson(["status" => true]);
    }

    /**
     * test if the order succeeds and persists in db
     *
     * @return void
    **/
    public function test_order_success_and_persistence()
    {
        //order should be empty before first order is sent
        $this->assertDatabaseEmpty(Order::class);

        $response = $this->postJson('/api/order', [
            "products" => [
                [ "product_id" => 1, "quantity" => 1 ]
            ]
        ]);

        //order should contain 1 item after first order is sent
        $this->assertDatabaseCount(Order::class, 1);

        $response = $this->postJson('/api/order', [
            "products" => [
                [ "product_id" => 1, "quantity" => 2 ]
            ]
        ]);
        
        //order should contain 2 items after second order is sent
        $this->assertDatabaseCount(Order::class, 2);
    
        $response->assertStatus(201);
    }

}
