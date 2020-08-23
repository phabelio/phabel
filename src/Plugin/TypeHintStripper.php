<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Target\Php74\ArrowClosure;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\MagicConst\Function_ as MagicConstFunction_;
use PhpParser\Node\Scalar\MagicConst\Method;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Throw_;
use PhpParser\Node\UnionType;
use SplStack;

/**
 * Replace all usages of a certain type in typehints.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 */
class TypeHintStripper extends Plugin
{
    private const IGNORE_RETURN = 0;
    private const VOID_RETURN = 1;
    private const TYPE_RETURN = 2;
    /**
     * Stack.
     *
     * @template T as array{0: self::IGNORE_RETURN|self::VOID_RETURN}|array{0: self::TYPE_RETURN, 1: Node, 2: bool, 3: bool, 4: Node, 5: BooleanNot}
     *
     * @var SplStack<T>
     */
    private SplStack $stack;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->stack = new SplStack;
    }
    /**
     * Convert a function to a closure.
     */
    private function toClosure(FunctionLike &$func): void
    {
        if ($func instanceof ArrowFunction) {
            $func = ArrowClosure::enterClosure($func);
        }
    }
    /**
     * Generate.
     *
     * @param Variable            $var          Variable to check
     * @param (Name|Identifier)[] $types        Types to check
     * @param boolean             $fromNullable Whether this type is nullable
     *
     * @return array{0: bool, 1: Node, 2: BooleanNot} Whether the polyfilled gettype should be used, the error message, the condition
     */
    private function generateConditions(Variable $var, array $types, bool $fromNullable = false): array
    {
        /** @var bool Whether no explicit classes were referenced */
        $noOopTypes = true;
        /** @var string[] */
        $typeNames = [];
        /** @var Expr[] */
        $conditions = [];
        /** @var string Last string type name */
        $stringType = '';
        foreach ($types as $type) {
            $typeNames []= $type->toString();

            if ($type instanceof Identifier) {
                $typeName = $type->toLowerString();
                switch ($typeName) {
                    case 'callable':
                    case 'array':
                    case 'bool':
                    case 'float':
                    case 'int':
                    case 'object':
                    case 'string':
                    case 'resource':
                    case 'null':
                        $stringType = new String_($typeName === 'callable' ?
                            $typeName :
                            ($typeName === 'object' ? 'an object' : "of type $typeName"));
                        $conditions []= Plugin::call("is_$typeName", $var);
                        break;
                    case 'iterable':
                        $stringType = new String_('iterable');
                        $conditions []= new BooleanOr(
                            Plugin::call("is_array", $var),
                            new Instanceof_($var, new Name(\Traversable::class))
                        );
                        break;
                    default:
                        $noOopTypes = false;
                        $stringType = $type->isSpecialClassName() ?
                            new Concat(new String_("an instance of "), new ClassConstFetch(new Name($typeName), new Identifier('class'))) :
                            new String_("an instance of ".$type->toString());
                        $conditions []= new Instanceof_($var, new Name($typeName));
                }
            } else {
                $noOopTypes = false;
                $stringType = new String_("an instance of ".$type->toString());
                $conditions []= new Instanceof_($var, $type);
            }
        }
        if (\count($typeNames) > 1) {
            $stringType = new String_(\implode("|", $typeNames));
        }
        if ($fromNullable) {
            $stringType = new Concat($stringType, new String_(' or null'));
            $conditions []= Plugin::call("is_null", $var);
        }
        $initial = \array_shift($conditions);
        $condition = new BooleanNot(
            empty($conditions)
            ? $initial
            : \array_reduce($conditions, fn (Expr $a, Expr $b): BooleanOr => new BooleanOr($a, $b), $initial)
        );
        return [$noOopTypes, $stringType, $condition];
    }
    /**
     * Strip typehint.
     *
     * @param Variable                                    $var  Variable
     * @param null|Identifier|Name|NullableType|UnionType $type Type
     *
     * @return null|array{0: bool, 1: Node, 2: BooleanNot} Whether the polyfilled gettype should be used, the error message, the condition
     */
    private function strip(Variable $var, ?Node $type): ?array
    {
        if (!$type) {
            return null;
        }
        if ($type instanceof UnionType && $this->getConfig('union', false)) {
            return $this->generateConditions($var, $type->types);
        }
        if ($type instanceof NullableType && $this->getConfig('nullable', false)) {
            return $this->generateConditions($var, [$type->type], true);
        }
        $subType = $type instanceof NullableType ? $type->type : $type;
        if (\in_array($subType->toString(), $this->getConfig('types', []))) {
            return $this->generateConditions($var, [$subType], $type instanceof NullableType);
        }
        return null;
    }
    /**
     * Strip type hints from function.
     *
     * @param FunctionLike $func Function
     *
     * @return ?FunctionLike
     */
    public function enterFunction(FunctionLike $func, Context $ctx): ?FunctionLike
    {
        $functionName = new Method();
        if ($func instanceof ClassMethod) {
            /** @var ClassLike */
            $parent = $ctx->parents->top();
            if (!$parent->name) {
                $functionName = new Concat(new String_('class@anonymous:'), new MagicConstFunction_());
            }
        }
        $stmts = [];
        foreach ($func->getParams() as $index => $param) {
            if (!$condition = $this->strip($param->variadic ? new Variable('phabelVariadic') : $param->var, $param->type)) {
                break;
            }
            $param->type = null;
            [$noOop, $string, $condition] = $condition;
            $start = $param->variadic ? new Concat(new String_("Argument "), new Plus(new LNumber($index), new Variable('phabelVariadicIndex'))) : new String_("Argument $index");
            $start = new Concat($start, new String_(" passed to "));
            $start = new Concat($start, $functionName);
            $start = new Concat($start, new String_(" must be "));
            $start = new Concat($start, $string);
            $start = new Concat($start, new String_(", "));
            $start = new Concat($start, $noOop ? self::call('gettype', $param->var) : self::callPoly('gettype', $param->var));
            $start = new Concat($start, new String_(" given, called in "));
            $start = new Concat($start, self::callPoly('trace', new LNumber(0)));

            $if = new If_($condition, [new Throw_(new New_(new FullyQualified(\TypeError::class), [new Arg($start)]))]);
            if ($param->variadic) {
                $stmts []= new Foreach_($param->var, new Variable('phabelVariadic'), ['keyVar' => new Variable('phabelVariadicIndex'), 'stmts' => [$if]]);
            } else {
                $stmts []= $if;
            }
        }
        if ($stmts) {
            $this->toClosure($func);
            $func->stmts = \array_merge($stmts, $func->getStmts() ?? []);
        }

        if ($this->getConfig('void', false) && $func->getReturnType() instanceof Identifier && $func->getReturnType()->toLowerString() === 'void') {
            $this->toClosure($func);
            $this->stack->push([self::VOID_RETURN]);
        }
        $var = new Variable('phabelReturn');
        if (!$condition = $this->strip($var, $func->getReturnType())) {
            $this->stack->push([self::IGNORE_RETURN]);
            return null;
        }
        $this->toClosure($func);
        $this->stack->push([self::TYPE_RETURN, $functionName, $func->returnsByRef(), ...$condition]);
        return $func;
    }
    public function enterReturn(Return_ $return, Context $ctx): ?Node
    {
        $current = $this->stack->top();
        if ($current[0] === self::IGNORE_RETURN) {
            return null;
        }
        if ($current[0] === self::VOID_RETURN) {
            if ($return->expr !== null) {
                // This should be a transpilation error, wait for better stack traces before throwing here
                return new Throw_(new New_(new FullyQualified(\ParseError::class), [new String_("A void function must not return a value")]));
            }
            return null;
        }
        [, $functionName, $byRef, $noOop, $string, $condition] = $current;

        $var = new Variable('phabelReturn');
        $assign = $byRef ? new AssignRef($var, $return->expr ?? new Name('null')) : new Assign($var, $return->expr ?? new Name('null'));

        $start = new String_("Return value of");
        $start = new Concat($start, $functionName);
        $start = new Concat($start, new String_(" must be "));
        $start = new Concat($start, $string);
        $start = new Concat($start, new String_(", "));
        $start = new Concat($start, $noOop ? self::call('gettype', $var) : self::callPoly('gettype', $var));
        $start = new Concat($start, new String_(" returned in "));
        $start = new Concat($start, self::callPoly('trace', 0));

        $if = new If_($condition, [new Throw_(new New_(new FullyQualified(\TypeError::class), [new Arg($start)]))]);

        $return->expr = $var;

        $ctx->insertBefore($ctx->parents->top(), $assign, $if);
    }
    /**
     * Get trace string for errors.
     *
     * @param int $index Index
     *
     * @return string
     */
    public static function trace($index)
    {
        $trace = \debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[$index];
        return ($trace['file'] ?? '').' on line '.($trace['line'] ?? '');
    }
    /**
     * Get type string or object.
     *
     * @param mixed $object Object
     *
     * @return string
     */
    public static function gettype($object)
    {
        if (\is_object($object)) {
            $type = \get_class($object);
            return \str_starts_with($type, 'class@anonymous') ? 'instance of class@anonymous' : "instance of $type";
        }
        return \gettype($object);
    }

    /**
     * Runwithafter.
     *
     * @return array
     */
    public static function runWithAfter(): array
    {
        return [StringConcatOptimizer::class];
    }
}
