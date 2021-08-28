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
    private static function &extractWorkVar(Node &$var)
    {
        if ($var instanceof ArrayDimFetch && $var->var instanceof ArrayDimFetch) {
            $phabelReturn =& self::extractWorkVar($var->var);
            if (!$phabelReturn instanceof Node) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn =& $var;
        if (!$phabelReturn instanceof Node) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Wrap boolean isset check.
     *
     * @param Expr $node Node
     *
     * @return ArrayDimFetch
     */
    private static function wrapBoolean(Expr $node)
    {
        $phabelReturn = new ArrayDimFetch(self::callPoly('returnMe', new Ternary($node, self::fromLiteral([0]), self::fromLiteral([]))), new LNumber(0));
        if (!$phabelReturn instanceof ArrayDimFetch) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ArrayDimFetch, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function enter(Isset_ $isset)
    {
        foreach ($isset->vars as $key => &$var) {
            /** @var array<string, array<class-string<Expr>, true>> */
            $subNodes = $this->getConfig(\get_class($var), false);
            if (!$subNodes) {
                continue;
            }
            $workVar =& $this->extractWorkVar($var);
            $needsFixing = false;
            foreach ($subNodes as $key => $types) {
                if (isset($types[self::getClass(isset($workVar->{$key}) ? $workVar->{$key} : '')])) {
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
                    if ($key === 'dim') {
                        $workVar->dim = self::callPoly('returnMe', $workVar->dim);
                    } else {
                        $workVar->var = self::callPoly('returnMe', $workVar->var);
                    }
                    break;
                case StaticPropertyFetch::class:
                    $workVar = $this->wrapBoolean(self::callPoly('staticExists', $workVar->class, $workVar->name instanceof VarLikeIdentifier ? new String_($workVar->name->name) : $workVar->name, self::fromLiteral(true)));
                    break;
                case ClassConstFetch::class:
                    $workVar = $this->wrapBoolean(self::callPoly('staticExists', $workVar->class, new String_($workVar->name->name), self::fromLiteral(false)));
                    break;
                case Variable::class:
                    $workVar->name = self::callPoly('returnMe', $workVar->name);
                    break;
                default:
                    throw new \RuntimeException("Trying to fix unknown isset expression {$class}");
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
    public static function getClass($class)
    {
        $phabelReturn = \is_string($class) ? $class : \get_class($class);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
        }
        return $phabelReturn;
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
    public static function staticExists($class, $property, $propertyOrConstant)
    {
        if (!\is_string($property)) {
            if (!(\is_string($property) || \is_object($property) && \method_exists($property, '__toString') || (\is_bool($property) || \is_numeric($property)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($property) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($property) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $property = (string) $property;
        }
        if (!\is_bool($propertyOrConstant)) {
            if (!(\is_bool($propertyOrConstant) || \is_numeric($propertyOrConstant) || \is_string($propertyOrConstant))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($propertyOrConstant) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($propertyOrConstant) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $propertyOrConstant = (bool) $propertyOrConstant;
        }
        $reflectionClass = new ReflectionClass($class);
        $class = self::getClass($class);
        if ($propertyOrConstant) {
            try {
                $reflection = $reflectionClass->getProperty($property);
            } catch (ReflectionException $e) {
                $phabelReturn = false;
                if (!\is_bool($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (bool) $phabelReturn;
                }
                return $phabelReturn;
            }
        } elseif (PHP_VERSION_ID >= 70100) {
            try {
                $reflection = new ReflectionClassConstant($class, $property);
            } catch (ReflectionException $e) {
                $phabelReturn = false;
                if (!\is_bool($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (bool) $phabelReturn;
                }
                return $phabelReturn;
            }
        } else {
            $phabelReturn = isset($reflectionClass->getConstants()[$property]);
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (bool) $phabelReturn;
            }
            return $phabelReturn;
        }
        $classCaller = null !== ($phabel_698a1ed91fd0737e = \debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)) && isset($phabel_698a1ed91fd0737e[1]['class']) ? $phabel_698a1ed91fd0737e[1]['class'] : '';
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
            $phabelReturn = $allowPrivate ? $reflection->getValue() !== null : false;
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (bool) $phabelReturn;
            }
            return $phabelReturn;
        }
        if ($reflection->isProtected()) {
            $phabelReturn = $allowProtected ? $reflection->getValue() !== null : false;
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (bool) $phabelReturn;
            }
            return $phabelReturn;
        }
        $phabelReturn = $reflection->getValue() !== null;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
}
