<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use function App\Helpers\is50PercentOrLess;

class HelperTest extends TestCase
{
    /**
     * A test for the `is50PercentOrLess` helper function
     *
     * @return void
     */
    public function test_that_is50PercentOrLess_works()
    {
        $this->assertTrue( is50PercentOrLess(50, 25) );
        $this->assertFalse( is50PercentOrLess(50, 27) );
        $this->assertFalse( is50PercentOrLess(50.00, 25.0015) );
    }
}
