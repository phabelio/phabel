<?php

namespace Phabel\Target\Php72\TypeRestrictionWidening;

use Phabel\Context;
use Phabel\PersistentContextInterface;
use PhpParser\Node\Stmt\ClassMethod;

final class Storage implements PersistentContextInterface
{
    private const IS_ABSTRACT = null;

    /**
     * Storage.
     *
     * @psalm-var array<string, array<class-string, array<string, ClassMethod>>>
     */
    private array $storage = [];
    /**
     * Anonymous storage.
     *
     * @psalm-var array<string, array<string, ClassMethod>[]>
     */
    private array $anonymousStorage = [];

    /**
     * Add method.
     *
     * @param ClassMethod $method
     * @param Context     $ctx
     * @return void
     */
    public function add(ClassMethod $method, Context $ctx): void
    {
        $method = clone $method;
        if (\is_array($method->stmts)) {
            $method->stmts = [];
        }
        if ($ctx->parents[0]->name) {
            $this->storage[$ctx->getFile()][$ctx->parents[0]->name][$method->name] = $method;
        } else {
            $this->anonymousStorage[$ctx->getFile()][]= [$method->name, $method];
        }
    }

    /**
     * Merge storage with another.
     *
     * @param self $other
     * @return void
     */
    public function merge($other): void
    {
        foreach ($other->storage as $file => $classes) {
            foreach ($classes as $class => $methods) {
                foreach ($methods as $name => $method) {
                    $this->storage[$file][$class][$name] = $method;
                }
            }
        }
        foreach ($other->anonymousStorage as $file => $methods) {
            $this->anonymousStorage[$file] = \array_merge($methods, $this->anonymousStorage[$file] ?? []);
        }
    }

    public function finish(): void
    {
    }
}
