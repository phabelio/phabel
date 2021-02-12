<?php

namespace Phabel\Plugin;

use Phabel\ClassStorage;
use Phabel\ClassStorage\Builder;
use Phabel\ClassStorage\Storage;
use Phabel\Context;
use Phabel\Plugin;
use Phabel\RootNode;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Trait_;

final class ClassStoragePlugin extends Plugin
{
    /**
     * Storage.
     *
     * @var array<class-string, array<string, Builder[]>>
     */
    public array $classes = [];
    /**
     * Storage.
     *
     * @var array<class-string, array<string, Builder[]>>
     */
    public array $traits = [];
    /**
     * Input-output file map.
     */
    private array $fileMap;
    /**
     * Plugins to call during final iteration.
     *
     * @var array<class-string<PluginInterface>, true>
     */
    private array $finalPlugins = [];

    /**
     * Set configuration array.
     *
     * @param array $config
     * @return void
     */
    public function setConfigArray(array $config): void
    {
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
        $this->fileMap[$file] = $context->getOutputFile();
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
        $file = $this->fileMap[$file] ?: $file;
        if ($class->name) {
            $name = (string) self::getFqdn($class);
        } else {
            $name = "class@anonymous";
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
        $class->setAttribute(ClassStorage::FILE_KEY, $file);

        if ($class instanceof Trait_) {
            $this->traits[$file][$name][] = new Builder($class);
        } else {
            $this->classes[$file][$name][] = new Builder($class);
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
        $this->storage = array_merge_recursive($this->storage, $other->storage);
        $this->finalPlugins += $other->finalPlugins;
        $this->fileMap += $other->fileMap;
    }

    /**
     * Resolve all classes, optionally fixing up a few methods.
     *
     * @return array Config to pass to new Traverser instance
     */
    public function finish(): array
    {
        $storage = new ClassStorage($this);
        do {
            $changed = false;
            foreach ($this->finalPlugins as $class => $_) {
                if ($class::processClassGraph($storage)) {
                    $changed = true;
                }
            }
        } while ($changed);
        return \array_map(fn () => [ClassStorage::class => $storage], $this->finalPlugins);
    }
}
