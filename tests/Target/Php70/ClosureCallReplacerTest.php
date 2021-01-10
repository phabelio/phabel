<?php

namespace PhabelTest\Target\Php70;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ClosureCallReplacerTest extends TestCase
{
    public function test()
    {
        $this->assertEquals('uwu', (fn (string $d): string => $d)('uwu'));
    }
}
