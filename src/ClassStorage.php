<?php

namespace Phabel;

use Phabel\ClassStorage\Storage;

final class ClassStorage
{
    const FILE_KEY = 'ClassStorage:file';

    /**
     * Classes.
     *
     * @var array<class-string, Storage>
     */
    private array $classes = [];
    /**
     * Traits.
     *
     * @var array<trait-string, Storage>
     */
    private array $traits = [];

    /**
     * Constructor.
     */
    public function __construct(array $classes, array $traits)
    {
        foreach ($traits as $name => $trait) {
            $trait->resolve($classes, $traits);
        }
        foreach ($classes as $name => $class) {
            $class->resolve($classes, $traits);
        }

        foreach ($traits as $name => $trait) {
            $this->traits[$name] = $trait->build();
        }
        foreach ($classes as $name => $class) {
            $this->classes[$name] = $class->build();
        }
    }

    /**
     * Get class or trait or null.
     *
     * @param string $class Class or trait name
     * @psalm-param class-string|trait-string $class Class or trait name
     *
     * @return Storage|null
     */
    public function getClassOrTrait(string $class): ?Storage
    {
        return $this->classes[$class] ?? $this->traits[$class] ?? null;
    }
    /**
     * Get class.
     *
     * @param string $class Class name
     * @psalm-param class-string $class Class name
     *
     * @return Storage
     */
    public function getClass(string $class): Storage
    {
        return $this->classes[$class];
    }
    /**
     * Get trait.
     *
     * @param string $class Class name
     * @psalm-param trait-string $class Class name
     *
     * @return Storage
     */
    public function getTrait(string $class): Storage
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
    public function hasClass($class): bool
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
    public function hasTrait($trait): bool
    {
        return isset($this->traits[$trait]);
    }
}
