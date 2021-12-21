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
        $this->assertEquals(0, $this->nullish(0));
        $this->assertEquals(0, $this->nullish2(0));
        $this->assertEquals(null, $this->nullish(null));
        $this->assertEquals(0, $this->nullish2(0));
        $this->assertEquals(null, $this->nullish2(null));
        $this->assertEquals(null, $this->union(1.0, null));
        $this->assertEquals(123, $this->union(1.0, 123));
        $this->assertEquals($this, $this->union(1.0, $this));
        $this->assertEquals($this, $this->union($this, null));
    }

    public function variadic(int ...$test): int
    {
        return $test[0];
    }
    public function nullish(int $test = null): ?int {
        return $test;
    }
    public function nullish2(int $test = \null): ?int {
        return $test;
    }
    public function union(float|object $var, int|object|null ...$test): int|object|null
    {
        return is_object($var) ? $var : $test[0];
    }
    public function voidPony(): void
    {
    }

    public function generatorPony(): \Generator
    {
    }
}
