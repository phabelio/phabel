<?php

namespace Phabel\ClassStorage;

use Phabel\ClassStorage;
use Phabel\PluginGraph\CircularException;
use PhpParser\Node\Stmt\Class_ as StmtClass_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\TraitUse;

/**
 * Stores information about a class
 */
class Class_
{
    /**
     * Method list
     * 
     * @psalm-var array<string, ClassMethod>
     */
    private array $methods = [];
    /**
     * Abstract method list
     * 
     * @psalm-var array<string, ClassMethod>
     */
    private array $abstractMethods = [];

    /**
     * Extended classes/interfaces
     * 
     * @var array<class-string, Class_|true>
     */
    private array $extended = [];
    /**
     * Used classes/interfaces
     * 
     * @var array<trait-string, Class_|true>
     */
    private array $use = [];

    /**
     * Whether we're resolving
     */
    private bool $resolving = false;
    /**
     * Whether we resolved
     */
    private bool $resolved = false;

    /**
     * Class name
     */
    private string $name;

    /**
     * Constructor
     * 
     * @param ClassLike $class Class or trait
     */
    public function __construct(ClassLike $class)
    {
        $this->name = $class->namespacedName;
        if ($class instanceof Interface_ || $class instanceof StmtClass_) {
            foreach ($class->extends as $name) {
                $this->extended[$name->namespacedName] = true;
            } 
            foreach ($class->implements ?? [] as $name) {
                $this->extended[$name->namespacedName] = true;
            }
        }
        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof ClassMethod) {
                $this->addMethod($stmt);
            }
            if ($stmt instanceof TraitUse) {
                foreach ($stmt->traits as $trait) {
                    $this->use[$trait->namespacedName] = true;
                }
            }
        }
    }
    /**
     * Add method
     * 
     * @var ClassMethod $method
     */
    private function addMethod(ClassMethod $method)
    {
        if ($method->stmts === null) {
            $this->abstractMethods[$method->name->name] = $method;
        } else {
            $this->methods[$method->name->name] = $method;
        }
    }

    /**
     * Resolve class tree
     */
    public function resolve(ClassStorage $storage): void
    {
        if ($this->resolved) {
            return;
        }
        if ($this->resolving) {
            $plugins = [$this->name];
            foreach (\debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, DEBUG_BACKTRACE_PROVIDE_OBJECT) as $frame) {
                $plugins []= $frame['object']->name;
                if ($frame['object'] === $this) {
                    break;
                }
            }
            throw new CircularException($plugins);
        }
        $this->resolving = true;
        foreach ($this->use as $trait => &$res) {
            if ($storage->hasTrait($trait)) {
                $res = $storage->getTrait($trait);
                $res->resolve($storage);
            }
        }
        foreach ($this->extended as $class => &$res) {
            if ($storage->hasClass($class)) {
                $res = $storage->getClass($class);
                $res->resolve($storage);
            }
        }
        $this->resolving = false;
        $this->resolved = true;
    }

    /**
     * Get name
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}