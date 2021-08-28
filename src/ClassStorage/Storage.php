<?php

namespace Phabel\ClassStorage;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * Stores information about a class.
 */
class Storage
{
    const MODIFIER_NORMAL = 256;
    const STORAGE_KEY = 'Storage:instance';
    /**
     * Method list.
     *
     * @psalm-var array<string, ClassMethod>
     */
    private $methods = [];
    /**
     * Abstract method list.
     *
     * @psalm-var array<string, ClassMethod>
     */
    private $abstractMethods = [];
    /**
     * Removed method list.
     *
     * @var array<string, true>
     */
    private $removedMethods = [];
    /**
     * Classes/interfaces to extend.
     *
     * @var array<class-string, Storage>
     */
    private $extends = [];
    /**
     * Classes/interfaces that extend us.
     *
     * @var array<class-string, Storage>
     */
    private $extendedBy = [];
    /**
     * Class name.
     */
    private $name;
    /**
     * Constructor.
     *
     * @param string                       $name
     * @param array<string, ClassMethod>   $methods
     * @param array<string, ClassMethod>   $abstractMethods
     * @param array<class-string, Builder> $extends
     */
    public function build($name, array $methods, array $abstractMethods, array $extends)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $name = (string) $name;
        }
        $this->name = $name;
        $this->methods = $methods;
        $this->abstractMethods = $abstractMethods;
        foreach ($methods as $method) {
            if ($method->getAttribute(Builder::STORAGE_KEY)) {
                $method->setAttribute(self::STORAGE_KEY, $method->getAttribute(Builder::STORAGE_KEY)->build());
                $method->setAttribute(Builder::STORAGE_KEY, null);
            }
            $method->flags |= self::MODIFIER_NORMAL;
        }
        foreach ($abstractMethods as $method) {
            if ($method->getAttribute(Builder::STORAGE_KEY)) {
                $method->setAttribute(self::STORAGE_KEY, $method->getAttribute(Builder::STORAGE_KEY)->build());
                $method->setAttribute(Builder::STORAGE_KEY, null);
            }
        }
        foreach ($extends as $name => $class) {
            $this->extends[$name] = $class->build();
        }
        foreach ($this->extends as $name => $class) {
            $class->extendedBy[$this->name] = $this;
        }
    }
    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        $phabelReturn = $this->name;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Get method list.
     *
     * @param int-mask<Class_::MODIFIER_*> $typeMask Mask
     * @param int-mask<Class_::MODIFIER_*> $visibilityMask Mask
     *
     * @return \Generator<string, ClassMethod, null, void>
     */
    public function getMethods($typeMask = ~Class_::VISIBILITY_MODIFIER_MASK, $visibilityMask = Class_::VISIBILITY_MODIFIER_MASK)
    {
        if (!\is_int($typeMask)) {
            if (!(\is_bool($typeMask) || \is_numeric($typeMask))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($typeMask) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($typeMask) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $typeMask = (int) $typeMask;
        }
        if (!\is_int($visibilityMask)) {
            if (!(\is_bool($visibilityMask) || \is_numeric($visibilityMask))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($visibilityMask) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($visibilityMask) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $visibilityMask = (int) $visibilityMask;
        }
        if ($typeMask & Class_::MODIFIER_ABSTRACT) {
            foreach ($this->abstractMethods as $name => $method) {
                if (($method->flags | Class_::MODIFIER_ABSTRACT) & $typeMask && $method->flags & $visibilityMask) {
                    (yield $name => $method);
                }
            }
        }
        if ($typeMask & self::MODIFIER_NORMAL) {
            foreach ($this->methods as $name => $method) {
                if ($method->flags & $typeMask && $method->flags & $visibilityMask) {
                    (yield $name => $method);
                }
            }
        }
    }
    /**
     * Get classes which extend this class.
     *
     * @return array<class-string, Storage>
     */
    public function getExtendedBy()
    {
        $phabelReturn = $this->extendedBy;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get classes which this class extends.
     *
     * @return array<class-string, Storage>
     */
    public function getExtends()
    {
        $phabelReturn = $this->extends;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get all child classes.
     *
     * @return \Generator<void, Storage, null, void>
     */
    public function getAllChildren()
    {
        foreach ($this->extendedBy as $class) {
            (yield $class);
            yield from $class->getAllChildren();
        }
    }
    /**
     * Get all parent classes.
     *
     * @return \Generator<void, Storage, null, void>
     */
    public function getAllParents()
    {
        foreach ($this->extends as $class) {
            (yield $class);
            yield from $class->getAllParents();
        }
    }
    /**
     * Get all methods which override the specified method in child classes.
     *
     * @param string $method Method
     * @param int-mask<Class_::MODIFIER_*> $typeMask Mask
     * @param int-mask<Class_::MODIFIER_*> $visibilityMask Mask
     *
     * @return \Generator<void, ClassMethod, null, void>
     */
    public function getOverriddenMethods($name, $typeMask = ~Class_::VISIBILITY_MODIFIER_MASK, $visibilityMask = Class_::VISIBILITY_MODIFIER_MASK)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $name = (string) $name;
        }
        if (!\is_int($typeMask)) {
            if (!(\is_bool($typeMask) || \is_numeric($typeMask))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($typeMask) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($typeMask) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $typeMask = (int) $typeMask;
        }
        if (!\is_int($visibilityMask)) {
            if (!(\is_bool($visibilityMask) || \is_numeric($visibilityMask))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($visibilityMask) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($visibilityMask) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $visibilityMask = (int) $visibilityMask;
        }
        foreach ($this->getAllChildren() as $child) {
            if (isset($child->abstractMethods[$name])) {
                $method = $child->abstractMethods[$name];
                $flags = $method->flags | Class_::MODIFIER_ABSTRACT;
                if ($flags & $typeMask && $flags & $visibilityMask) {
                    (yield $method);
                }
            }
            if (isset($child->methods[$name])) {
                $method = $child->methods[$name];
                $flags = $method->flags;
                if ($flags & $typeMask && $flags & $visibilityMask) {
                    (yield $method);
                }
            }
        }
    }
    /**
     * Remove method.
     *
     * @param ClassMethod $method Removed method
     *
     * @return bool
     */
    public function removeMethod(ClassMethod $method)
    {
        $name = $method->name->name;
        if ($method->stmts !== null) {
            if (isset($this->methods[$name])) {
                $this->removedMethods[$name] = true;
                unset($this->methods[$name]);
                $phabelReturn = true;
                if (!\is_bool($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (bool) $phabelReturn;
                }
                return $phabelReturn;
            }
        } elseif (isset($this->abstractMethods[$name])) {
            $this->removedMethods[$name] = true;
            unset($this->abstractMethods[$name]);
            $phabelReturn = true;
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (bool) $phabelReturn;
            }
            return $phabelReturn;
        }
        $phabelReturn = false;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Process method from AST.
     *
     * @return bool
     */
    public function process(ClassMethod $method)
    {
        $name = $method->name->name;
        if (isset($this->removedMethods[$name])) {
            $phabelReturn = true;
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (bool) $phabelReturn;
            }
            return $phabelReturn;
        }
        $myMethod = isset($this->methods[$name]) ? $this->methods[$name] : $this->abstractMethods[$name];
        foreach ($myMethod->getSubNodeNames() as $name) {
            if ($name === 'stmts') {
                continue;
            }
            $method->{$name} = $myMethod->{$name};
        }
        foreach ($myMethod->getAttributes() as $key => $attribute) {
            if (\str_contains($key, ':')) {
                $method->setAttribute($key, $attribute);
            }
        }
        $phabelReturn = false;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
}
