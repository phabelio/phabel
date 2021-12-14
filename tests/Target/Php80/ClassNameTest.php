<?php

namespace PhabelTest\Target\Php80;

use Exception;
use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ClassNameTest extends TestCase
{
    public function test()
    {
        $this->assertEquals(self::class, $this::class);
    }
}
