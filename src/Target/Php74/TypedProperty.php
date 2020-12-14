<?php

namespace Phabel\Target\Php74;

use Phabel\Context;
use Phabel\Plugin;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;

/**
 * Implement typed properties.
 */
class TypedProperty extends Plugin
{
    public function enter(Class_ $class, Context $context): void
    {
        /** @var Property[] */
        $typed = [];
        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof Property && $stmt->type) {
                $typed []= $stmt;
            }
        }

        if (empty($typed)) {
            return;
        }

        
        foreach ($typed as $property) {

        }
    }
}
