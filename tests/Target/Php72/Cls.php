<?php

namespace PhabelTest\Target\Php72;

abstract class Cls extends Abstr
{
    public function testCanWiden1($s)
    {
        return $s;
    }

    public function testCanRestrict1(): string
    {
        return 'test';
    }


    public function testCanWiden2(string|int|float|object $s)
    {
        return $s;
    }
    public function testCanRestrict2(): int
    {
        return 0;
    }
}