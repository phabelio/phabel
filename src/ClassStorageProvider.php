<?php

namespace Phabel;

use JsonSerializable;
use Phabel\ClassStorage\Storage;
use Phabel\Plugin\ClassStoragePlugin;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Nop;

abstract class ClassStorageProvider extends Plugin implements JsonSerializable
{
    private const PROCESSED = 'ClassStorageProvider:processed';
    /**
     * Class count.
     */
    private array $count = [];

    /**
     * Process class graph.
     *
     * @param ClassStorage $storage
     * @return bool
     */
    abstract public static function processClassGraph(ClassStorage $storage): bool;
    /**
     * Enter file.
     *
     * @param RootNode $_
     * @return void
     */
    public function enterRoot(RootNode $_, Context $context): void
    {
        $this->count[$context->getFile()] = [];
    }
    /**
     * Populate class storage.
     *
     * @param ClassLike $classLike
     * @return void
     */
    public function enterClassStorage(ClassLike $class, Context $context): void
    {
        if ($class->hasAttribute(self::PROCESSED)) {
            return;
        }
        $class->setAttribute(self::PROCESSED, true);

        $file = $context->getFile();
        if ($class->name) {
            $name = self::getFqdn($class);
        } else {
            $name = "class@anonymous$file";
            $this->count[$file][$name] ??= 0;
            $name .= "@".$this->count[$file][$name]++;
        }
        $storage = $this->getGlobalClassStorage()->getClass($file, $name);
        foreach ($class->stmts as $k => $stmt) {
            if ($stmt instanceof ClassMethod && $storage->process($stmt)) {
                $class->stmts[$k] = new Nop();
            }
        }
    }
    /**
     * Get global class storage.
     *
     * @return ClassStorage
     */
    public function getGlobalClassStorage(): ClassStorage
    {
        return $this->getConfig(ClassStorage::class, null);
    }
    /**
     * JSON representation.
     *
     * @return string
     */
    public function jsonSerialize(): string
    {
        return \spl_object_hash($this);
    }

    public static function previous(array $config): array
    {
        return [
            ClassStoragePlugin::class => [
                ClassStorage::class => $config[ClassStorage::class],
                static::class => true
            ]
        ];
    }
}
