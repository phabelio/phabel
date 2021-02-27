<?php

namespace Phabel\ClassStorage;

use Phabel\Exception;
use Phabel\Plugin\ClassStoragePlugin;
use Phabel\PluginGraph\CircularException;
use Phabel\Tools;
use PhpParser\Node\Stmt\Class_ as StmtClass_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\TraitUseAdaptation\Alias;
use PhpParser\Node\Stmt\TraitUseAdaptation\Precedence;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

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
    private ?Storage $storage = null;

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
     */
    public function resolve(ClassStoragePlugin $plugin): self
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
            if (!isset($plugin->traits[$trait])) {
                continue;
            }
            $resolved = \array_values($plugin->traits[$trait])[0]->resolve($plugin);
            foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator([$resolved->methods, $resolved->abstractMethods])) as $name => $method) {
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
        foreach ($this->extended as $class => &$res) {
            $res = array_values($plugin->classes[$class])[0];
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
            $this->storage = new Storage;
            $this->storage->build($this->name, $this->methods, $this->abstractMethods, $this->extended);
        }
        return $this->storage;
    }
}
