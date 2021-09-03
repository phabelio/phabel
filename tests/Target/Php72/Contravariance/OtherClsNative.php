<?php

namespace PhabelTest\Target\Php72\Contravariance;

use ArrayIterator;

class OtherClsNative extends ArrayIterator
{
    /*protected function __construct()
    {
        parent::__construct();
    }*/
    
    public static function get(): self {
        return new self;
    }
}