<?php

namespace PhabelTest\Target\Php72\Contravariance;

class OtherCls2 extends OtherCls1
{
    protected function __construct()
    {
    }
    
    public static function get(): self {
        return new self;
    }
}