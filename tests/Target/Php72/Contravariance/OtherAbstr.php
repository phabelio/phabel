<?php

namespace PhabelTest\Target\Php72\Contravariance;

abstract class OtherAbstr
{
    abstract public function returnMe(): self;
    abstract public function returnMe2(): static;
}