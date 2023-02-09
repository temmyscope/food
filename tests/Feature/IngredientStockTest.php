<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

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
    }

}
