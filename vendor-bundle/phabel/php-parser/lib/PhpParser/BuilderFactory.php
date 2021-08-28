<?php

namespace Phabel\PhpParser;

use Phabel\PhpParser\Node\Arg;
use Phabel\PhpParser\Node\Expr;
use Phabel\PhpParser\Node\Expr\BinaryOp\Concat;
use Phabel\PhpParser\Node\Identifier;
use Phabel\PhpParser\Node\Name;
use Phabel\PhpParser\Node\Scalar\String_;
use Phabel\PhpParser\Node\Stmt\Use_;
class BuilderFactory
{
    /**
     * Creates a namespace builder.
     *
     * @param null|string|Node\Name $name Name of the namespace
     *
     * @return Builder\Namespace_ The created namespace builder
     */
    public function namespace($name)
    {
        $phabelReturn = new Builder\Namespace_($name);
        if (!$phabelReturn instanceof Builder\Namespace_) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Builder\\Namespace_, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a class builder.
     *
     * @param string $name Name of the class
     *
     * @return Builder\Class_ The created class builder
     */
    public function class($name)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        $phabelReturn = new Builder\Class_($name);
        if (!$phabelReturn instanceof Builder\Class_) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Builder\\Class_, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates an interface builder.
     *
     * @param string $name Name of the interface
     *
     * @return Builder\Interface_ The created interface builder
     */
    public function interface($name)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        $phabelReturn = new Builder\Interface_($name);
        if (!$phabelReturn instanceof Builder\Interface_) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Builder\\Interface_, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a trait builder.
     *
     * @param string $name Name of the trait
     *
     * @return Builder\Trait_ The created trait builder
     */
    public function trait($name)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        $phabelReturn = new Builder\Trait_($name);
        if (!$phabelReturn instanceof Builder\Trait_) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Builder\\Trait_, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a trait use builder.
     *
     * @param Node\Name|string ...$traits Trait names
     *
     * @return Builder\TraitUse The create trait use builder
     */
    public function useTrait(...$traits)
    {
        $phabelReturn = new Builder\TraitUse(...$traits);
        if (!$phabelReturn instanceof Builder\TraitUse) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Builder\\TraitUse, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a trait use adaptation builder.
     *
     * @param Node\Name|string|null  $trait  Trait name
     * @param Node\Identifier|string $method Method name
     *
     * @return Builder\TraitUseAdaptation The create trait use adaptation builder
     */
    public function traitUseAdaptation($trait, $method = null)
    {
        if ($method === null) {
            $method = $trait;
            $trait = null;
        }
        $phabelReturn = new Builder\TraitUseAdaptation($trait, $method);
        if (!$phabelReturn instanceof Builder\TraitUseAdaptation) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Builder\\TraitUseAdaptation, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a method builder.
     *
     * @param string $name Name of the method
     *
     * @return Builder\Method The created method builder
     */
    public function method($name)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        $phabelReturn = new Builder\Method($name);
        if (!$phabelReturn instanceof Builder\Method) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Builder\\Method, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a parameter builder.
     *
     * @param string $name Name of the parameter
     *
     * @return Builder\Param The created parameter builder
     */
    public function param($name)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        $phabelReturn = new Builder\Param($name);
        if (!$phabelReturn instanceof Builder\Param) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Builder\\Param, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a property builder.
     *
     * @param string $name Name of the property
     *
     * @return Builder\Property The created property builder
     */
    public function property($name)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        $phabelReturn = new Builder\Property($name);
        if (!$phabelReturn instanceof Builder\Property) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Builder\\Property, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a function builder.
     *
     * @param string $name Name of the function
     *
     * @return Builder\Function_ The created function builder
     */
    public function function($name)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        $phabelReturn = new Builder\Function_($name);
        if (!$phabelReturn instanceof Builder\Function_) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Builder\\Function_, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a namespace/class use builder.
     *
     * @param Node\Name|string $name Name of the entity (namespace or class) to alias
     *
     * @return Builder\Use_ The created use builder
     */
    public function use_($name)
    {
        $phabelReturn = new Builder\Use_($name, Use_::TYPE_NORMAL);
        if (!$phabelReturn instanceof Builder\Use_) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Builder\\Use_, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a function use builder.
     *
     * @param Node\Name|string $name Name of the function to alias
     *
     * @return Builder\Use_ The created use function builder
     */
    public function useFunction($name)
    {
        $phabelReturn = new Builder\Use_($name, Use_::TYPE_FUNCTION);
        if (!$phabelReturn instanceof Builder\Use_) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Builder\\Use_, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a constant use builder.
     *
     * @param Node\Name|string $name Name of the const to alias
     *
     * @return Builder\Use_ The created use const builder
     */
    public function useConst($name)
    {
        $phabelReturn = new Builder\Use_($name, Use_::TYPE_CONSTANT);
        if (!$phabelReturn instanceof Builder\Use_) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Builder\\Use_, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates node a for a literal value.
     *
     * @param Expr|bool|null|int|float|string|array $value $value
     *
     * @return Expr
     */
    public function val($value)
    {
        $phabelReturn = BuilderHelpers::normalizeValue($value);
        if (!$phabelReturn instanceof Expr) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates variable node.
     *
     * @param string|Expr $name Name
     *
     * @return Expr\Variable
     */
    public function var($name)
    {
        if (!\is_string($name) && !$name instanceof Expr) {
            throw new \LogicException('Variable name must be string or Expr');
        }
        $phabelReturn = new Expr\Variable($name);
        if (!$phabelReturn instanceof Expr\Variable) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr\\Variable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Normalizes an argument list.
     *
     * Creates Arg nodes for all arguments and converts literal values to expressions.
     *
     * @param array $args List of arguments to normalize
     *
     * @return Arg[]
     */
    public function args(array $args)
    {
        $normalizedArgs = [];
        foreach ($args as $arg) {
            if ($arg instanceof Arg) {
                $normalizedArgs[] = $arg;
            } else {
                $normalizedArgs[] = new Arg(BuilderHelpers::normalizeValue($arg));
            }
        }
        $phabelReturn = $normalizedArgs;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a function call node.
     *
     * @param string|Name|Expr $name Function name
     * @param array            $args Function arguments
     *
     * @return Expr\FuncCall
     */
    public function funcCall($name, array $args = [])
    {
        $phabelReturn = new Expr\FuncCall(BuilderHelpers::normalizeNameOrExpr($name), $this->args($args));
        if (!$phabelReturn instanceof Expr\FuncCall) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr\\FuncCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a method call node.
     *
     * @param Expr                   $var  Variable the method is called on
     * @param string|Identifier|Expr $name Method name
     * @param array                  $args Method arguments
     *
     * @return Expr\MethodCall
     */
    public function methodCall(Expr $var, $name, array $args = [])
    {
        $phabelReturn = new Expr\MethodCall($var, BuilderHelpers::normalizeIdentifierOrExpr($name), $this->args($args));
        if (!$phabelReturn instanceof Expr\MethodCall) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr\\MethodCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a static method call node.
     *
     * @param string|Name|Expr       $class Class name
     * @param string|Identifier|Expr $name  Method name
     * @param array                  $args  Method arguments
     *
     * @return Expr\StaticCall
     */
    public function staticCall($class, $name, array $args = [])
    {
        $phabelReturn = new Expr\StaticCall(BuilderHelpers::normalizeNameOrExpr($class), BuilderHelpers::normalizeIdentifierOrExpr($name), $this->args($args));
        if (!$phabelReturn instanceof Expr\StaticCall) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr\\StaticCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates an object creation node.
     *
     * @param string|Name|Expr $class Class name
     * @param array            $args  Constructor arguments
     *
     * @return Expr\New_
     */
    public function new($class, array $args = [])
    {
        $phabelReturn = new Expr\New_(BuilderHelpers::normalizeNameOrExpr($class), $this->args($args));
        if (!$phabelReturn instanceof Expr\New_) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr\\New_, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a constant fetch node.
     *
     * @param string|Name $name Constant name
     *
     * @return Expr\ConstFetch
     */
    public function constFetch($name)
    {
        $phabelReturn = new Expr\ConstFetch(BuilderHelpers::normalizeName($name));
        if (!$phabelReturn instanceof Expr\ConstFetch) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr\\ConstFetch, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a property fetch node.
     *
     * @param Expr                   $var  Variable holding object
     * @param string|Identifier|Expr $name Property name
     *
     * @return Expr\PropertyFetch
     */
    public function propertyFetch(Expr $var, $name)
    {
        $phabelReturn = new Expr\PropertyFetch($var, BuilderHelpers::normalizeIdentifierOrExpr($name));
        if (!$phabelReturn instanceof Expr\PropertyFetch) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr\\PropertyFetch, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a class constant fetch node.
     *
     * @param string|Name|Expr  $class Class name
     * @param string|Identifier $name  Constant name
     *
     * @return Expr\ClassConstFetch
     */
    public function classConstFetch($class, $name)
    {
        $phabelReturn = new Expr\ClassConstFetch(BuilderHelpers::normalizeNameOrExpr($class), BuilderHelpers::normalizeIdentifier($name));
        if (!$phabelReturn instanceof Expr\ClassConstFetch) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr\\ClassConstFetch, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates nested Concat nodes from a list of expressions.
     *
     * @param Expr|string ...$exprs Expressions or literal strings
     *
     * @return Concat
     */
    public function concat(...$exprs)
    {
        $numExprs = \count($exprs);
        if ($numExprs < 2) {
            throw new \LogicException('Expected at least two expressions');
        }
        $lastConcat = $this->normalizeStringExpr($exprs[0]);
        for ($i = 1; $i < $numExprs; $i++) {
            $lastConcat = new Concat($lastConcat, $this->normalizeStringExpr($exprs[$i]));
        }
        $phabelReturn = $lastConcat;
        if (!$phabelReturn instanceof Concat) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Concat, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param string|Expr $expr
     * @return Expr
     */
    private function normalizeStringExpr($expr)
    {
        if ($expr instanceof Expr) {
            $phabelReturn = $expr;
            if (!$phabelReturn instanceof Expr) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (\is_string($expr)) {
            $phabelReturn = new String_($expr);
            if (!$phabelReturn instanceof Expr) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        throw new \LogicException('Expected string or Expr');
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
}
