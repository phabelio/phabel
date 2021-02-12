<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintTest extends TestCase
{
    public function test()
    {
        $this->assertEquals(0, $this->variadic(0));
    }

    public function variadic(int ...$test): int
    {
        return $test[0];
    }
    public function voidPony(): void
    {
    }

    public function generatorPony(): \Generator
    {
    }
}
