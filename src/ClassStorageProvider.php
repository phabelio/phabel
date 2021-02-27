<?php

namespace Phabel;

use JsonSerializable;
use Phabel\ClassStorage\Storage;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;

abstract class ClassStorageProvider extends Plugin implements JsonSerializable
{
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
        $file = $context->getFile();
        if ($class->name) {
            $name = self::getFqdn($class);
        } else {
            $name = "class@anonymous$file";
            $name .= "@".$this->count[$file][$name]++;
        }
        $storage = $this->getGlobalClassStorage()->getClass($file, $name);
        foreach ($class->stmts as $k => $stmt) {
            if ($stmt instanceof ClassMethod && $storage->process($stmt)) {
                unset($class->stmts[$k]);
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
}
