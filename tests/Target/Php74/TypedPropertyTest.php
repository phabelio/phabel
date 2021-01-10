<?php

namespace PhabelTest\Target\Php74;

use PHPUnit\Framework\TestCase;

/**
 * Implement typed properties.
 */
class TypedPropertyTest extends TestCase
{
    private int $test = 0;
    public function test()
    {
        $this->assertEquals(0, $this->test);
    }
}
