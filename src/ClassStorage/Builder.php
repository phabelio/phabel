<?php

namespace Phabel\ClassStorage;

use Phabel\Exception;
use Phabel\Plugin\ClassStoragePlugin;
use Phabel\Plugin\ConstantResolver;
use Phabel\PluginGraph\CircularException;
use Phabel\Tools;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\Class_ as StmtClass_;
use PhpParser\Node\Stmt\ClassConst;
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
    const CONSTANT_FLAGS_KEY = 'Storage:constantFlags';
    /**
     * Method list.
     *
     * @psalm-var array<string, ClassMethod>
     */
    private $methods = [];
    /**
     * Abstract method list.
     *
     * @psalm-var array<string, ClassMethod>
     */
    private $abstractMethods = [];
    /**
     * Constant list.
     *
     * @var array<string, Expr>
     */
    public $constants = [];
    /**
     * Constant list.
     *
     * @var array<string, mixed>
     */
    public $constantsResolved = [];
    /**
     * Extended classes/interfaces.
     *
     * @var array<class-string, (Builder | true)>
     */
    private $extends = [];
    /**
     * Used classes/interfaces.
     *
     * @var array<trait-string, (Builder | true)>
     */
    private $use = [];
    /**
     * Use aliases.
     *
     * trait => [oldName => [newTrait, newName]]
     *
     * @var array<trait-string, array<string, array{0: trait-string, 1: string}>>
     */
    private $useAlias = [];
    /**
     * Whether we're resolving.
     * @var bool $resolving
     */
    private $resolving = false;
    /**
     * Whether we resolved.
     * @var bool $resolved
     */
    private $resolved = false;
    /**
     * Storage.
     * @var (Storage | null) $storage
     */
    private $storage = null;
    /**
     * Class name.
     * @var string $name
     */
    private $name;
    /**
     * Constructor.
     *
     * @param ClassLike $class Class or trait
     */
    public function __construct(ClassLike $class, string $customName = '')
    {
        $this->name = Tools::getFqdn($class, $customName);
        if ($class instanceof Interface_ || $class instanceof StmtClass_) {
            foreach (\is_array($class->extends) ? $class->extends : ($class->extends ? [$class->extends] : []) as $name) {
                $this->extends[Tools::getFqdn($name)] = true;
            }
            foreach ($class->implements ?? [] as $name) {
                $this->extends[Tools::getFqdn($name)] = true;
            }
        }
        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof ClassMethod) {
                $this->addMethod($stmt);
            }
            if ($stmt instanceof ClassConst) {
                $this->addConstants($stmt);
            }
            if ($stmt instanceof TraitUse) {
                foreach ($stmt->traits as $trait) {
                    $this->use[Tools::getFqdn($trait)] = true;
                }
                foreach ($stmt->adaptations as $k => $adapt) {
                    $trait = Tools::getFqdn($adapt->trait ?? $stmt->traits[0]);
                    $method = $adapt->method->name;
                    if ($adapt instanceof Alias) {
                        $this->useAlias[$trait][$method] = [$trait, ($adapt->newName ?? \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->name ?? $method];
                    } elseif ($adapt instanceof Precedence) {
                        foreach ($adapt->insteadof as $name) {
                            $insteadOf = Tools::getFqdn($name);
                            $this->useAlias[$insteadOf][$method] = [$trait, $method];
                        }
                    }
                }
            }
        }
    }
    /**
     * Add method.
     *
     * @param ClassMethod $method
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
     * Add constants.
     *
     */
    private function addConstants(ClassConst $const)
    {
        foreach ($const->consts as $constant) {
            $value = $constant->value;
            $value->setAttribute(self::CONSTANT_FLAGS_KEY, $const->flags);
            $this->constants[$constant->name->name] = $value;
        }
    }
    /**
     * Resolve class tree.
     */
    public function resolve(ClassStoragePlugin $plugin): self
    {
        if ($this->resolved) {
            return $this;
        }
        if ($this->resolving) {
            $plugins = [$this->name];
            foreach (\debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, DEBUG_BACKTRACE_PROVIDE_OBJECT) as $frame) {
                $plugins[] = $frame['object']->name;
                if ($frame['object'] === $this) {
                    break;
                }
            }
            throw new CircularException($plugins);
        }
        $this->resolving = true;
        foreach ($this->use as $trait => $_) {
            if (!isset($plugin->traits[$trait])) {
                continue;
            }
            $resolved = \array_values($plugin->traits[$trait])[0]->resolve($plugin);
            foreach ([$resolved->methods, $resolved->abstractMethods] as $list) {
                foreach ($list as $name => $method) {
                    if (isset($this->useAlias[$trait][$name])) {
                        [$newTrait, $name] = $this->useAlias[$trait][$name];
                        if (!isset($plugin->traits[$newTrait])) {
                            continue;
                        }
                        $newTrait = \array_values($plugin->traits[$newTrait])[0]->resolve($plugin);
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
        }
        foreach ($this->extends as $class => &$res) {
            if (isset($plugin->classes[$class])) {
                $res = \array_values($plugin->classes[$class])[0];
            } else {
                unset($this->extends[$class]);
            }
        }
        foreach ($this->constants as $name => $constant) {
            try {
                $this->constantsResolved[$name] = ConstantResolver::resolve($constant, $this->name, $plugin);
            } catch (\Throwable $phabel_db70707c8f43a951) {
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
        if (!$this->resolved) {
            throw new Exception("Trying to build an unresolved class!");
        }
        if (!isset($this->storage)) {
            $this->storage = new Storage();
            $this->storage->build($this->name, $this->methods, $this->abstractMethods, $this->constantsResolved, $this->extends);
        }
        return $this->storage;
    }
    /**
     * Debug info.
     */
    public function __debugInfo(): array
    {
        return ['name' => $this->name, 'methods' => \array_keys($this->methods), 'abstractMethods' => \array_keys($this->abstractMethods), 'extends' => $this->extends, 'useAlias' => $this->useAlias, 'use' => $this->use];
    }
}
