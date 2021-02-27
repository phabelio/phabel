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
    public static function replaceType(Node $node, string $class, array $propertyMap = []): Node
    {
        if ($propertyMap) {
            $nodeNew = (new ReflectionClass($class))->newInstanceWithoutConstructor();
            foreach ($propertyMap as $old => $new) {
                $nodeNew->{$new} = $node->{$old};
            }
            $nodeNew->setAttributes($node->getAttributes());
            return $nodeNew;
        }
        return new $class(...[
            ...\array_map(fn (string $name) => $node->{$name}, $node->getSubNodeNames()),
            $node->getAttributes()
        ]);
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
    public static function replaceTypeInPlace(Node &$node, string $class, array $propertyMap = []): void
    {
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
    public static function assign(Variable $name, Expr $expression): Expression
    {
        return new Expression(
            new Assign(
                $name,
                $expression
            )
        );
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
        $parameters = \array_map(fn ($data) => $data instanceof Arg ? $data : new Arg($data), $parameters);
        return \is_array($name)
            ? new StaticCall(new FullyQualified($name[0]), $name[1], $parameters)
            : new FuncCall(new FullyQualified($name), $parameters);
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
    public static function callMethod(Expr $name, string $method, ...$parameters): MethodCall
    {
        $parameters = \array_map(fn ($data) => $data instanceof Arg ? $data : new Arg($data), $parameters);
        return new MethodCall($name, $method, $parameters);
    }
    /**
     * Convert array, int or other literal to node.
     *
     * @param mixed $data Data to convert
     *
     * @return Node
     */
    public static function toLiteral($data): Node
    {
        return self::toNode(\var_export($data, true).';');
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
    public static function toNode(string $code): Node
    {
        $res = (new ParserFactory)->create(ParserFactory::PREFER_PHP7)->parse('<?php '.$code);
        if ($res === null || empty($res) || !$res[0] instanceof Expression || !isset($res[0]->expr)) {
            throw new \RuntimeException('Invalid code was provided!');
        }
        return $res[0]->expr;
    }

    /**
     * Check if this node or any child node have any side effects (like calling other methods, or assigning variables).
     *
     * @param ?Expr $node Node
     *
     * @return bool
     */
    public static function hasSideEffects(?Expr $node): bool
    {
        if (!$node) {
            return false;
        }
        if ($node->hasAttribute('hasSideEffects')
            || $node instanceof String_       // __toString
            || $node instanceof ArrayDimFetch // offsetSet/offsetGet
            || $node instanceof Assign
            || $node instanceof AssignOp
            || $node instanceof AssignRef
            || $node instanceof Clone_        // __clone
            || $node instanceof Eval_
            || $node instanceof FuncCall
            || $node instanceof Include_
            || $node instanceof List_         // offsetGet/offsetSet
            || $node instanceof MethodCall
            || $node instanceof New_
            || $node instanceof NullsafeMethodCall
            || $node instanceof NullsafePropertyFetch
            || $node instanceof PostDec
            || $node instanceof PostInc
            || $node instanceof PreDec
            || $node instanceof PreInc
            || $node instanceof PropertyFetch
            || $node instanceof StaticCall
            || $node instanceof Yield_
            || $node instanceof YieldFrom
            || $node instanceof ShellExec
            ) {
            $node->setAttribute('hasSideEffects', true);
            return true;
        }
        /** @var string */
        foreach ($node->getSubNodeNames() as $name) {
            if ($node->{$name} instanceof Expr) {
                if (self::hasSideEffects($node->{$name})) {
                    $node->setAttribute('hasSideEffects', true);
                    return true;
                }
            } elseif (\is_array($node->{$name})) {
                /** @var Node|Node[]|string */
                foreach ($node->{$name} as $var) {
                    if ($var instanceof Expr && self::hasSideEffects($var)) {
                        $node->setAttribute('hasSideEffects', true);
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Get fully qualified name
     *
     * @param Node $node
     * @return class-string
     */
    public static function getFqdn(Node $node): string
    {
        if ($node instanceof FullyQualified) {
            return (string) $node;
        }
        if (isset($node->namespacedName)) {
            return (string) $node->namespacedName;
        }
        if (!$node->getAttribute('resolvedName')) {
            throw new \RuntimeException('Cannot obtain FQDN from unresolved name!');
        }
        return (string) $node->getAttribute('resolvedName', $node->getAttribute('namespacedName'));
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
    public static function cloneWithTrait(object $obj, string $trait): object
    {
        /** @psalm-var int */
        static $count = 0;
        /** @psalm-var array<string, class-string<T>> $memoized */
        static $memoized = [];

        $reflect = new ReflectionClass($obj);


        $r = $reflect;
        while ($r && $r->isAnonymous()) {
            $r = $r->getParentClass();
        }

        $extend = "extends \\".$r->getName();
        if (isset($memoized["$trait $extend"])) {
            /** @psalm-suppress MixedMethodCall */
            $newObj = new $memoized["$trait $extend"];
        } else {
            $memoized["$trait $extend"] = "phabelTmpClass$count";
            $eval = "class phabelTmpClass$count $extend {
                use \\$trait;
                public function __construct() {}
            }
            return new phabelTmpClass$count;";
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

        return $newObj;
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
    public static function hasVar(object $obj, string $var): bool
    {
        return \Closure::bind(
            function () use ($var): bool {
                return isset($this->{$var});
            },
            $obj,
            \get_class($obj)
        )->__invoke();
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
    public static function &getVar($obj, string $var)
    {
        return \Closure::bind(
            /** @return mixed */
            function & () use ($var) {
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
    public static function setVar($obj, string $var, &$val): void
    {
        \Closure::bind(
            function () use ($var, &$val) {
                $this->{$var} =& $val;
            },
            $obj,
            \get_class($obj)
        )->__invoke();
    }
}
