<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    /**
     * A test for the `is50PercentOrLess` helper function
     *
     * @return void
     */
    public function test_that_is50PercentOrLess_works()
    {
        $this->assertTrue( isLessOrEqualToThreshold(50, 25, 50) );
        $this->assertFalse( isLessOrEqualToThreshold(50, 27, 50) );
        $this->assertFalse( isLessOrEqualToThreshold(50.00, 25.0015, 50) );
    }
}
