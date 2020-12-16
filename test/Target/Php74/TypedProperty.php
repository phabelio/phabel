<?php

namespace PhabelTest\Target\Php74;

use Phabel\Context;
use PHPUnit\Framework\TestCase;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;

/**
 * Implement typed properties.
 */
class TypedProperty extends TestCase
{
    private int $test = 0;
}
