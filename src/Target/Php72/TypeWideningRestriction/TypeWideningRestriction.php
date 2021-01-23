<?php

namespace Phabel\Target\Php72\TypeWideningRestriction;

use Phabel\ClassStorageProvider;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeWideningRestriction extends ClassStorageProvider
{
    public function enter(ClassMethod $method): void
    {
        $storage = $this->getClassStorage();
    }
}
