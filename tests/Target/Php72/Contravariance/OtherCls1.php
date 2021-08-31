<?php

namespace PhabelTest\Target\Php72\Contravariance;

class OtherCls1 extends OtherAbstr
{
    public function __construct()
    {
    }

    public static function get(): self {
        return new self;
    }
    
    public function returnMe(): self {
        return new self;
    }
    
    public function returnMe2(): static {
        return new self;
    }
}