<?php

namespace PhabelTest\Target\Php80;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class MixedTypeTest extends TestCase
{
    public function test()
    {
        $this->assertEquals('test', (fn (): mixed => 'test')());
        $this->assertEquals(123, (fn (): mixed => 123)());
        $this->assertEquals(123.123, (fn (): mixed => 123.123)());
        $this->assertEquals(null, (fn (): mixed => null)());
    }
}
