<?php

namespace Phabel;

use Phabel\ClassStorage\Storage;
use Phabel\Plugin\ClassStoragePlugin;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

final class ClassStorage
{
    const FILE_KEY = 'ClassStorage:file';

    /**
     * Classes.
     *
     * @var array<class-string, array<string, Storage[]>>
     */
    private array $classes = [];
    /**
     * Traits.
     *
     * @var array<class-string, array<string, Storage[]>>
     */
    private array $traits = [];

    /**
     * Root classes.
     *
     * @var array<class-string, []Storage>
     */
    private array $rootClasses;

    /**
     * Constructor.
     */
    public function __construct(ClassStoragePlugin $plugin)
    {
        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($plugin->traits)) as $trait) {
            $trait->resolve($plugin);
        }
        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($plugin->classes)) as $class) {
            $class->resolve($plugin);
        }

        foreach ($plugin->traits as $name => $fileTraits) {
            foreach ($fileTraits as $file => $idxTraits) {
                foreach ($idxTraits as $trait) {
                    $trait = $trait->build();
                    $this->traits[$name][$file][] = $trait;
                }
            }
        }
        foreach ($plugin->classes as $name => $fileClasses) {
            foreach ($fileClasses as $file => $idxClasses) {
                foreach ($idxClasses as $class) {
                    $class = $class->build();
                    $this->classes[$name][$file][] = $class;
                }
            }
        }

        foreach ($this->classes as $name => $fileClasses) {
            foreach ($fileClasses as $file => $idxClasses) {
                foreach ($idxClasses as $class) {
                    if (!$class->getExtends() && !$class->getExtendedBy()) {
                        $this->rootClasses[$name][] = $class;
                    }
                }
            }
        }
    }

    /**
     * Get class.
     *
     * @param string $class Class name
     * @psalm-param class-string $class Class name
     *
     * @return Storage
     */
    public function getClass(string $class, int $idx): Storage
    {
        return $this->classes[$class][$idx];
    }
    /**
     * Get trait.
     *
     * @param string $class Class name
     * @psalm-param trait-string $class Class name
     *
     * @return Storage
     */
    public function getTrait(string $class, int $idx): Storage
    {
        return $this->traits[$class];
    }

    /**
     * Check whether we have a class.
     *
     * @param string $class Class name
     *
     * @return bool
     */
    public function hasClass(string $class, int $idx): bool
    {
        return isset($this->classes[$class]);
    }
    /**
     * Check whether we have a trait.
     *
     * @param string $trait Trait name
     *
     * @return bool
     */
    public function hasTrait(string $trait, int $idx): bool
    {
        return isset($this->traits[$trait]);
    }

    /**
     * Get storage
     *
     * @return RecursiveIteratorIterator
     */
    public function getClasses(): RecursiveIteratorIterator
    {
        return new RecursiveIteratorIterator(new RecursiveArrayIterator($this->classes));
    }

    /**
     * Get root classes
     *
     * @return array<class-string, Storage[]>
     */
    public function getRootClasses(): array
    {
        return $this->rootClasses;
    }
}
