<?php

namespace Phabel\Plugin;

use Phabel\ClassStorage;
use Phabel\Context;
use Phabel\Plugin;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Trait_;

final class ClassStoragePlugin extends Plugin
{
    /**
     * Storage.
     *
     * @psalm-var array<string, array<class-string, ClassLike>>
     */
    private array $storage = [];
    /**
     * Input-output file map
     */
    private array $fileMap;
    /**
     * Anonymous class count.
     */
    private static int $count = 0;
    /**
     * Plugins to call during final iteration.
     *
     * @psalm-var array<class-string<PluginInterface>, true>
     */
    private array $finalPlugins = [];
    /**
     * Previous file.
     */
    private string $previousFile = '';

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
            $name = (string) $class->namespacedName;
        } else {
            $name = "class@anonymous$file".self::$count++;
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

        if ($file !== $this->previousFile) {
            $this->previousFile = $file;
            $this->storage[$file] = [];
            $this->fileMap[$file] = $context->getOutputFile();
        }
        $this->storage[$file][$name] = $class;
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
            $this->storage[$file] ??= [];
            $this->storage[$file] += $classes;
        }
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
        $traits = [];
        $classes = [];
        foreach ($this->storage as $file => $classes) {
            $file = $this->fileMap[$file] ?: $file;
            /** @var ClassLike */
            foreach ($classes as $name => $class) {
                if ($class instanceof Trait_) {

                }
                \var_dump($name);
            }
        }
        return \array_map(fn () => [ClassStorage::class => $this], $this->finalPlugins);
    }

    /**
     * Enable plugin.
     *
     * @param string $target
     * @psalm-param class-string<ClassStorageProvider> $target
     *
     * @return array
     */
    public static function enable(string $target): array
    {
        return [ClassStoragePlugin::class => [$target => true]];
    }
}
