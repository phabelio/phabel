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
            if (!(\is_string($class) || \is_object($class) && \method_exists($class, '__toString') || (\is_bool($class) || \is_numeric($class)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($class) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($class) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $class = (string) $class;
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
                if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $name = (string) $name;
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
            if (!(\is_string($class) || \is_object($class) && \method_exists($class, '__toString') || (\is_bool($class) || \is_numeric($class)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($class) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($class) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $class = (string) $class;
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
            if (!(\is_string($method) || \is_object($method) && \method_exists($method, '__toString') || (\is_bool($method) || \is_numeric($method)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($method) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($method) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $method = (string) $method;
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
    public static function fromLiteral($data)
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
            if (!(\is_string($code) || \is_object($code) && \method_exists($code, '__toString') || (\is_bool($code) || \is_numeric($code)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($code) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($code) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $code = (string) $code;
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
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (bool) $phabelReturn;
            }
            return $phabelReturn;
        }
        if ($node->hasAttribute('hasSideEffects') || $node instanceof String_ || $node instanceof ArrayDimFetch || $node instanceof Assign || $node instanceof AssignOp || $node instanceof AssignRef || $node instanceof Clone_ || $node instanceof Eval_ || $node instanceof FuncCall || $node instanceof Include_ || $node instanceof List_ || $node instanceof MethodCall || $node instanceof New_ || $node instanceof NullsafeMethodCall || $node instanceof NullsafePropertyFetch || $node instanceof PostDec || $node instanceof PostInc || $node instanceof PreDec || $node instanceof PreInc || $node instanceof PropertyFetch || $node instanceof StaticCall || $node instanceof Yield_ || $node instanceof YieldFrom || $node instanceof ShellExec) {
            $node->setAttribute('hasSideEffects', true);
            $phabelReturn = true;
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (bool) $phabelReturn;
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
                        if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                            throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        }
                        $phabelReturn = (bool) $phabelReturn;
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
                            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                            }
                            $phabelReturn = (bool) $phabelReturn;
                        }
                        return $phabelReturn;
                    }
                }
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
    /**
     * Get fully qualified name.
     *
     * @param Node $node
     * @param class-string $alt Alternative name
     *
     * @return class-string
     */
    public static function getFqdn(Node $node, $alt = '')
    {
        if (!\is_string($alt)) {
            if (!(\is_string($alt) || \is_object($alt) && \method_exists($alt, '__toString') || (\is_bool($alt) || \is_numeric($alt)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($alt) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($alt) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $alt = (string) $alt;
        }
        if ($node instanceof FullyQualified) {
            $phabelReturn = (string) $node;
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (string) $phabelReturn;
            }
            return $phabelReturn;
        }
        if (isset($node->namespacedName)) {
            $phabelReturn = (string) $node->namespacedName;
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (string) $phabelReturn;
            }
            return $phabelReturn;
        }
        if (!$node->getAttribute('resolvedName')) {
            if ($alt) {
                $phabelReturn = $alt;
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (string) $phabelReturn;
                }
                return $phabelReturn;
            }
            throw new UnresolvedNameException();
        }
        $phabelReturn = (string) $node->getAttribute('resolvedName', $node->getAttribute('namespacedName'));
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
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
        if (!\is_object($obj)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($obj) must be of type object, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($obj) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($trait)) {
            if (!(\is_string($trait) || \is_object($trait) && \method_exists($trait, '__toString') || (\is_bool($trait) || \is_numeric($trait)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($trait) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($trait) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $trait = (string) $trait;
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
            $newObj = ($phabel_a2ec9a4ca43bd91b = $memoized["{$trait} {$extend}"]) || true ? new $phabel_a2ec9a4ca43bd91b() : false;
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
        if (!\is_object($obj)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($obj) must be of type object, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($obj) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($var)) {
            if (!(\is_string($var) || \is_object($var) && \method_exists($var, '__toString') || (\is_bool($var) || \is_numeric($var)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($var) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($var) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $var = (string) $var;
        }
        $phabelReturn = \Closure::bind(function () use ($var) {
            $phabelReturn = isset($this->{$var});
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (bool) $phabelReturn;
            }
            return $phabelReturn;
        }, $obj, \get_class($obj))->__invoke();
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
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
            if (!(\is_string($var) || \is_object($var) && \method_exists($var, '__toString') || (\is_bool($var) || \is_numeric($var)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($var) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($var) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $var = (string) $var;
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
            if (!(\is_string($var) || \is_object($var) && \method_exists($var, '__toString') || (\is_bool($var) || \is_numeric($var)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($var) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($var) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $var = (string) $var;
        }
        \Closure::bind(function () use ($var, &$val) {
            $this->{$var} =& $val;
        }, $obj, \get_class($obj))->__invoke();
    }
    /**
     * Adapted from https://gist.github.com/divinity76/01ef9ca99c111565a72d3a8a6e42f7fb
     * returns number of cpu cores
     * Copyleft 2018, license: WTFPL.
     * @throws \RuntimeException
     * @throws \LogicException
     * @psalm-suppress ForbiddenCode
     */
    public static function getCpuCount()
    {
        static $result = -1;
        if ($result !== -1) {
            $phabelReturn = $result;
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (int) $phabelReturn;
            }
            return $phabelReturn;
        }
        if (\defined('PHP_WINDOWS_VERSION_MAJOR')) {
            $phabelReturn = $result = 1;
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (int) $phabelReturn;
            }
            /*
            $str = trim((string) shell_exec('wmic cpu get NumberOfCores 2>&1'));
            if (!preg_match('/(\d+)/', $str, $matches)) {
                throw new \RuntimeException('wmic failed to get number of cpu cores on windows!');
            }
            return ((int) $matches [1]);
            */
            return $phabelReturn;
        }
        if (self::ini_get('pcre.jit') === '1' && \PHP_OS === 'Darwin' && \version_compare(\PHP_VERSION, '7.3.0') >= 0 && \version_compare(\PHP_VERSION, '7.4.0') < 0) {
            $phabelReturn = $result = 1;
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (int) $phabelReturn;
            }
            return $phabelReturn;
        }
        if (!\extension_loaded('pcntl')) {
            $phabelReturn = $result = 1;
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (int) $phabelReturn;
            }
            return $phabelReturn;
        }
        $has_nproc = \trim((string) @\shell_exec('command -v nproc'));
        if ($has_nproc) {
            $ret = @\shell_exec('nproc');
            if (\is_string($ret)) {
                $ret = \trim($ret);
                $tmp = \filter_var($ret, FILTER_VALIDATE_INT);
                if (\is_int($tmp)) {
                    $phabelReturn = $result = $tmp;
                    if (!\is_int($phabelReturn)) {
                        if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                            throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        }
                        $phabelReturn = (int) $phabelReturn;
                    }
                    return $phabelReturn;
                }
            }
        }
        $ret = @\shell_exec('sysctl -n hw.ncpu');
        if (\is_string($ret)) {
            $ret = \trim($ret);
            $tmp = \filter_var($ret, FILTER_VALIDATE_INT);
            if (\is_int($tmp)) {
                $phabelReturn = $result = $tmp;
                if (!\is_int($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (int) $phabelReturn;
                }
                return $phabelReturn;
            }
        }
        if (\is_readable('/proc/cpuinfo')) {
            $cpuinfo = \file_get_contents('/proc/cpuinfo');
            $count = \substr_count($cpuinfo, 'processor');
            if ($count > 0) {
                $phabelReturn = $result = $count;
                if (!\is_int($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (int) $phabelReturn;
                }
                return $phabelReturn;
            }
        }
        $phabelReturn = $result = 1;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (int) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Safely get value from php.ini.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function ini_get($key, $default = null)
    {
        if (!\is_string($key)) {
            if (!(\is_string($key) || \is_object($key) && \method_exists($key, '__toString') || (\is_bool($key) || \is_numeric($key)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($key) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($key) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $key = (string) $key;
        }
        try {
            if (\function_exists('ini_get')) {
                return @\ini_get($key);
            }
        } catch (\Exception $e) {
        } catch (\Error $e) {
        }
        return $default;
    }
}
