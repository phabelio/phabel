<?php

namespace PhabelTest\Target\Php72\Contravariance;

abstract class Abstr3 extends Abstr
{
    abstract public function testCanWiden1($s);
    abstract public function testCanRestrict1(): string;

    abstract public function testCanWiden2($s);
    abstract public function testCanRestrict2(): string;
}