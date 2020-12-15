<?php

namespace Phabel\Target\Php74;

use Phabel\Context;
use Phabel\Plugin;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Serializable as GlobalSerializable;

/**
 * Implement __serialize and __unserialize.
 */
class Serializable extends Plugin
{/*
    public function enter(Class_ $class, Context $context): void
    {
        /** @var array<string, &ClassMethod> */
        /*
        $methods = [];
        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof ClassMethod) {
                $name = $stmt->name->toLowerString();
                $methods[$name] = $stmt;
            }
        }

        if (!isset($methods['__serialize']) && !isset($methods['__unserialize'])) {
            return;
        }
        foreach ($class->implements as $name) {
            $resolved = $context->getNameContext()->getResolvedClassName($name);
            if ($resolved->toLowerString() === 'serializable' || $name->toLowerString() === 'serializable') {
                return; // Already implements
            }
        }
        if (isset($methods['__sleep'])) {
            $methods['__sleep']->name = new Identifier('__phabelSleep');
        }
        if (isset($methods['__wakeup'])) {
            $methods['__wakeup']->name = new Identifier('__phabelWakeup');
        }

        $class->implements []= new FullyQualified(GlobalSerializable::class);
    }*/
}
