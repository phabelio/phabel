<?php

namespace PhabelTest\Target\Php72\Contravariance;

abstract class Abstr2 extends Abstr
{
    abstract public function testCanWiden1(?string $s);
    abstract public function testCanRestrict1(): ?string;

    abstract public function testCanWiden2(string|int|float $s);
    abstract public function testCanRestrict2(): string|int;
}