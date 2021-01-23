<?php

namespace PhabelTest\Target\Php72;

trait Trait1
{
    public function testCanWiden1($s)
    {
        return $s;
    }

    public function testCanRestrict1(): string
    {
        return 'test';
    }
}