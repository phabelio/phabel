<?php

namespace Phabel;

use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;

final class ClassStorage extends Plugin
{
    /**
     * Storage.
     *
     * @psalm-var array<class-string, ClassLike>
     */
    private array $storage = [];
    /**
     * Anonymous class counter.
     *
     * @var array<string, int>
     */
    private array $anonymousCtr = [];

    /**
     * Add method.
     *
     * @param ClassLike $class
     *
     * @return void
     */
    public function enter(ClassLike $class): void
    {
        if ($class->name) {
            $name = (string) $class->namespacedName;
        } else {
            $this->anonymousCtr[$this->getFile()] = $ctr = ($this->anonymousCtr[$this->getFile()] ?? 0) + 1;
            $name = 'class@anonymous' . $this->getFile() . $ctr;
        }
        $class = clone $class;
        $stmts = [];
        foreach ($class->stmts as $stmt) {
            if (!$stmt instanceof ClassMethod) {
                continue;
            }
            if (\is_array($stmt->stmts)) {
                $stmt->stmts = [];
            }
            $stmts []= $stmt;
        }
        $class->stmts = $stmts;
        $this->storage[$name] = $class;
    }

    /**
     * Merge storage with another.
     *
     * @param self $other
     * @return void
     */
    public function merge($other): void
    {
        $this->storage += $other->storage;
    }

    /**
     * Resolve all classes, optionally fixing up a few methods.
     *
     * @return void
     */
    public function finish(): void
    {
        foreach ($this->storage as $name => $class) {
            \var_dump($name);
        }
    }
}
