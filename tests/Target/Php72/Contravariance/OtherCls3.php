<?php

namespace PhabelTest\Target\Php72\Contravariance;

class OtherCls3 extends OtherCls2
{
    private function __construct()
    {
    }

    public static function get(): self {
        return new self;
    }
}