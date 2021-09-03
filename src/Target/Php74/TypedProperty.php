<?php

namespace Phabel\Target\Php74;

use Phabel\Plugin;
use Phabel\PhpParser\Node\Stmt\ClassLike;
use Phabel\PhpParser\Node\Stmt\Property;
/**
 * Implement typed properties.
 */
class TypedProperty extends Plugin
{
    public function enter(ClassLike $class) : void
    {
        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof Property && $stmt->type) {
                $stmt->type = null;
            }
        }
    }
}
