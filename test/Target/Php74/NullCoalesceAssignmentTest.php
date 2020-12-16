<?php

namespace PhabelTest\Target\Php74;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class NullCoalesceAssignmentTest extends TestCase
{
    public function test()
    {
        $b = null;
        $c = ['a' => []];

        $a ??= 'lmao';
        $b ??= 'lmao';
        $c['a']['b'] ??= 'lmao';
        $this->assertEquals('lmao', $a);
        $this->assertEquals('lmao', $b);
        $this->assertEquals('lmao', $c['a']['b']);
    }
}
