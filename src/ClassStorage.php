<?php

namespace Phabel;

use Phabel\ClassStorage\Class_;

final class ClassStorage
{
    /**
     * Classes
     * 
     * @var array<class-string, Class_>
     */
    private array $classes;
    /**
     * Traits
     * 
     * @var array<trait-string, Class_>
     */
    private array $traits;

    /**
     * Constructor
     */
    public function __construct(array $classes, array $traits)
    {
        $this->classes = $classes;
        $this->traits = $traits;

        foreach ($this->traits as $trait) {
            $trait->resolve($this);
        }
        foreach ($this->classes as $class) {
            $class->resolve($this);
        }
    }

    /**
     * Get class
     * 
     * @param string $class Class name
     * @psalm-param class-string $class Class name
     * 
     * @return Class_
     */
    public function getClass(string $class): Class_
    {
        return $this->classes[$class];
    }
    /**
     * Get trait
     * 
     * @param string $class Class name
     * @psalm-param trait-string $class Class name
     * 
     * @return Class_
     */
    public function getTrait(string $class): Class_
    {
        return $this->traits[$class];
    }

    /**
     * Check whether we have a class
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
     * Check whether we have a trait
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
