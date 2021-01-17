<?php

namespace Phabel;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\Cast\String_;
use PhpParser\Node\Expr\Clone_;
use PhpParser\Node\Expr\Eval_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\PostDec;
use PhpParser\Node\Expr\PostInc;
use PhpParser\Node\Expr\PreDec;
use PhpParser\Node\Expr\PreInc;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\ShellExec;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Expression;
use PhpParser\ParserFactory;
use ReflectionClass;

/**
 * Various tools.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
abstract class Tools
{
    /**
     * Replace node of one type with another.
     *
     * @param Node   $node        Original node
     * @param string $class       Class of new node
     * @param array  $propertyMap Property map between old and new objects
     *
     * @psalm-param class-string<Node>    $class       Class of new node
     * @psalm-param array<string, string> $propertyMap Property map between old and new objects
     *
     * @psalm-suppress MissingClosureReturnType
     *
     * @return Node
     */
    public static function replaceType(Node $node, $class, array $propertyMap = [])
    {
        if (!\is_string($class)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($class) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($class) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if ($propertyMap) {
            $nodeNew = (new ReflectionClass($class))->newInstanceWithoutConstructor();
            foreach ($propertyMap as $old => $new) {
                $nodeNew->{$new} = $node->{$old};
            }
            $nodeNew->setAttributes($node->getAttributes());
            $phabelReturn = $nodeNew;
            if (!$phabelReturn instanceof Node) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = new $class(...\array_merge(\array_map(function ($name) use ($node) {
            if (!\is_string($name)) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $node->{$name};
        }, $node->getSubNodeNames()), [$node->getAttributes()]));
        if (!$phabelReturn instanceof Node) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Replace type in-place.
     *
     * @param Node   &$node       Original node
     * @param string $class       Class of new node
     * @param array  $propertyMap Property map between old and new objects
     *
     * @psalm-param class-string<Node> $class Class of new node
     * @psalm-param array<string, string> $propertyMap Property map between old and new objects
     *
     * @param-out Node &$node
     *
     * @return void
     */
    public static function replaceTypeInPlace(Node &$node, $class, array $propertyMap = [])
    {
        if (!\is_string($class)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($class) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($class) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $node = self::replaceType($node, $class, $propertyMap);
    }
    /**
     * Create variable assignment.
     *
     * @param Variable $name       Variable
     * @param Expr     $expression Expression
     *
     * @return Expression
     */
    public static function assign(Variable $name, Expr $expression)
    {
        $phabelReturn = new Expression(new Assign($name, $expression));
        if (!$phabelReturn instanceof Expression) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Expression, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Call function.
     *
     * @param array{0: class-string, 1: string}|callable-string $name          Function name
     * @param Expr|Arg                                          ...$parameters Parameters
     *
     * @return FuncCall|StaticCall
     *
     * @template T as array{0: class-string, 1: string}|callable-string
     * @psalm-param T $name
     *
     * @psalm-return (T is callable-string ? FuncCall : StaticCall)
     */
    public static function call($name, ...$parameters)
    {
        $parameters = \array_map(function ($data) {
            return $data instanceof Arg ? $data : new Arg($data);
        }, $parameters);
        return \is_array($name) ? new StaticCall(new FullyQualified($name[0]), $name[1], $parameters) : new FuncCall(new FullyQualified($name), $parameters);
    }
    /**
     * Call method of object.
     *
     * @param Expr     $name          Object name
     * @param string   $method        Method
     * @param Expr|Arg ...$parameters Parameters
     *
     * @return MethodCall
     */
    public static function callMethod(Expr $name, $method, ...$parameters)
    {
        if (!\is_string($method)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($method) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($method) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $parameters = \array_map(function ($data) {
            return $data instanceof Arg ? $data : new Arg($data);
        }, $parameters);
        $phabelReturn = new MethodCall($name, $method, $parameters);
        if (!$phabelReturn instanceof MethodCall) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type MethodCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Convert array, int or other literal to node.
     *
     * @param mixed $data Data to convert
     *
     * @return Node
     */
    public static function toLiteral($data)
    {
        $phabelReturn = self::toNode(\var_export($data, true) . ';');
        if (!$phabelReturn instanceof Node) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Convert code to node.
     *
     * @param string $code Code
     *
     * @memoize $code
     *
     * @return Node
     */
    public static function toNode($code)
    {
        if (!\is_string($code)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($code) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($code) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $res = (new ParserFactory())->create(ParserFactory::PREFER_PHP7)->parse('<?php ' . $code);
        if ($res === null || empty($res) || !$res[0] instanceof Expression || !isset($res[0]->expr)) {
            throw new \RuntimeException('Invalid code was provided!');
        }
        $phabelReturn = $res[0]->expr;
        if (!$phabelReturn instanceof Node) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Check if this node or any child node have any side effects (like calling other methods, or assigning variables).
     *
     * @param ?Expr $node Node
     *
     * @return bool
     */
    public static function hasSideEffects($node)
    {
        if (!($node instanceof Expr || \is_null($node))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($node) must be of type ?Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($node) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!$node) {
            $phabelReturn = false;
            if (!\is_bool($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($node->hasAttribute('hasSideEffects') || $node instanceof String_ || $node instanceof ArrayDimFetch || $node instanceof Assign || $node instanceof AssignOp || $node instanceof AssignRef || $node instanceof Clone_ || $node instanceof Eval_ || $node instanceof FuncCall || $node instanceof Include_ || $node instanceof List_ || $node instanceof MethodCall || $node instanceof New_ || $node instanceof NullsafeMethodCall || $node instanceof NullsafePropertyFetch || $node instanceof PostDec || $node instanceof PostInc || $node instanceof PreDec || $node instanceof PreInc || $node instanceof PropertyFetch || $node instanceof StaticCall || $node instanceof Yield_ || $node instanceof YieldFrom || $node instanceof ShellExec) {
            $node->setAttribute('hasSideEffects', true);
            $phabelReturn = true;
            if (!\is_bool($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        /** @var string */
        foreach ($node->getSubNodeNames() as $name) {
            if ($node->{$name} instanceof Expr) {
                if (self::hasSideEffects($node->{$name})) {
                    $node->setAttribute('hasSideEffects', true);
                    $phabelReturn = true;
                    if (!\is_bool($phabelReturn)) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    return $phabelReturn;
                }
            } elseif (\is_array($node->{$name})) {
                /** @var Node|Node[]|string */
                foreach ($node->{$name} as $var) {
                    if ($var instanceof Expr && self::hasSideEffects($var)) {
                        $node->setAttribute('hasSideEffects', true);
                        $phabelReturn = true;
                        if (!\is_bool($phabelReturn)) {
                            throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        }
                        return $phabelReturn;
                    }
                }
            }
        }
        $phabelReturn = false;
        if (!\is_bool($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Create a new object extended from this object, with the specified additional trait + interface.
     *
     * @param object $obj
     * @param string $trait
     *
     * @template T as object
     * @psalm-param T $obj
     *
     * @return object
     */
    public static function cloneWithTrait($obj, $trait)
    {
        if (!\is_string($trait)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($trait) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($trait) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_object($obj)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($obj) must be of type object, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($obj) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        /** @psalm-var int */
        static $count = 0;
        /** @psalm-var array<string, class-string<T>> $memoized */
        static $memoized = [];
        $reflect = new ReflectionClass($obj);
        $r = $reflect;
        while ($r && $r->isAnonymous()) {
            $r = $r->getParentClass();
        }
        $extend = "extends \\" . $r->getName();
        if (isset($memoized["{$trait} {$extend}"])) {
            /** @psalm-suppress MixedMethodCall */
            $newObj = ($phabel_f4436a511eb771f2 = $memoized["{$trait} {$extend}"]) || 1 ? new $phabel_f4436a511eb771f2() : 0;
        } else {
            $memoized["{$trait} {$extend}"] = "phabelTmpClass{$count}";
            $eval = "class phabelTmpClass{$count} {$extend} {\n                use \\{$trait};\n                public function __construct() {}\n            }\n            return new phabelTmpClass{$count};";
            $count++;
            /** @var object */
            $newObj = eval($eval);
        }
        $reflectNew = new ReflectionClass($newObj);
        do {
            if ($tmp = $reflectNew->getParentClass()) {
                $reflectNew = $tmp;
            }
            foreach ($reflect->getProperties() as $prop) {
                if ($reflectNew->hasProperty($prop->getName())) {
                    $propNew = $reflectNew->getProperty($prop->getName());
                    $propNew->setAccessible(true);
                    $prop->setAccessible(true);
                    $propNew->setValue($newObj, $prop->getValue($obj));
                }
            }
        } while ($reflect = $reflect->getParentClass());
        $phabelReturn = $newObj;
        if (!\is_object($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type object, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Checks private property exists in an object.
     *
     * @param object $obj Object
     * @param string $var Attribute name
     *
     * @psalm-suppress InvalidScope
     * @psalm-suppress PossiblyInvalidFunctionCall
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedPropertyFetch
     * @psalm-suppress MixedInferredReturnType
     *
     * @return bool
     * @access public
     */
    public static function hasVar($obj, $var)
    {
        if (!\is_string($var)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($var) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($var) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_object($obj)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($obj) must be of type object, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($obj) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $phabelReturn = \Closure::bind(function () use ($var) {
            $phabelReturn = isset($this->{$var});
            if (!\is_bool($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }, $obj, \get_class($obj))->__invoke();
        if (!\is_bool($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Accesses a private variable from an object.
     *
     * @param object $obj Object
     * @param string $var Attribute name
     *
     * @psalm-suppress InvalidScope
     * @psalm-suppress PossiblyInvalidFunctionCall
     * @psalm-suppress MixedPropertyFetch
     *
     * @return mixed
     * @access public
     */
    public static function &getVar($obj, $var)
    {
        if (!\is_string($var)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($var) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($var) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return \Closure::bind(
            /** @return mixed */
            function &() use ($var) {
                return $this->{$var};
            },
            $obj,
            \get_class($obj)
        )->__invoke();
    }
    /**
     * Sets a private variable in an object.
     *
     * @param object $obj Object
     * @param string $var Attribute name
     * @param mixed  $val Attribute value
     *
     * @psalm-suppress InvalidScope
     * @psalm-suppress PossiblyInvalidFunctionCall
     *
     * @return void
     *
     * @access public
     */
    public static function setVar($obj, $var, &$val)
    {
        if (!\is_string($var)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($var) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($var) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        \Closure::bind(function () use ($var, &$val) {
            $this->{$var} =& $val;
        }, $obj, \get_class($obj))->__invoke();
    }
}
