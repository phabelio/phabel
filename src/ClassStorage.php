<?php

namespace Phabel;

use Phabel\ClassStorage\Storage;
use Phabel\Plugin\ClassStoragePlugin;
use Phabel\Plugin\TypeHintReplacer;
use Phabel\PhpParser\Node\Identifier;
use Phabel\PhpParser\Node\Name;
use Phabel\PhpParser\Node\NullableType;
use Phabel\PhpParser\Node\UnionType;
final class ClassStorage
{
    const FILE_KEY = 'ClassStorage:file';
    /**
     * Classes.
     *
     * @var array<string, array<string, Storage>>
     */
    private $classes = [];
    /**
     * Traits.
     *
     * @var array<string, array<string, Storage>>
     */
    private $traits = [];
    /**
     * Files to process.
     *
     * @var array<string, true>
     */
    private $files = [];
    /**
     * Constructor.
     */
    public function __construct(ClassStoragePlugin $plugin)
    {
        foreach ($plugin->traits as $traits) {
            foreach ($traits as $trait) {
                $trait->resolve($plugin);
            }
        }
        foreach ($plugin->classes as $classes) {
            foreach ($classes as $class) {
                $class->resolve($plugin);
            }
        }
        foreach ($plugin->traits as $name => $fileTraits) {
            foreach ($fileTraits as $file => $trait) {
                $trait = $trait->build();
                $this->traits[$name][$file] = $trait;
                $this->files[$file] = \true;
            }
        }
        foreach ($plugin->classes as $name => $fileClasses) {
            foreach ($fileClasses as $file => $class) {
                $class = $class->build();
                $this->classes[$name][$file] = $class;
                $this->files[$file] = \true;
            }
        }
    }
    /**
     * Get all files to process.
     *
     * @return array<string, true>
     */
    public function getFiles() : array
    {
        return \array_keys($this->files);
    }
    /**
     * Get class.
     *
     * @param string $file File name
     * @param string $name Compound name
     *
     * @return Storage
     */
    public function getClass(string $file, string $name) : Storage
    {
        return $this->classes[$name][$file] ?? $this->traits[$name][$file];
    }
    /**
     * Get class by class name.
     *
     * @param class-string $class Class name
     *
     * @return ?Storage
     */
    public function getClassByName(string $class)
    {
        $phabelReturn = \array_values($this->classes[$class] ?? [])[0] ?? null;
        if (!($phabelReturn instanceof Storage || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Storage, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get storage.
     *
     * @return \Generator<class-string, Storage, null, void>
     */
    public function getClasses() : \Generator
    {
        foreach ($this->classes as $class => $classes) {
            foreach ($classes as $_ => $storage) {
                (yield $class => $storage);
            }
        }
    }
    private static function typeArray($type) : array
    {
        if (!(\is_null($type) || $type instanceof Identifier || $type instanceof Name || $type instanceof NullableType || $type instanceof UnionType)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($type) must be of type Identifier|Name|NullableType|UnionType|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $types = [];
        if ($type instanceof NullableType) {
            $types = [$type->type, new Identifier('null')];
        } elseif ($type instanceof UnionType) {
            $types = $type->types;
        } elseif (!TypeHintReplacer::replaced($type)) {
            $types = [$type];
        }
        return $types;
    }
    /**
     * Compare two types.
     *
     * @param null|Identifier|Name|NullableType|UnionType $typeA
     * @param null|Identifier|Name|NullableType|UnionType $typeB
     * @param Storage $ctxA
     * @param Storage $ctxB
     *
     * @return integer
     */
    public function compare($typeA, $typeB, Storage $ctxA, Storage $ctxB) : int
    {
        if (!(\is_null($typeA) || $typeA instanceof Identifier || $typeA instanceof Name || $typeA instanceof NullableType || $typeA instanceof UnionType)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($typeA) must be of type Identifier|Name|NullableType|UnionType|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($typeA) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!(\is_null($typeB) || $typeB instanceof Identifier || $typeB instanceof Name || $typeB instanceof NullableType || $typeB instanceof UnionType)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($typeB) must be of type Identifier|Name|NullableType|UnionType|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($typeB) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $typeA = self::typeArray($typeA);
        $typeB = self::typeArray($typeB);
        if (\count($typeA) !== \count($typeB)) {
            return \count($typeA) <=> \count($typeB);
        }
        if (\count($typeA) + \count($typeB) === 2) {
            $typeA = $typeA[0];
            $typeB = $typeB[0];
            if ($typeA instanceof Name && $typeB instanceof Name && ($classA = $typeA->parts === ['self'] ? $ctxA : $this->getClassByName(\Phabel\Tools::getFqdn($typeA))) && ($classB = $typeA->parts === ['self'] ? $ctxB : $this->getClassByName(\Phabel\Tools::getFqdn($typeB)))) {
                foreach ($classA->getAllChildren() as $child) {
                    if ($child === $classB) {
                        return 1;
                    }
                }
                foreach ($classB->getAllChildren() as $child) {
                    if ($child === $classA) {
                        return -1;
                    }
                }
            }
        }
        return 0;
    }
}
