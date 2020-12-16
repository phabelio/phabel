<?php

namespace PhabelTest\Target\Php70;

use PHPUnit\Framework\TestCase;

if (!\defined('PHABEL_ARRAY_TEST')) {
    \define('PHABEL_ARRAY_TEST', ['uwu']);
}

/**
 * Converts define() arrays into const arrays.
 */
class DefineArrayReplacerTest extends TestCase
{
    public function test()
    {
        $this->assertEquals('uwu', PHABEL_ARRAY_TEST[0]);
    }
}
