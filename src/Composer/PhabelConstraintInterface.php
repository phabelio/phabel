<?php

namespace Phabel\Composer;

use Composer\Semver\Constraint\ConstraintInterface;

interface PhabelConstraintInterface extends ConstraintInterface
{
    public function getConfig(): array;
    public function setConfig(array $config);
}
