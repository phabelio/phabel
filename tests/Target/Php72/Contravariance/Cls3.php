<?php

namespace PhabelTest\Target\Php72\Contravariance;

class Cls3 extends Abstr3
{
    public function testCanWiden1($s) {
        return 'testCanWiden1';
    }
    public function testCanRestrict1(): string {
        return 'testCanRestrict1';
    }

    public function testCanWiden2($s) {
        return 'testCanWiden2';
    }
    public function testCanRestrict2(): string {
        return 'testCanRestrict2';
    }
}