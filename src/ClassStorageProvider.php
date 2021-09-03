<?php

namespace Phabel;

use JsonSerializable;
use Phabel\ClassStorage\Storage;
use Phabel\PhpParser\Node\Stmt\ClassLike;
use Phabel\PhpParser\Node\Stmt\ClassMethod;
use Phabel\PhpParser\Node\Stmt\Nop;
abstract class ClassStorageProvider extends \Phabel\Plugin implements JsonSerializable
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
    public static abstract function processClassGraph(\Phabel\ClassStorage $storage) : bool;
    /**
     * Enter file.
     *
     * @param RootNode $_
     * @return void
     */
    public function enterRoot(\Phabel\RootNode $_, \Phabel\Context $context) : void
    {
        $this->count[$context->getFile()] = [];
    }
    /**
     * Populate class storage.
     *
     * @param ClassLike $classLike
     * @return void
     */
    public function enterClassStorage(ClassLike $class, \Phabel\Context $context) : void
    {
        if ($class->hasAttribute(self::PROCESSED)) {
            return;
        }
        $class->setAttribute(self::PROCESSED, \true);
        $file = $context->getFile();
        if ($class->name) {
            $name = self::getFqdn($class);
        } else {
            $name = "class@anonymous{$file}";
            $this->count[$file][$name] ??= 0;
            $name .= "@" . $this->count[$file][$name]++;
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
    public function getGlobalClassStorage() : \Phabel\ClassStorage
    {
        return $this->getConfig(\Phabel\ClassStorage::class, null);
    }
    /**
     * JSON representation.
     *
     * @return string
     */
    public function jsonSerialize() : string
    {
        return \spl_object_hash($this);
    }
}
