<?php

namespace PhabelTest\Target\Php80;

use PHPUnit\Framework\TestCase;

/**
 * Implement typed properties.
 */
class TypedPropertyTest extends TestCase
{
    private int|string $test = 0;
    public function test()
    {
        $this->assertEquals(0, $this->test);
        $this->test = "uwu";
        $this->assertEquals("uwu", $this->test);
    }
}
