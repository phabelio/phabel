<?php

namespace PhabelTest\Target\Php80;

use Exception;
use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class CatchEmptyTest extends TestCase
{
    public function test()
    {
        try {
            throw new Exception('test');
        } catch (\Throwable) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }
}
