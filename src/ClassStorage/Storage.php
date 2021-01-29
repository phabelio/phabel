<?php

namespace Phabel\ClassStorage;

use PhpParser\Node\Stmt\ClassMethod;

/**
 * Stores information about a class.
 */
class Storage
{
    const STORAGE_KEY = 'Storage:instance';
    /**
     * Method list.
     *
     * @psalm-var array<string, ClassMethod>
     */
    private array $methods = [];
    /**
     * Abstract method list.
     *
     * @psalm-var array<string, ClassMethod>
     */
    private array $abstractMethods = [];

    /**
     * Classes/interfaces to extend.
     *
     * @var array<class-string, Storage>
     */
    private array $extends = [];

    /**
     * Classes/interfaces that extend us.
     *
     * @var array<class-string, Storage>
     */
    private array $extendedBy = [];

    /**
     * Class name.
     */
    private string $name;

    /**
     * Constructor.
     *
     * @param string                       $name
     * @param array<string, ClassMethod>   $methods
     * @param array<string, ClassMethod>   $abstractMethods
     * @param array<class-string, Builder> $extends
     */
    public function build(string $name, array $methods, array $abstractMethods, array $extends)
    {
        $this->name = $name;
        $this->methods = $methods;
        $this->abstractMethods = $abstractMethods;

        foreach ($methods as $method) {
            if ($method->hasAttribute(Builder::STORAGE_KEY)) {
                $method->setAttribute(self::STORAGE_KEY, $method->getAttribute(Builder::STORAGE_KEY)->build());
                $method->setAttribute(Builder::STORAGE_KEY, null);
            }
        }
        foreach ($abstractMethods as $method) {
            if ($method->hasAttribute(Builder::STORAGE_KEY)) {
                $method->setAttribute(self::STORAGE_KEY, $method->getAttribute(Builder::STORAGE_KEY)->build());
                $method->setAttribute(Builder::STORAGE_KEY, null);
            }
        }

        foreach ($extends as $name => $class) {
            $this->extends[$name] = $class->build();
        }
        foreach ($this->extends as $class) {
            $class->extendedBy[$this->name] = $this;
        }
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get method list.
     *
     * @return ClassMethod[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Get abstract method list.
     *
     * @return ClassMethod[]
     */
    public function getAbstractMethods(): array
    {
        return $this->abstractMethods;
    }
}
