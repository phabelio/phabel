<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Target\Php70\AnonymousClass\AnonymousClassInterface;
use Phabel\Tools;
use Phabel\PhpParser\BuilderHelpers;
use Phabel\PhpParser\Node;
use Phabel\PhpParser\Node\Arg;
use Phabel\PhpParser\Node\Expr;
use Phabel\PhpParser\Node\Expr\Assign;
use Phabel\PhpParser\Node\Expr\AssignRef;
use Phabel\PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use Phabel\PhpParser\Node\Expr\BinaryOp\BooleanOr;
use Phabel\PhpParser\Node\Expr\BinaryOp\Concat;
use Phabel\PhpParser\Node\Expr\BinaryOp\Plus;
use Phabel\PhpParser\Node\Expr\BooleanNot;
use Phabel\PhpParser\Node\Expr\Cast;
use Phabel\PhpParser\Node\Expr\Cast\Bool_;
use Phabel\PhpParser\Node\Expr\Cast\Double;
use Phabel\PhpParser\Node\Expr\Cast\Int_;
use Phabel\PhpParser\Node\Expr\Cast\String_ as CastString_;
use Phabel\PhpParser\Node\Expr\ClassConstFetch;
use Phabel\PhpParser\Node\Expr\ConstFetch;
use Phabel\PhpParser\Node\Expr\Instanceof_;
use Phabel\PhpParser\Node\Expr\New_;
use Phabel\PhpParser\Node\Expr\Variable;
use Phabel\PhpParser\Node\FunctionLike;
use Phabel\PhpParser\Node\Identifier;
use Phabel\PhpParser\Node\Name;
use Phabel\PhpParser\Node\Name\FullyQualified;
use Phabel\PhpParser\Node\NullableType;
use Phabel\PhpParser\Node\Param;
use Phabel\PhpParser\Node\Scalar\LNumber;
use Phabel\PhpParser\Node\Scalar\MagicConst\Function_ as MagicConstFunction_;
use Phabel\PhpParser\Node\Scalar\MagicConst\Method;
use Phabel\PhpParser\Node\Scalar\String_;
use Phabel\PhpParser\Node\Stmt\Class_ as StmtClass_;
use Phabel\PhpParser\Node\Stmt\ClassLike;
use Phabel\PhpParser\Node\Stmt\ClassMethod;
use Phabel\PhpParser\Node\Stmt\Else_;
use Phabel\PhpParser\Node\Stmt\Expression;
use Phabel\PhpParser\Node\Stmt\Foreach_;
use Phabel\PhpParser\Node\Stmt\If_;
use Phabel\PhpParser\Node\Stmt\Interface_;
use Phabel\PhpParser\Node\Stmt\Return_;
use Phabel\PhpParser\Node\Stmt\Throw_;
use Phabel\PhpParser\Node\UnionType;
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
    const FORCE_ATTRIBUTE = 'TypeHintReplacer:force';
    const IGNORE_RETURN = 0;
    const VOID_RETURN = 1;
    const TYPE_RETURN = 2;
    /**
     * Stack.
     *
     * @var SplStack
     * @psalm-var SplStack<array{0: self::IGNORE_RETURN|self::VOID_RETURN}|array{0: self::TYPE_RETURN, 1: Node, 2: bool, 3: bool, 4: Node, 5: (callable(Node...): If_)}>
     */
    private $stack;
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @psalm-var SplStack<array{0: self::IGNORE_RETURN|self::VOID_RETURN}|array{0: self::TYPE_RETURN, 1: Node, 2: bool, 3: bool, 4: Node, 5: (callable(Node...): If_)}> */
        $this->stack = new SplStack();
    }
    /**
     * Replace typehint.
     *
     * @param null|Identifier|Name|NullableType|UnionType $type Type
     *
     * @return bool
     */
    public static function replace($type) : bool
    {
        if (!($type instanceof Node || \is_null($type))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($type) must be of type ?Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if ($type) {
            if ($type->getAttribute(self::FORCE_ATTRIBUTE, \false)) {
                return \false;
            }
            $type->setAttribute(self::FORCE_ATTRIBUTE, \true);
            return \true;
        }
        return \false;
    }
    /**
     * Return whether we replaced this typehint.
     *
     * @param null|Identifier|Name|NullableType|UnionType $type Type
     *
     * @return boolean
     */
    public static function replaced($type) : bool
    {
        if (!($type instanceof Node || \is_null($type))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($type) must be of type ?Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if ($type) {
            return $type->getAttribute(self::FORCE_ATTRIBUTE, \false);
        }
        return \true;
    }
    /**
     * Check if we should replace a void return type.
     *
     * @param Node|null $returnType
     * @return bool
     */
    private function checkVoid($returnType) : bool
    {
        if (!($returnType instanceof Node || \is_null($returnType))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($returnType) must be of type ?Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($returnType) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
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
    private function resolveClassName($type, $className) : Expr
    {
        if (!($className instanceof Expr || \is_null($className))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($className) must be of type ?Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($className) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $string = $type instanceof Identifier ? $type->toString() : $type->toCodeString();
        return $type->isSpecialClassName() ? $string === 'self' && $className ? $className : new ClassConstFetch(new Name($string), new Identifier('class')) : new String_($type->toString());
    }
    /**
     * Reduce multiple conditions to a single not.
     *
     * @param non-empty-list<Expr> $conditions
     * @return BooleanNot
     */
    private static function reduceConditions(array $conditions) : BooleanNot
    {
        $initial = \array_shift($conditions);
        return new BooleanNot(empty($conditions) ? $initial : \array_reduce($conditions, function (Expr $a, Expr $b) : BooleanOr {
            return new BooleanOr($a, $b);
        }, $initial));
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
    private function generateConditions(Variable $var, array $types, $className, bool $fromNullable = \false) : array
    {
        if (!($className instanceof Expr || \is_null($className))) {
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($className) must be of type ?Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($className) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
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
                            $conditions[] = [Plugin::call("is_{$typeName}", $var), new BooleanOr(Plugin::call("is_bool", $var), Plugin::call("is_numeric", $var)), $typeName === 'int' ? Int_::class : Double::class];
                        } elseif ($typeName === 'bool') {
                            $conditions[] = [Plugin::call("is_{$typeName}", $var), new BooleanOr(new BooleanOr(Plugin::call("is_bool", $var), Plugin::call("is_numeric", $var)), Plugin::call("is_string", $var)), Bool_::class];
                        } elseif ($typeName === 'string') {
                            $conditions[] = [Plugin::call("is_{$typeName}", $var), new BooleanOr(new BooleanOr(Plugin::call("is_string", $var), new BooleanAnd(Plugin::call("is_object", $var), Plugin::call("method_exists", $var, new String_('__toString')))), new BooleanOr(Plugin::call("is_bool", $var), Plugin::call("is_numeric", $var))), CastString_::class];
                        } else {
                            $conditions[] = Plugin::call("is_{$typeName}", $var);
                        }
                        if (\in_array($typeName, ['object', 'callable'])) {
                            $oopNames[] = $stringType;
                        } else {
                            $typeNames[] = $stringType;
                        }
                        break;
                    case 'iterable':
                        $stringType = new String_('iterable');
                        $conditions[] = new BooleanOr(Plugin::call("is_array", $var), new Instanceof_($var, new FullyQualified(\Traversable::class)));
                        $typeNames[] = $stringType;
                        break;
                    case 'mixed':
                        $stringType = new String_('mixed');
                        $conditions[] = Tools::fromLiteral(\true);
                        $oopNames[] = $stringType;
                        break;
                    default:
                        $stringType = $this->resolveClassName($type, $className);
                        $conditions[] = new Instanceof_($var, new Name($typeName));
                        $oopNames[] = $stringType;
                }
            } else {
                $stringType = $this->resolveClassName($type, $className);
                $conditions[] = new Instanceof_($var, $type);
                $oopNames[] = $stringType;
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
            $conditions[] = Plugin::call("is_null", $var);
        }
        $splitConditions = [];
        $currentConditions = [];
        foreach ($conditions as $condition) {
            if (\is_array($condition)) {
                if ($currentConditions) {
                    $currentConditions = $this->reduceConditions($currentConditions);
                    $splitConditions[] = function (Node ...$stmts) use($currentConditions) : If_ {
                        return new If_($currentConditions, ['stmts' => $stmts]);
                    };
                }
                $currentConditions = [];
                list($conditionsStrict, $conditionsLoose, $castLoose) = $condition;
                $conditionsStrict = new BooleanNot($conditionsStrict);
                $conditionsLoose = new BooleanNot($conditionsLoose);
                $splitConditions[] = function (Node ...$stmts) use($conditionsStrict, $conditionsLoose, $var, $castLoose) : If_ {
                    return new If_($conditionsStrict, ['stmts' => [new If_($conditionsLoose, ['stmts' => $stmts, 'else' => new Else_([new Expression(new Assign($var, new $castLoose($var)))])])]]);
                };
            } else {
                $currentConditions[] = $condition;
            }
        }
        if ($currentConditions) {
            $currentConditions = $this->reduceConditions($currentConditions);
            $splitConditions[] = function (Node ...$stmts) use($currentConditions) : If_ {
                return new If_($currentConditions, ['stmts' => $stmts]);
            };
        }
        return [$stringType, function (Node ...$expr) use($splitConditions) : If_ {
            $prev = $expr;
            foreach ($splitConditions as $func) {
                $prev = [$func(...$prev)];
            }
            return $prev[0];
        }];
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
    private function strip(Variable $var, $type, $className, bool $nullish, bool $force = \false)
    {
        if (!($type instanceof Node || \is_null($type))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($type) must be of type ?Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!($className instanceof Expr || \is_null($className))) {
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($className) must be of type ?Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($className) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!$type) {
            $phabelReturn = null;
            if (!(\is_array($phabelReturn) || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $force = $force || $type->getAttribute(self::FORCE_ATTRIBUTE, \false);
        if ($type instanceof UnionType) {
            if (!$this->getConfig('union', $force)) {
                $phabelReturn = null;
                if (!(\is_array($phabelReturn) || \is_null($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ?array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
            $phabelReturn = $this->generateConditions($var, $type->types, $className, $nullish);
            if (!(\is_array($phabelReturn) || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($type instanceof NullableType && $this->getConfig('nullable', $force)) {
            $phabelReturn = $this->generateConditions($var, [$type->type], $className, \true);
            if (!(\is_array($phabelReturn) || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $subType = $type instanceof NullableType ? $type->type : $type;
        if (\in_array($subType->toString(), $this->getConfig('types', [])) || $force) {
            $phabelReturn = $this->generateConditions($var, [$subType], $className, $nullish || $type instanceof NullableType);
            if (!(\is_array($phabelReturn) || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = null;
        if (!(\is_array($phabelReturn) || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Strip type hints from function.
     *
     * @param FunctionLike $func Function
     *
     * @return ?FunctionLike
     */
    public function enterFunction(FunctionLike $func, Context $ctx)
    {
        $functionName = new Method();
        $className = null;
        $returnType = $func->getReturnType();
        if ($func instanceof ClassMethod) {
            /** @var ClassLike */
            $parent = $ctx->parents->top();
            if ($parent instanceof Interface_ || $func->getStmts() === null) {
                foreach ($func->getParams() as $param) {
                    if ($this->strip(new Variable('phabelVariadic'), $param->type, null, \false)) {
                        $param->type = null;
                    }
                }
                if ($this->checkVoid($returnType)) {
                    $func->returnType = null;
                }
                if ($this->strip(new Variable('phabelReturn'), $returnType, null, \false, $this->getConfig('return', \false))) {
                    $func->returnType = null;
                }
                $this->stack->push([self::IGNORE_RETURN]);
                $phabelReturn = null;
                if (!($phabelReturn instanceof FunctionLike || \is_null($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ?FunctionLike, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
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
            if (!($condition = $this->strip($param->variadic ? new Variable('phabelVariadic') : $param->var, $param->type, $className, $nullish))) {
                continue;
            }
            $index++;
            $param->type = null;
            list($string, $condition) = $condition;
            $start = $param->variadic ? new Concat(new String_("(): Argument #"), new Plus(new LNumber($index), new Variable('phabelVariadicIndex'))) : new String_("(): Argument #{$index} (\$" . $param->var->name . ")");
            $start = new Concat($start, new String_(" must be of type "));
            $start = new Concat($start, $string);
            $start = new Concat($start, new String_(", "));
            $start = new Concat($start, self::callPoly('getDebugType', $param->var));
            $start = new Concat($start, new String_(" given, called in "));
            $start = new Concat($start, self::callPoly('trace'));
            $start = new Concat($functionName, $start);
            $if = $condition(new Throw_(new New_(new FullyQualified(\TypeError::class), [new Arg($start)])));
            if ($param->variadic) {
                $stmts[] = new Foreach_($param->var, new Variable('phabelVariadic'), ['keyVar' => new Variable('phabelVariadicIndex'), 'stmts' => [$if]]);
            } else {
                $stmts[] = $if;
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
            $phabelReturn = $func;
            if (!($phabelReturn instanceof FunctionLike || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?FunctionLike, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $var = new Variable('phabelReturn');
        if (!($condition = $this->strip($var, $returnType, $className, \false, $this->getConfig('return', \false)))) {
            $this->stack->push([self::IGNORE_RETURN]);
            $phabelReturn = $func;
            if (!($phabelReturn instanceof FunctionLike || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?FunctionLike, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $func->returnType = null;
        if (\Phabel\Plugin\GeneratorDetector::isGenerator($func)) {
            $this->stack->push([self::IGNORE_RETURN]);
            $phabelReturn = $func;
            if (!($phabelReturn instanceof FunctionLike || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?FunctionLike, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $ctx->toClosure($func);
        $this->stack->push(\array_merge([self::TYPE_RETURN, $functionName, $func->returnsByRef()], $condition));
        $stmts = $func->getStmts();
        $final = \end($stmts);
        if (!$final instanceof Return_) {
            list(, $string, $condition) = $condition;
            $start = new Concat($functionName, new String_("(): Return value must be of type "));
            $start = new Concat($start, $string);
            $start = new Concat($start, new String_(", none returned in "));
            $start = new Concat($start, self::callPoly('trace'));
            $throw = new Throw_(new New_(new FullyQualified(\TypeError::class), [new Arg($start)]));
            $func->stmts[] = $throw;
        }
        $phabelReturn = $func;
        if (!($phabelReturn instanceof FunctionLike || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?FunctionLike, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function enterReturn(Return_ $return, Context $ctx)
    {
        if ($this->stack->isEmpty()) {
            $phabelReturn = null;
            if (!($phabelReturn instanceof Node || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $current = $this->stack->top();
        if ($current[0] === self::IGNORE_RETURN) {
            $phabelReturn = null;
            if (!($phabelReturn instanceof Node || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($current[0] === self::VOID_RETURN) {
            if ($return->expr !== null) {
                $phabelReturn = new Throw_(new New_(new FullyQualified(\ParseError::class), [new String_("A void function must not return a value")]));
                if (!($phabelReturn instanceof Node || \is_null($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                // This should be a transpilation error, wait for better stack traces before throwing here
                return $phabelReturn;
            }
            $phabelReturn = null;
            if (!($phabelReturn instanceof Node || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        list(, $functionName, $byRef, $string, $condition) = $current;
        $var = new Variable('phabelReturn');
        $assign = new Expression($byRef && $return->expr ? new AssignRef($var, $return->expr) : new Assign($var, $return->expr ?? BuilderHelpers::normalizeValue(null)));
        $start = new Concat($functionName, new String_("(): Return value must be of type "));
        $start = new Concat($start, $string);
        $start = new Concat($start, new String_(", "));
        $start = new Concat($start, self::callPoly('getDebugType', $var));
        $start = new Concat($start, new String_(" returned in "));
        $start = new Concat($start, self::callPoly('trace'));
        $if = $condition(new Throw_(new New_(new FullyQualified(\TypeError::class), [new Arg($start)])));
        $return->expr = $var;
        $ctx->insertBefore($return, $assign, $if);
        $phabelReturn = null;
        if (!($phabelReturn instanceof Node || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function leaveFunc(FunctionLike $func)
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
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        return ($trace['file'] ?? '') . ' on line ' . ($trace['line'] ?? '');
    }
    /**
     * Get debug type.
     *
     * @param mixed $value
     * @return string
     */
    public static function getDebugType($value)
    {
        if (\Phabel\Target\Php72\Polyfill::is_object($value) && $value instanceof AnonymousClassInterface) {
            return $value::getPhabelOriginalName();
        }
        return \get_debug_type($value);
    }
    public static function next(array $config) : array
    {
        return [\Phabel\Plugin\StringConcatOptimizer::class];
    }
    public static function previous(array $config) : array
    {
        return [\Phabel\Plugin\GeneratorDetector::class];
    }
}
