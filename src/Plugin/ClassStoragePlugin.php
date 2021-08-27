<?php

namespace Phabel\Plugin;

use Exception;
use Phabel\ClassStorage;
use Phabel\ClassStorage\Builder;
use Phabel\ClassStorage\Storage;
use Phabel\ClassStorageProvider;
use Phabel\Context;
use Phabel\Plugin;
use Phabel\RootNode;
use PhpParser\Builder\Method;
use PhpParser\Builder\Param;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Trait_;

final class ClassStoragePlugin extends Plugin
{
    /**
     * Storage.
     *
     * @var array<string, array<string, Builder>>
     */
    public array $classes = [];
    /**
     * Storage.
     *
     * @var array<string, array<string, Builder>>
     */
    public array $traits = [];

    /**
     * Count.
     */
    private array $count = [];
    /**
     * Plugins to call during final iteration.
     *
     * @var array<class-string<ClassStorageProvider>, true>
     */
    protected array $finalPlugins = [];

    /**
     * Check if plugin should run.
     *
     * @param string $package Package name
     *
     * @return boolean
     */
    public function shouldRun(string $package): bool
    {
        return true;
    }
    /**
     * Check if plugin should run.
     *
     * @param string $file File name
     *
     * @return boolean
     */
    public function shouldRunFile(string $file): bool
    {
        return true;
    }

    /**
     * Set configuration array.
     *
     * @param array $config
     * @return void
     */
    public function setConfigArray(array $config): void
    {
        parent::setConfigArray($config);
        $this->finalPlugins += $config;
    }

    /**
     * Enter file.
     *
     * @param RootNode $_
     * @return void
     */
    public function enterRoot(RootNode $_, Context $context): void
    {
        $file = $context->getFile();
        $this->count[$file] = [];
        foreach ($this->traits as $trait => $traits) {
            if (isset($traits[$file])) {
                unset($this->traits[$trait][$file]);
            }
        }
        foreach ($this->classes as $class => $classes) {
            if (isset($classes[$file])) {
                unset($this->classes[$class][$file]);
            }
        }
    }
    /**
     * Add method.
     *
     * @param ClassLike $class
     *
     * @return void
     */
    public function enter(ClassLike $class, Context $context): void
    {
        $file = $context->getFile();
        if ($class->name) {
            $name = self::getFqdn($class);
        } else {
            $name = "class@anonymous$file";
            $this->count[$file][$name] ??= 0;
            $name .= "@".$this->count[$file][$name]++;
        }

        $class = clone $class;
        $stmts = [];
        foreach ($class->stmts as $stmt) {
            if (!$stmt instanceof ClassMethod) {
                continue;
            }
            $stmts []= $stmt;
        }
        $class->stmts = $stmts;
        $class->setAttribute(ClassStorage::FILE_KEY, $file);

        if ($class instanceof Trait_) {
            $this->traits[$name][$file] = new Builder($class);
        } else {
            $this->classes[$name][$file] = new Builder($class, $name);
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
        foreach ($other->classes as $class => $classes) {
            foreach ($classes as $file => $builder) {
                if (isset($this->classes[$class][$file])) {
                    throw new Exception('Already exists!');
                }
                $this->classes[$class][$file] = $builder;
            }
        }
        foreach ($other->traits as $class => $traits) {
            foreach ($traits as $file => $builder) {
                if (isset($this->traits[$class][$file])) {
                    throw new Exception('Already exists!');
                }
                $this->traits[$class][$file] = $builder;
            }
        }
        $this->finalPlugins += $other->finalPlugins;
    }

    /**
     * Resolve all classes, optionally fixing up a few methods.
     *
     * @return array{0: array, 1: array<string, true>} Config to pass to new Traverser instance
     */
    public function finish(): array
    {
        $storage = new ClassStorage($this);
        $processedAny = false;
        do {
            $processed = false;
            foreach ($this->finalPlugins as $name => $_) {
                $processed = $name::processClassGraph($storage) || $processed;
            }
            $processedAny = $processed || $processedAny;
        } while ($processed);
        if (!$processedAny) {
            return [[], []];
        }
        $result = \array_fill_keys(\array_keys($this->finalPlugins), [ClassStorage::class => $storage]);
        return [$result, $storage->getFiles()];
    }
}
