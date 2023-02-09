<?php

namespace Tests\Feature;


use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test if the order persists in db
     *
     * @return void
     */
    public function test_order_persistence()
    {
    }

}
