<?php

namespace Phabel;

use Phabel\ClassStorage\Storage;
use Phabel\Plugin\ClassStoragePlugin;
use Phabel\Plugin\TypeHintReplacer;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;

final class ClassStorage
{
    const FILE_KEY = 'ClassStorage:file';
    /**
     * Classes.
     *
     * @var array<string, array<string, Storage>>
     */
    private array $classes = [];
    /**
     * Traits.
     *
     * @var array<string, array<string, Storage>>
     */
    private array $traits = [];
    /**
     * Files to process.
     *
     * @var array<string, true>
     */
    private array $files = [];
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
                $this->files[$file] = true;
            }
        }
        foreach ($plugin->classes as $name => $fileClasses) {
            foreach ($fileClasses as $file => $class) {
                $class = $class->build();
                $this->classes[$name][$file] = $class;
                $this->files[$file] = true;
            }
        }
    }
    /**
     * Get all files to process.
     *
     * @return array<string, true>
     */
    public function getFiles(): array
    {
        return $this->files;
    }
    /**
     * Get class.
     *
     * @param string $file File name
     * @param string $name Compound name
     *
     * @return Storage
     */
    public function getClass(string $file, string $name): Storage
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
    public function getClassByName(string $class): ?Storage
    {
        return \array_values($this->classes[$class] ?? [])[0] ?? null;
    }
    /**
     * Get storage.
     *
     * @return \Generator<class-string, Storage, null, void>
     */
    public function getClasses(): \Generator
    {
        foreach ($this->classes as $class => $classes) {
            foreach ($classes as $_ => $storage) {
                (yield $class => $storage);
            }
        }
    }
    private static function typeArray(null|Identifier|Name|NullableType|UnionType $type): array
    {
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
    public function compare(null|Identifier|Name|NullableType|UnionType $typeA, null|Identifier|Name|NullableType|UnionType $typeB, Storage $ctxA, Storage $ctxB): int
    {
        $typeA = self::typeArray($typeA);
        $typeB = self::typeArray($typeB);
        if (\count($typeA) !== \count($typeB)) {
            return \count($typeA) <=> \count($typeB);
        }
        if (\count($typeA) + \count($typeB) === 2) {
            $typeA = $typeA[0];
            $typeB = $typeB[0];
            if ($typeA instanceof Name && $typeB instanceof Name && ($classA = $typeA->parts === ['self'] ? $ctxA : $this->getClassByName(Tools::getFqdn($typeA))) && ($classB = $typeA->parts === ['self'] ? $ctxB : $this->getClassByName(Tools::getFqdn($typeB)))) {
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
