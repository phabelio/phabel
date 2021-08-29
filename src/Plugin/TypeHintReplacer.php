<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Target\Php70\AnonymousClass\AnonymousClassInterface;
use Phabel\Tools;
use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\Cast\Bool_;
use PhpParser\Node\Expr\Cast\Double;
use PhpParser\Node\Expr\Cast\Int_;
use PhpParser\Node\Expr\Cast\String_ as CastString_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
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
use PhpParser\Node\Stmt\Class_ as StmtClass_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Throw_;
use PhpParser\Node\UnionType;
use SplStack;

/**
 * Replace all usages of a certain type in typehints.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer extends Plugin
{
    /**
     * Force removal of specific typehint via node attribute.
     */
    private const FORCE_ATTRIBUTE = 'TypeHintReplacer:force';

    private const IGNORE_RETURN = 0;
    private const VOID_RETURN = 1;
    private const TYPE_RETURN = 2;
    /**
     * Stack.
     *
     * @var SplStack
     * @psalm-var SplStack<array{0: self::IGNORE_RETURN|self::VOID_RETURN}|array{0: self::TYPE_RETURN, 1: Node, 2: bool, 3: bool, 4: Node, 5: (callable(Node...): If_)}>
     */
    private SplStack $stack;
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @psalm-var SplStack<array{0: self::IGNORE_RETURN|self::VOID_RETURN}|array{0: self::TYPE_RETURN, 1: Node, 2: bool, 3: bool, 4: Node, 5: (callable(Node...): If_)}> */
        $this->stack = new SplStack;
    }
    /**
     * Replace typehint.
     *
     * @param null|Identifier|Name|NullableType|UnionType $type Type
     *
     * @return bool
     */
    public static function replace(?Node $type): bool
    {
        if ($type) {
            if ($type->getAttribute(self::FORCE_ATTRIBUTE, false)) {
                return false;
            }
            $type->setAttribute(self::FORCE_ATTRIBUTE, true);
            return true;
        }
        return false;
    }
    /**
     * Return whether we replaced this typehint.
     *
     * @param null|Identifier|Name|NullableType|UnionType $type Type
     *
     * @return boolean
     */
    public static function replaced(?Node $type): bool
    {
        if ($type) {
            return $type->getAttribute(self::FORCE_ATTRIBUTE, false);
        }
        return true;
    }
    /**
     * Check if we should replace a void return type.
     *
     * @param Node|null $returnType
     * @return bool
     */
    private function checkVoid(?Node $returnType): bool
    {
        return $returnType instanceof Identifier && $returnType->toLowerString() === 'void' && $this->getConfig('void', $this->getConfig('return', $returnType->getAttribute(self::FORCE_ATTRIBUTE)));
    }
    /**
     * Resolve special class name.
     *
     * @param Identifier|Name $type
     * @param ?Expr            $className
     *
     * @return Expr
     */
    private function resolveClassName($type, ?Expr $className): Expr
    {
        $string = $type instanceof Identifier ? $type->toString() : $type->toCodeString();
        return $type->isSpecialClassName() ?
            (
                $string === 'self' && $className
                ? $className
                : new ClassConstFetch(new Name($string), new Identifier('class'))
            ) : new String_($type->toString());
    }
    /**
     * Reduce multiple conditions to a single not.
     *
     * @param non-empty-list<Expr> $conditions
     * @return BooleanNot
     */
    private static function reduceConditions(array $conditions): BooleanNot
    {
        $initial = \array_shift($conditions);
        return new BooleanNot(
            empty($conditions)
            ? $initial
            : \array_reduce($conditions, fn (Expr $a, Expr $b): BooleanOr => new BooleanOr($a, $b), $initial)
        );
    }
    /**
     * Generate.
     *
     * @param Variable            $var          Variable to check
     * @param (Name|Identifier)[] $types        Types to check
     * @param ?Expr               $className    Whether the current class is anonymous
     * @param boolean             $fromNullable Whether this type is nullable
     *
     * @return array{0: Node, 1: (callable(Node...): If_)} Whether the polyfilled gettype should be used, the error message, the condition
     */
    private function generateConditions(Variable $var, array $types, ?Expr $className, bool $fromNullable = false): array
    {
        /** @var Expr[] */
        $typeNames = [];
        /** @var Expr[] */
        $oopNames = [];
        /** @var (Expr|array{0: Expr, 1: Expr, 2: class-string<Cast>})[] */
        $conditions = [];
        /** @var string Last string type name */
        $stringType = '';
        foreach ($types as $type) {
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
                        $stringType = new String_($typeName);
                        if ($typeName === 'int' || $typeName === 'float') {
                            $conditions []= [
                                Plugin::call("is_$typeName", $var),
                                new BooleanOr(
                                    Plugin::call("is_bool", $var),
                                    Plugin::call("is_numeric", $var),
                                ),
                                $typeName === 'int' ? Int_::class : Double::class,
                            ];
                        } elseif ($typeName === 'bool') {
                            $conditions []= [
                                Plugin::call("is_$typeName", $var),
                                new BooleanOr(
                                    new BooleanOr(
                                        Plugin::call("is_bool", $var),
                                        Plugin::call("is_numeric", $var),
                                    ),
                                    Plugin::call("is_string", $var),
                                ),
                                Bool_::class
                            ];
                        } elseif ($typeName === 'string') {
                            $conditions []= [
                                Plugin::call("is_$typeName", $var),
                                new BooleanOr(
                                    new BooleanOr(
                                        Plugin::call("is_string", $var),
                                        new BooleanAnd(
                                            Plugin::call("is_object", $var),
                                            Plugin::call("method_exists", $var, new String_('__toString')),
                                        ),
                                    ),
                                    new BooleanOr(
                                        Plugin::call("is_bool", $var),
                                        Plugin::call("is_numeric", $var),
                                    ),
                                ),
                                CastString_::class
                            ];
                        } else {
                            $conditions []= Plugin::call("is_$typeName", $var);
                        }
                        if (\in_array($typeName, ['object', 'callable'])) {
                            $oopNames []= $stringType;
                        } else {
                            $typeNames []= $stringType;
                        }
                        break;
                    case 'iterable':
                        $stringType = new String_('iterable');
                        $conditions []= new BooleanOr(
                            Plugin::call("is_array", $var),
                            new Instanceof_($var, new FullyQualified(\Traversable::class))
                        );
                        $typeNames []= $stringType;
                        break;
                    case 'mixed':
                        $stringType = new String_('mixed');
                        $conditions []= Tools::fromLiteral(true);
                        $oopNames []= $stringType;
                        break;
                    default:
                        $stringType = $this->resolveClassName($type, $className);
                        $conditions []= new Instanceof_($var, new Name($typeName));
                        $oopNames []= $stringType;
                }
            } else {
                $stringType = $this->resolveClassName($type, $className);
                $conditions []= new Instanceof_($var, $type);
                $oopNames []= $stringType;
            }
        }
        if (\count($typeNames) + \count($oopNames) > 1) {
            $typeNames = \array_merge($oopNames, $typeNames);
            $stringType = \array_shift($typeNames);
            foreach ($typeNames as $t) {
                $stringType = new Concat($stringType, new String_("|"));
                $stringType = new Concat($stringType, $t);
            }
        }
        if ($fromNullable) {
            $stringType = new Concat(new String_('?'), $stringType);
            $conditions []= Plugin::call("is_null", $var);
        }
        $splitConditions = [];
        $currentConditions = [];
        foreach ($conditions as $condition) {
            if (\is_array($condition)) {
                if ($currentConditions) {
                    $currentConditions = $this->reduceConditions($currentConditions);
                    $splitConditions []= fn (Node ...$stmts): If_ => new If_(
                        $currentConditions,
                        ['stmts' => $stmts]
                    );
                }
                $currentConditions = [];

                [$conditionsStrict, $conditionsLoose, $castLoose] = $condition;
                $conditionsStrict = new BooleanNot($conditionsStrict);
                $conditionsLoose = new BooleanNot($conditionsLoose);
                $splitConditions []= fn (Node ...$stmts): If_ => new If_(
                    $conditionsStrict,
                    ['stmts' => [
                        new If_(
                            $conditionsLoose,
                            [
                                'stmts' => $stmts,
                                'else' => new Else_([
                                    new Expression(new Assign($var, new $castLoose($var))),
                                ]),
                            ]
                        )
                    ]]
                );
            } else {
                $currentConditions []= $condition;
            }
        }
        if ($currentConditions) {
            $currentConditions = $this->reduceConditions($currentConditions);
            $splitConditions []= fn (Node ...$stmts): If_ => new If_(
                $currentConditions,
                ['stmts' => $stmts]
            );
        }
        return [
            $stringType,
            function (Node ...$expr) use ($splitConditions): If_ {
                $prev = $expr;
                foreach ($splitConditions as $func) {
                    $prev = [$func(...$prev)];
                }
                return $prev[0];
            }
        ];
    }
    /**
     * Strip typehint.
     *
     * @param Variable                                    $var       Variable
     * @param null|Identifier|Name|NullableType|UnionType $type      Type
     * @param ?Expr                                       $className Whether the current class is anonymous
     * @param bool                                        $force     Whether to force strip
     *
     * @return null|array{1: Node, 1: (callable(Node...): If_)} Whether the polyfilled gettype should be used, the error message, the condition
     */
    private function strip(Variable $var, ?Node $type, ?Expr $className, bool $nullish, bool $force = false): ?array
    {
        if (!$type) {
            return null;
        }
        $force = $force || $type->getAttribute(self::FORCE_ATTRIBUTE, false);
        if ($type instanceof UnionType) {
            if (!$this->getConfig('union', $force)) {
                return null;
            }
            return $this->generateConditions($var, $type->types, $className, $nullish);
        }
        if ($type instanceof NullableType && $this->getConfig('nullable', $force)) {
            return $this->generateConditions($var, [$type->type], $className, true);
        }
        $subType = $type instanceof NullableType ? $type->type : $type;
        if (\in_array($subType->toString(), $this->getConfig('types', [])) || $force) {
            return $this->generateConditions($var, [$subType], $className, $nullish || $type instanceof NullableType);
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
        $className = null;
        $returnType = $func->getReturnType();
        if ($func instanceof ClassMethod) {
            /** @var ClassLike */
            $parent = $ctx->parents->top();
            if ($parent instanceof Interface_ || $func->getStmts() === null) {
                foreach ($func->getParams() as $param) {
                    if ($this->strip(new Variable('phabelVariadic'), $param->type, null, false)) {
                        $param->type = null;
                    }
                }
                if ($this->checkVoid($returnType)) {
                    $func->returnType = null;
                }
                if ($this->strip(new Variable('phabelReturn'), $returnType, null, false, $this->getConfig('return', false))) {
                    $func->returnType = null;
                }
                $this->stack->push([self::IGNORE_RETURN]);
                return null;
            }
            if (!$parent->name) {
                /** @var StmtClass_ $parent */
                if ($parent->extends) {
                    $className = new ClassConstFetch($parent->extends, new Identifier('class'));
                }
                if (!$className) {
                    foreach ($parent->implements as $name) {
                        $className = new ClassConstFetch($name, new Identifier('class'));
                        break;
                    }
                }
                if ($className) {
                    $className = new Concat($className, new String_('@anonymous'));
                } else {
                    $className = new String_('class@anonymous');
                }
                $functionName = new Concat($className, new Concat(new String_(':'), new MagicConstFunction_()));
            }
        }
        $stmts = [];
        foreach ($func->getParams() as $index => $param) {
            $nullish = $param->default instanceof ConstFetch && $param->default->name->toLowerString() === 'null';
            if (!$condition = $this->strip($param->variadic ? new Variable('phabelVariadic') : $param->var, $param->type, $className, $nullish)) {
                continue;
            }
            $index++;

            $param->type = null;
            [$string, $condition] = $condition;
            $start = $param->variadic
                ? new Concat(new String_("(): Argument #"), new Plus(new LNumber($index), new Variable('phabelVariadicIndex')))
                : new String_("(): Argument #$index ($".$param->var->name.")");
            $start = new Concat($start, new String_(" must be of type "));
            $start = new Concat($start, $string);
            $start = new Concat($start, new String_(", "));
            $start = new Concat($start, self::callPoly('getDebugType', $param->var));
            $start = new Concat($start, new String_(" given, called in "));
            $start = new Concat($start, self::callPoly('trace'));

            $start = new Concat($functionName, $start);

            $if = $condition(new Throw_(new New_(new FullyQualified(\TypeError::class), [new Arg($start)])));
            if ($param->variadic) {
                $stmts []= new Foreach_($param->var, new Variable('phabelVariadic'), ['keyVar' => new Variable('phabelVariadicIndex'), 'stmts' => [$if]]);
            } else {
                $stmts []= $if;
            }
        }
        if ($stmts) {
            $ctx->toClosure($func);
            $func->stmts = \array_merge($stmts, $func->getStmts() ?? []);
        }

        if ($this->checkVoid($returnType)) {
            $ctx->toClosure($func);
            $this->stack->push([self::VOID_RETURN]);
            $func->returnType = null;
            return $func;
        }
        $var = new Variable('phabelReturn');
        if (!$condition = $this->strip($var, $returnType, $className, false, $this->getConfig('return', false))) {
            $this->stack->push([self::IGNORE_RETURN]);
            return $func;
        }
        $func->returnType = null;
        if (GeneratorDetector::isGenerator($func)) {
            $this->stack->push([self::IGNORE_RETURN]);
            return $func;
        }
        $ctx->toClosure($func);
        $this->stack->push([self::TYPE_RETURN, $functionName, $func->returnsByRef(), ...$condition]);

        $stmts = $func->getStmts();
        $final = \end($stmts);
        if (!$final instanceof Return_) {
            [, $string, $condition] = $condition;

            $start = new Concat($functionName, new String_("(): Return value must be of type "));
            $start = new Concat($start, $string);
            $start = new Concat($start, new String_(", none returned in "));
            $start = new Concat($start, self::callPoly('trace'));

            $throw = new Throw_(new New_(new FullyQualified(\TypeError::class), [new Arg($start)]));
            $func->stmts []= $throw;
        }

        return $func;
    }
    public function enterReturn(Return_ $return, Context $ctx): ?Node
    {
        if ($this->stack->isEmpty()) {
            return null;
        }
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
        [, $functionName, $byRef, $string, $condition] = $current;

        $var = new Variable('phabelReturn');
        $assign = new Expression($byRef && $return->expr ? new AssignRef($var, $return->expr) : new Assign($var, $return->expr ?? BuilderHelpers::normalizeValue(null)));

        $start = new Concat($functionName, new String_("(): Return value must be of type "));
        $start = new Concat($start, $string);
        $start = new Concat($start, new String_(", "));
        $start = new Concat($start, self::callPoly('getDebugType', $var));
        $start = new Concat($start, new String_(" returned in "));
        $start = new Concat($start, self::callPoly('trace'));

        $if = $condition(
            new Throw_(new New_(new FullyQualified(\TypeError::class), [new Arg($start)]))
        );

        $return->expr = $var;

        $ctx->insertBefore($return, $assign, $if);

        return null;
    }
    public function leaveFunc(FunctionLike $func): void
    {
        $this->stack->pop();
    }
    /**
     * Get trace string for errors.
     *
     * @return string
     */
    public static function trace()
    {
        $trace = \debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        return ($trace['file'] ?? '').' on line '.($trace['line'] ?? '');
    }
    /**
     * Get debug type.
     *
     * @param mixed $value
     * @return string
     */
    public static function getDebugType($value)
    {
        if (\is_object($value) && $value instanceof AnonymousClassInterface) {
            return $value::getPhabelOriginalName();
        }
        return \get_debug_type($value);
    }

    public static function next(array $config): array
    {
        return [StringConcatOptimizer::class];
    }
    public static function previous(array $config): array
    {
        return [GeneratorDetector::class];
    }
}
