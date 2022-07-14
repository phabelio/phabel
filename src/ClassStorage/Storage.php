<?php

namespace Phabel\ClassStorage;

use Phabel\Tools;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * Stores information about a class.
 */
class Storage
{
    private const MODIFIER_NORMAL = 256;
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
     * Removed method list.
     *
     * @var array<string, true>
     */
    private array $removedMethods = [];
    /**
     * Classes/interfaces to extend.
     *
     * @var array<class-string, Storage>
     */
    private array $extends = [];
    /**
     * Class constants.
     *
     * @var array<string, mixed>
     */
    private array $constants = [];
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
     * @param string $name
     * @param array<string, ClassMethod> $methods
     * @param array<string, ClassMethod> $abstractMethods
     * @param array<string, Expr> $constants
     * @param array<class-string, Builder> $extends
     */
    public function build(string $name, array $methods, array $abstractMethods, array $constants, array $extends)
    {
        $this->name = $name;
        $this->methods = $methods;
        $this->abstractMethods = $abstractMethods;
        foreach ($constants as $name => $constant) {
            try {
                $this->constants[$name] = Tools::toLiteral($constant);
            } catch (\Throwable $phabel_cb33c8486804c377) {
                // Ignore errors caused by constant lookups
            }
        }
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
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * Get method list.
     *
     * @param int-mask<Class_::MODIFIER_*> $typeMask Mask
     * @param int-mask<Class_::MODIFIER_*> $visibilityMask Mask
     *
     * @return \Generator<string, ClassMethod, null, void>
     */
    public function getMethods(int $typeMask = -8, int $visibilityMask = 7): \Generator
    {
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
     * Get constant, ignoring constant visibility for now.
     *
     * @return mixed
     */
    public function getConstant(string $name)
    {
        $phabelReturn = $this->constants[$name];
        if (!true) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type mixed, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get classes which extend this class.
     *
     * @return array<class-string, Storage>
     */
    public function getExtendedBy(): array
    {
        return $this->extendedBy;
    }
    /**
     * Get classes which this class extends.
     *
     * @return array<class-string, Storage>
     */
    public function getExtends(): array
    {
        return $this->extends;
    }
    /**
     * Get all child classes.
     *
     * @return \Generator<void, Storage, null, void>
     */
    public function getAllChildren(): \Generator
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
    public function getAllParents(): \Generator
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
    public function getOverriddenMethods(string $name, int $typeMask = -8, int $visibilityMask = 7): \Generator
    {
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
    public function removeMethod(ClassMethod $method): bool
    {
        $name = $method->name->name;
        if ($method->stmts !== null) {
            if (isset($this->methods[$name])) {
                $this->removedMethods[$name] = true;
                unset($this->methods[$name]);
                return true;
            }
        } elseif (isset($this->abstractMethods[$name])) {
            $this->removedMethods[$name] = true;
            unset($this->abstractMethods[$name]);
            return true;
        }
        return false;
    }
    /**
     * Process method from AST.
     *
     * @return bool
     */
    public function process(ClassMethod $method): bool
    {
        $name = $method->name->name;
        if (isset($this->removedMethods[$name])) {
            return true;
        }
        $myMethod = $this->methods[$name] ?? $this->abstractMethods[$name];
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
        return false;
    }
}
