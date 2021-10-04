<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use PhabelVendor\PhpParser\Node\Stmt\ClassLike;
use PhabelVendor\PhpParser\Node\Stmt\Property;
use PhabelVendor\PhpParser\Node\UnionType;
/**
 * Implement typed properties.
 */
class TypedProperty extends Plugin
{
    public function enter(ClassLike $class) : void
    {
        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof Property && $stmt->type instanceof UnionType) {
                $stmt->type = null;
            }
        }
    }
}
