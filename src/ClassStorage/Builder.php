<?php

namespace Phabel\ClassStorage;

use Phabel\PluginGraph\CircularException;
use Phabel\Tools;
use PhpParser\Node\Stmt\Class_ as StmtClass_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\TraitUseAdaptation\Alias;
use PhpParser\Node\Stmt\TraitUseAdaptation\Precedence;

/**
 * Builds information about a class.
 */
class Builder
{
    const STORAGE_KEY = 'Storage:tmp';
    /**
     * Method list.
     *
     * @psalm-var array<string, ClassMethod>
     */
    private array $methods = [];
    /**
     * Abstract method list.
     *
     * @psalm-var array<string, ClassMethod>
     */
    private array $abstractMethods = [];

    /**
     * Extended classes/interfaces.
     *
     * @var array<class-string, Builder|true>
     */
    private array $extended = [];
    /**
     * Used classes/interfaces.
     *
     * @var array<trait-string, Builder|true>
     */
    private array $use = [];

    /**
     * Use aliases.
     *
     * trait => [oldName => [newTrait, newName]]
     *
     * @var array<trait-string, array<string, array{0: trait-string, 1: string}>>
     */
    private array $useAlias = [];

    /**
     * Whether we're resolving.
     */
    private bool $resolving = false;
    /**
     * Whether we resolved.
     */
    private bool $resolved = false;

    /**
     * Storage.
     */
    private Storage $storage;

    /**
     * Class name.
     */
    private string $name;

    /**
     * Constructor.
     *
     * @param ClassLike $class Class or trait
     */
    public function __construct(ClassLike $class)
    {
        $this->name = Tools::getFqdn($class);
        if ($class instanceof Interface_ || $class instanceof StmtClass_) {
            foreach (
                \is_array($class->extends)
                ? $class->extends
                : ($class->extends ? [$class->extends] : [])
             as $name) {
                $this->extended[Tools::getFqdn($name)] = true;
            }
            foreach ($class->implements ?? [] as $name) {
                $this->extended[Tools::getFqdn($name)] = true;
            }
        }
        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof ClassMethod) {
                $this->addMethod($stmt);
            }
            if ($stmt instanceof TraitUse) {
                foreach ($stmt->traits as $trait) {
                    $this->use[Tools::getFqdn($trait)] = true;
                }
                foreach ($stmt->adaptations as $adapt) {
                    $trait = Tools::getFqdn($adapt->trait);
                    $method = Tools::getFqdn($adapt->method);
                    if ($adapt instanceof Alias) {
                        $this->useAlias[$trait][$method] = [
                            $trait,
                            Tools::getFqdn($adapt->newName)
                        ];
                    } elseif ($adapt instanceof Precedence) {
                        foreach ($adapt->insteadof as $name) {
                            $insteadOf = Tools::getFqdn($name);
                            $this->useAlias[$insteadOf][$method] = [
                                $trait,
                                $method
                            ];
                        }
                    }
                }
            }
        }
    }
    /**
     * Add method.
     *
     * @var ClassMethod $method
     */
    private function addMethod(ClassMethod $method)
    {
        $method->setAttribute(self::STORAGE_KEY, $this);
        if ($method->stmts === null) {
            $this->abstractMethods[$method->name->name] = $method;
        } else {
            $this->methods[$method->name->name] = $method;
        }
    }

    /**
     * Resolve class tree.
     *
     * @param array<class-string, Builder> $classes
     * @param array<trait-string, Builder> $traits
     */
    public function resolve(array $classes, array $traits): self
    {
        if ($this->resolved) {
            return $this;
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
        foreach ($this->use as $trait => $_) {
            if (!isset($traits[$trait])) {
                continue;
            }
            $resolved = $traits[$trait]->resolve($classes, $traits);
            foreach (\array_merge($resolved->methods, $resolved->abstractMethods) as $name => $method) {
                if (isset($this->useAlias[$trait][$name])) {
                    [$newTrait, $name] = $this->useAlias[$trait][$name];
                    if (!isset($traits[$newTrait])) {
                        continue;
                    }
                    $newTrait = $traits[$newTrait]->resolve($classes, $traits);
                    if (isset($newTrait->methods[$name])) {
                        $this->methods[$name] = $newTrait->methods[$name];
                    } elseif (isset($newTrait->abstractMethods[$name])) {
                        $this->abstractMethods[$name] = $newTrait->methods[$name];
                    }
                    continue;
                }
                if ($method->stmts === null) {
                    $this->abstractMethods[$name] = $method;
                } else {
                    $this->methods[$name] = $method;
                }
            }
        }
        foreach ($this->extended as $class => &$res) {
            if (isset($classes[$class])) {
                $res = $classes[$class]->resolve($classes, $traits);
            }
        }
        $this->resolving = false;
        $this->resolved = true;

        return $this;
    }

    /**
     * Build storage.
     *
     * @return Storage
     */
    public function build(): Storage
    {
        if (!isset($this->storage)) {
            $this->storage = new Storage;
            $this->storage->build($this->name, $this->methods, $this->abstractMethods, $this->extended);
        }
        return $this->storage;
    }
}
