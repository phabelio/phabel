<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class StringConcatOptimizerTest extends TestCase
{
    public function test()
    {
        $this->assertEquals('abcd', 'a'.'b'.'c'.'d');
        $this->assertEquals('abcd', 'a'.(('b'.'c').'d'));
        $this->assertEquals('abcd', 'a'.(('b'.(fn () => 'c')()).'d'));
    }
}
