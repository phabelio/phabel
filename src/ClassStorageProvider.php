<?php

namespace Phabel;

use JsonSerializable;
use Phabel\ClassStorage\Storage;
use Phabel\PhpParser\Node\Stmt\ClassLike;
use Phabel\PhpParser\Node\Stmt\ClassMethod;
use Phabel\PhpParser\Node\Stmt\Nop;
abstract class ClassStorageProvider extends \Phabel\Plugin implements JsonSerializable
{
    const PROCESSED = 'ClassStorageProvider:processed';
    /**
     * Class count.
     */
    private $count = [];
    /**
     * Process class graph.
     *
     * @param ClassStorage $storage
     * @return bool
     */
    public static abstract function processClassGraph(\Phabel\ClassStorage $storage);
    /**
     * Enter file.
     *
     * @param RootNode $_
     * @return void
     */
    public function enterRoot(\Phabel\RootNode $_, \Phabel\Context $context)
    {
        $this->count[$context->getFile()] = [];
    }
    /**
     * Populate class storage.
     *
     * @param ClassLike $classLike
     * @return void
     */
    public function enterClassStorage(ClassLike $class, \Phabel\Context $context)
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
            $this->count[$file][$name] = isset($this->count[$file][$name]) ? $this->count[$file][$name] : 0;
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
    public function getGlobalClassStorage()
    {
        $phabelReturn = $this->getConfig(\Phabel\ClassStorage::class, null);
        if (!$phabelReturn instanceof \Phabel\ClassStorage) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ClassStorage, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * JSON representation.
     *
     * @return string
     */
    public function jsonSerialize()
    {
        $phabelReturn = \spl_object_hash($this);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
        }
        return $phabelReturn;
    }
}
