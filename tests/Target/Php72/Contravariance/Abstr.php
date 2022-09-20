<?php

namespace PhabelTest\Target\Php72\Contravariance;

abstract class Abstr implements IFace, IFace2
{
    abstract public function testCanWiden1(?string $s);
    abstract public function testCanRestrict1(): ?string;

    abstract public function testCanWiden2(string|int $s);
    abstract public function testCanRestrict2(): string|int|null;
}