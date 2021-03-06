<?php

namespace Phabel\Target\Php74;

use Phabel\Plugin;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;

/**
 * Implement typed properties.
 */
class TypedProperty extends Plugin
{
    public function enter(Class_ $class): void
    {
        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof Property && $stmt->type) {
                $stmt->type = null;
            }
        }
    }
}
