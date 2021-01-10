<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class GeneratorTypeHintReplacerTest extends TestCase
{
    public function test()
    {
        $gen = function (): \Generator {
            yield 1;
            yield 2;
            yield 3;
        };
        foreach ($gen() as $i => $num) {
            $this->assertEquals($i+1, $num);
        }
        $gen = fn (): \Generator => yield 1;
        foreach ($gen() as $i => $num) {
            $this->assertEquals($i+1, $num);
        }
    }
}
