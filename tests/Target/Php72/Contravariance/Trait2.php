<?php

namespace PhabelTest\Target\Php72\Contravariance;

trait Trait2
{
    public function testCanWiden2(string|int|float|object $s)
    {
        return $s;
    }
    public function testCanRestrict2(): int
    {
        return 0;
    }
}