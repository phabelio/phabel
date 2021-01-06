<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\VarLikeIdentifier;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;

/**
 * Replace nested expressions in isset.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class IssetExpressionFixer extends Plugin
{
    /**
     * Recursively extract bottom ArrayDimFetch.
     *
     * @param Node $var
     * @return Node
     */
    private static function &extractWorkVar(Node &$var): Node
    {
        if ($var instanceof ArrayDimFetch && $var->var instanceof ArrayDimFetch) {
            return self::extractWorkVar($var->var);
        }
        return $var;
    }
    /**
     * Wrap boolean isset check.
     *
     * @param Expr $node Node
     *
     * @return ArrayDimFetch
     */
    private static function wrapBoolean(Expr $node): ArrayDimFetch
    {
        return new ArrayDimFetch(
            self::callPoly(
                'returnMe',
                new Ternary(
                    $node,
                    self::toLiteral([0]),
                    self::toLiteral([]),
                )
            ),
            new LNumber(0)
        );
    }

    public function enter(Isset_ $isset): void
    {
        foreach ($isset->vars as $key => &$var) {
            /** @var array<string, array<class-string<Expr>, true>> */
            $subNodes = $this->getConfig(\get_class($var), false);
            if (!$subNodes) {
                continue;
            }
            $workVar = &$this->extractWorkVar($var);
            $needsFixing = false;

            foreach ($subNodes as $key => $types) {
                if (isset($types[self::getClass($workVar->{$key} ?? '')])) {
                    $needsFixing = true;
                    break;
                }
            }
            if (!$needsFixing) {
                continue;
            }
            switch ($class = \get_class($workVar)) {
                case ArrayDimFetch::class:
                case PropertyFetch::class:
                    $workVar->var = self::callPoly('returnMe', $workVar->var);
                    break;
                case StaticPropertyFetch::class:
                    $workVar = $this->wrapBoolean(self::callPoly(
                        'staticExists',
                        $workVar->class,
                        $workVar->name instanceof VarLikeIdentifier ? new String_($workVar->name->name) : $workVar->name,
                        new LNumber(1)
                    ));
                    break;
                case ClassConstFetch::class:
                    $workVar = $this->wrapBoolean(self::callPoly(
                        'staticExists',
                        $workVar->class,
                        new String_($workVar->name->name),
                        new LNumber(0)
                    ));
                    break;
                case Variable::class:
                    $workVar->name = self::callPoly('returnMe', $workVar->name);
                    break;
                default:
                    throw new \RuntimeException("Trying to fix unknown isset expression $class");
            }
        }
    }

    /**
     * Returns the data provided.
     *
     * @param mixed $data Data
     *
     * @return mixed
     *
     * @template T
     *
     * @psalm-param T $data data
     *
     * @psalm-return T
     */
    public static function returnMe($data)
    {
        return $data;
    }

    /**
     * Get name of class.
     *
     * @param class-string|object $class Class
     *
     * @return class-string
     */
    public static function getClass($class): string
    {
        return \is_string($class) ? $class : \get_class($class);
    }

    /**
     * Check if static property is set.
     *
     * @param class-string|object $class              Class
     * @param string              $property           Property name
     * @param boolean             $propertyOrConstant Whether to fetch the property or the constant
     *
     * @return boolean
     */
    public static function staticExists($class, string $property, bool $propertyOrConstant): bool
    {
        $reflectionClass = new ReflectionClass($class);
        $class = self::getClass($class);
        if ($propertyOrConstant) {
            try {
                $reflection = $reflectionClass->getProperty($property);
            } catch (ReflectionException $e) {
                return false;
            }
        } elseif (PHP_VERSION_ID >= 70100) {
            try {
                $reflection = new ReflectionClassConstant($class, $property);
            } catch (ReflectionException $e) {
                return false;
            }
        } else {
            return isset($reflectionClass->getConstants()[$property]);
        }

        $classCaller = \debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['class'] ?? '';
        $allowProtected = false;
        $allowPrivate = false;
        if ($classCaller) {
            if ($class === $classCaller) {
                $allowProtected = $allowPrivate = true;
            } elseif ($reflectionClass->isSubclassOf($classCaller) || (new ReflectionClass($classCaller))->isSubclassOf($class)) {
                $allowProtected = true;
            }
        }
        if ($reflection->isPrivate()) {
            return $allowPrivate ? $reflection->getValue() !== null : false;
        }
        if ($reflection->isProtected()) {
            return $allowProtected ? $reflection->getValue() !== null : false;
        }
        return $reflection->getValue() !== null;
    }
}
