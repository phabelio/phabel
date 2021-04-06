<?php

namespace PhabelTest\Target\Php72\Contravariance;

interface IFace2
{
    public function testCanWiden1(string $s);
    public function testCanRestrict1();

    public function testCanWiden2(string $s);
    public function testCanRestrict2();
}