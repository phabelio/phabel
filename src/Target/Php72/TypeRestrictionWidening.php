<?php

namespace Phabel\Target\Php72;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Target\Php72\TypeRestrictionWidening\Storage;
use PhpParser\Node\Stmt\ClassMethod;

class TypeRestrictionWidening extends Plugin
{
    public function enter(ClassMethod $method, Context $context): void
    {
        $storage = $context->getPersistent(Storage::class);
        $storage->add($method, $context);
    }
}
