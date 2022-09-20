<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Target\Php70\AnonymousClass\AnonymousClassInterface;
use Phabel\Tools;
use PhabelVendor\PhpParser\BuilderHelpers;
use PhabelVendor\PhpParser\Comment\Doc;
use PhabelVendor\PhpParser\Node;
use PhabelVendor\PhpParser\Node\Arg;
use PhabelVendor\PhpParser\Node\Expr;
use PhabelVendor\PhpParser\Node\Expr\Assign;
use PhabelVendor\PhpParser\Node\Expr\AssignRef;
use PhabelVendor\PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhabelVendor\PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhabelVendor\PhpParser\Node\Expr\BinaryOp\Concat;
use PhabelVendor\PhpParser\Node\Expr\BinaryOp\Identical;
use PhabelVendor\PhpParser\Node\Expr\BinaryOp\Plus;
use PhabelVendor\PhpParser\Node\Expr\BooleanNot;
use PhabelVendor\PhpParser\Node\Expr\Cast;
use PhabelVendor\PhpParser\Node\Expr\Cast\Bool_;
use PhabelVendor\PhpParser\Node\Expr\Cast\Double;
use PhabelVendor\PhpParser\Node\Expr\Cast\Int_;
use PhabelVendor\PhpParser\Node\Expr\Cast\String_ as CastString_;
use PhabelVendor\PhpParser\Node\Expr\ClassConstFetch;
use PhabelVendor\PhpParser\Node\Expr\ConstFetch;
use PhabelVendor\PhpParser\Node\Expr\Instanceof_;
use PhabelVendor\PhpParser\Node\Expr\New_;
use PhabelVendor\PhpParser\Node\Expr\PropertyFetch;
use PhabelVendor\PhpParser\Node\Expr\Variable;
use PhabelVendor\PhpParser\Node\FunctionLike;
use PhabelVendor\PhpParser\Node\Identifier;
use PhabelVendor\PhpParser\Node\Name;
use PhabelVendor\PhpParser\Node\Name\FullyQualified;
use PhabelVendor\PhpParser\Node\NullableType;
use PhabelVendor\PhpParser\Node\Param;
use PhabelVendor\PhpParser\Node\Scalar\LNumber;
use PhabelVendor\PhpParser\Node\Scalar\MagicConst\Function_ as MagicConstFunction_;
use PhabelVendor\PhpParser\Node\Scalar\MagicConst\Method;
use PhabelVendor\PhpParser\Node\Scalar\String_;
use PhabelVendor\PhpParser\Node\Stmt\Class_ as StmtClass_;
use PhabelVendor\PhpParser\Node\Stmt\ClassLike;
use PhabelVendor\PhpParser\Node\Stmt\ClassMethod;
use PhabelVendor\PhpParser\Node\Stmt\Else_;
use PhabelVendor\PhpParser\Node\Stmt\Expression;
use PhabelVendor\PhpParser\Node\Stmt\Foreach_;
use PhabelVendor\PhpParser\Node\Stmt\If_;
use PhabelVendor\PhpParser\Node\Stmt\Interface_;
use PhabelVendor\PhpParser\Node\Stmt\Property;
use PhabelVendor\PhpParser\Node\Stmt\PropertyProperty;
use PhabelVendor\PhpParser\Node\Stmt\Return_;
use PhabelVendor\PhpParser\Node\Stmt\Throw_;
use PhabelVendor\PhpParser\Node\UnionType;
use PhabelVendor\PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PhabelVendor\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PhabelVendor\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PhabelVendor\PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PhabelVendor\PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
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
    private const SINGLE_PROPERTY = 'TypeHintReplacer:singleProperty';
    private const IGNORE_RETURN = 0;
    private const VOID_RETURN = 1;
    private const TYPE_RETURN = 2;
    private const PRECEDENCE = ['string' => 3, 'float' => 2, 'int' => 1, 'bool' => 0];
    /**
     * Stack.
     *
     * @var SplStack
     * @psalm-var SplStack<(array{0: (self::IGNORE_RETURN | self::VOID_RETURN)} | array{0: self::TYPE_RETURN, 1: Node, 2: bool, 3: bool, 4: Node, 5: callable(Node ...): If_})>
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
     * @param (null | Identifier | Name | NullableType | UnionType) $type Type
     *
     * @return bool
     */
    public static function replace(?Node $type) : bool
    {
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
     * @param (null | Identifier | Name | NullableType | UnionType) $type Type
     *
     * @return boolean
     */
    public static function replaced(?Node $type) : bool
    {
        if ($type) {
            return $type->getAttribute(self::FORCE_ATTRIBUTE, \false);
        }
        return \true;
    }
    /**
     * Check if we should replace a void return type.
     *
     * @param (Node | null) $returnType
     * @return bool
     */
    private function checkVoid(Context $ctx, PhpDocNode $phpdoc, ?Node $returnType) : bool
    {
        $strip = $returnType instanceof Identifier && $returnType->toLowerString() === 'void' && $this->getConfig('void', $this->getConfig('return', $returnType->getAttribute(self::FORCE_ATTRIBUTE)));
        if ($strip) {
            $ok = \false;
            foreach ($phpdoc->children as $child) {
                if ($child instanceof PhpDocTagNode && $child->value instanceof ReturnTagValueNode) {
                    $ok = \true;
                    break;
                }
            }
            if (!$ok) {
                $phpdoc->children[] = new PhpDocTagNode('@return', new ReturnTagValueNode($ctx->phpdocParser->parseType('void'), ''));
            }
        }
        return $strip;
    }
    /**
     * Resolve special class name.
     *
     * @param (Identifier | Name) $type
     * @param ?Expr $className
     *
     * @return Expr
     */
    private function resolveClassName($type, ?Expr $className) : Expr
    {
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
     * @param (Name | Identifier)[] $types Types to check
     * @param ?Expr $className Whether the current class is anonymous
     * @param boolean $fromNullable Whether this type is nullable
     *
     * @return array{0: Node, 1: callable(Node ...): If_} The error message, the condition wrapper callback
     * @param (Param | PropertyProperty | null) $param
     */
    private function generateConditions(Context $ctx, PhpDocNode $phpdoc, $param, array $types, ?Expr $className, bool $fromNullable = \false) : array
    {
        if (!($param instanceof Param || $param instanceof PropertyProperty || \is_null($param))) {
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($param) must be of type Param|PropertyProperty|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($param) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $is_return = \false;
        $is_property = \false;
        $is_single_property = \false;
        $is_param = \false;
        $phpdocVar = null;
        if (!$param) {
            $is_return = \true;
            $var = new Variable('phabelReturn');
        } elseif ($param instanceof PropertyProperty) {
            $is_property = \true;
            $is_single_property = $param->getAttribute(self::SINGLE_PROPERTY, \false);
            $phpdocVar = '$' . $param->name;
            $var = new PropertyFetch(new Variable('this'), $param->name);
        } elseif ($param->variadic) {
            $is_param = \true;
            $phpdocVar = '$' . $param->var->name;
            $var = new Variable('phabelVariadic');
        } else {
            $is_param = \true;
            $phpdocVar = '$' . $param->var->name;
            $var = $param->var;
        }
        /** @var Expr[] */
        $typeNames = [];
        /** @var Expr[] */
        $oopNames = [];
        /** @var list<(Expr|array{0: Expr, 1: Expr, 2: class-string<Cast>})> */
        $conditions = [];
        /** @var string Last string type name */
        $stringType = '';
        /** @var array<string, true> */
        $phpdocType = [];
        \usort($types, function ($type1, $type2) {
            if ($type1 instanceof Identifier && $type2 instanceof Identifier) {
                $type1 = $type1->toLowerString();
                $type2 = $type2->toLowerString();
                if (isset(self::PRECEDENCE[$type1]) && isset(self::PRECEDENCE[$type2])) {
                    return self::PRECEDENCE[$type1] <=> self::PRECEDENCE[$type2];
                }
            }
            return 0;
        });
        foreach ($types as $type) {
            if ($type instanceof Identifier) {
                $typeName = $type->toLowerString();
                $phpdocType[$typeName] = \true;
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
                    case 'false':
                        $stringType = new String_('false');
                        $conditions[] = new Identical($var, Tools::fromLiteral(\false));
                        $oopNames[] = $stringType;
                        break;
                    case 'true':
                        $stringType = new String_('true');
                        $conditions[] = new Identical($var, Tools::fromLiteral(\true));
                        $oopNames[] = $stringType;
                        break;
                    default:
                        $stringType = $this->resolveClassName($type, $className);
                        $conditions[] = new Instanceof_($var, new Name($typeName));
                        $oopNames[] = $stringType;
                }
            } else {
                $phpdocType[$type->toCodeString()] = \true;
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
            $phpdocType['null'] = \true;
        }
        $phpdocType = \implode('|', \array_keys($phpdocType));
        $ok = \false;
        foreach ($phpdoc->children as $child) {
            if (!$child instanceof PhpDocTagNode) {
                continue;
            }
            if ($is_return && $child->value instanceof ReturnTagValueNode) {
                $ok = \true;
                break;
            }
            if ($is_param && $child->value instanceof ParamTagValueNode && $child->value->parameterName === $phpdocVar) {
                $ok = \true;
                break;
            }
            if ($is_property && $child->value instanceof VarTagValueNode && ($child->value->variableName === $phpdocVar || $child->value->variableName === '' && $is_single_property)) {
                $ok = \true;
                break;
            }
        }
        if (!$ok) {
            if ($is_param) {
                $phpdoc->children[] = new PhpDocTagNode('@param', new ParamTagValueNode($ctx->phpdocParser->parseType($phpdocType), $param->variadic, $phpdocVar, ''));
            } elseif ($is_return) {
                $phpdoc->children[] = new PhpDocTagNode('@return', new ReturnTagValueNode($ctx->phpdocParser->parseType($phpdocType), ''));
            } elseif ($is_property) {
                $phpdoc->children[] = new PhpDocTagNode('@var', new VarTagValueNode($ctx->phpdocParser->parseType($phpdocType), $phpdocVar, ''));
            }
        }
        $splitConditions = [];
        $currentConditions = [];
        foreach ($conditions as $condition) {
            if (\is_array($condition)) {
                [$conditionStrict, $conditionsLoose, $castLoose] = $condition;
                $currentConditions[] = $conditionStrict;
                $conditionsLoose = new BooleanNot($conditionsLoose);
                $splitConditions[] = function (Node ...$stmts) use($conditionsLoose, $var, $castLoose) : If_ {
                    return new If_($conditionsLoose, ['stmts' => $stmts, 'else' => new Else_([new Expression(new Assign($var, new $castLoose($var)))])]);
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
     * @param ?Param $param Parameter
     * @param (null | Identifier | Name | NullableType | UnionType) $type Type
     * @param ?Expr $className Whether the current class is anonymous
     * @param bool $force Whether to force strip
     *
     * @return (null | array{0: Node, 1: callable(Node ...): If_}) The error message, the condition wrapper callback
     */
    private function strip(Context $ctx, PhpDocNode $phpdoc, $param, ?Node $type, ?Expr $className, bool $nullish, bool $force = \false) : ?array
    {
        if (!($param instanceof Param || $param instanceof PropertyProperty || \is_null($param))) {
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($param) must be of type Param|PropertyProperty|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($param) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!$type) {
            return null;
        }
        $force = $force || $type->getAttribute(self::FORCE_ATTRIBUTE, \false);
        if ($type instanceof UnionType) {
            if (!$this->getConfig('union', $force)) {
                return null;
            }
            return $this->generateConditions($ctx, $phpdoc, $param, $type->types, $className, $nullish);
        }
        if ($type instanceof NullableType && $this->getConfig('nullable', $force)) {
            return $this->generateConditions($ctx, $phpdoc, $param, [$type->type], $className, \true);
        }
        $subType = $type instanceof NullableType ? $type->type : $type;
        if (\in_array($subType->toString(), $this->getConfig('types', [])) || $force) {
            return $this->generateConditions($ctx, $phpdoc, $param, [$subType], $className, $nullish || $type instanceof NullableType);
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
    public function enterFunction(FunctionLike $func, Context $ctx) : ?FunctionLike
    {
        $phpdoc = $ctx->phpdocParser->parsePhpDoc($func->getDocComment());
        if (!$phpdoc) {
            $phpdoc = new PhpDocNode([]);
        }
        $functionName = new Method();
        $className = null;
        $returnType = $func->getReturnType();
        if ($func instanceof ClassMethod) {
            /** @var ClassLike */
            $parent = $ctx->parents->top();
            if ($parent instanceof Interface_ || $func->getStmts() === null) {
                foreach ($func->getParams() as $param) {
                    if ($this->strip($ctx, $phpdoc, $param, $param->type, null, \false)) {
                        $param->type = null;
                    }
                }
                if ($this->checkVoid($ctx, $phpdoc, $returnType)) {
                    $func->returnType = null;
                }
                if ($this->strip($ctx, $phpdoc, null, $returnType, null, \false, $this->getConfig('return', \false))) {
                    $func->returnType = null;
                }
                $this->stack->push([self::IGNORE_RETURN]);
                $func->setDocComment(new Doc($phpdoc));
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
            if (!($condition = $this->strip($ctx, $phpdoc, $param, $param->type, $className, $nullish))) {
                continue;
            }
            $index++;
            $param->type = null;
            [$string, $condition] = $condition;
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
        if ($this->checkVoid($ctx, $phpdoc, $returnType)) {
            $ctx->toClosure($func);
            $this->stack->push([self::VOID_RETURN]);
            $func->returnType = null;
            $func->setDocComment(new Doc($phpdoc));
            return $func;
        }
        $var = new Variable('phabelReturn');
        if (!($condition = $this->strip($ctx, $phpdoc, null, $returnType, $className, \false, $this->getConfig('return', \false)))) {
            $this->stack->push([self::IGNORE_RETURN]);
            $func->setDocComment(new Doc($phpdoc));
            return $func;
        }
        $func->returnType = null;
        if (\Phabel\Plugin\GeneratorDetector::isGenerator($func)) {
            $this->stack->push([self::IGNORE_RETURN]);
            $func->setDocComment(new Doc($phpdoc));
            return $func;
        }
        $ctx->toClosure($func);
        $this->stack->push(\array_merge([self::TYPE_RETURN, $functionName, $func->returnsByRef()], $condition));
        $stmts = $func->getStmts();
        $final = \end($stmts);
        if (!$final instanceof Return_) {
            [$string, $condition] = $condition;
            $start = new Concat($functionName, new String_("(): Return value must be of type "));
            $start = new Concat($start, $string);
            $start = new Concat($start, new String_(", none returned in "));
            $start = new Concat($start, self::callPoly('trace'));
            $throw = new Throw_(new New_(new FullyQualified(\TypeError::class), [new Arg($start)]));
            $func->stmts[] = $throw;
        }
        $func->setDocComment(new Doc($phpdoc));
        return $func;
    }
    /**
     *
     */
    public function enterReturn(Return_ $return, Context $ctx) : ?Node
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
        $if = $condition(new Throw_(new New_(new FullyQualified(\TypeError::class), [new Arg($start)])));
        $return->expr = $var;
        $ctx->insertBefore($return, $assign, $if);
        return null;
    }
    /**
     *
     */
    public function leaveFunc(FunctionLike $func) : void
    {
        $this->stack->pop();
    }
    /**
     *
     */
    public function enterProperty(Property $stmt, Context $ctx) : void
    {
        if (!$stmt->type || !$stmt->props) {
            return;
        }
        $phpdoc = $ctx->phpdocParser->parsePhpDoc($stmt->getDocComment());
        if (!$phpdoc) {
            $phpdoc = new PhpDocNode([]);
        }
        $stmt->props[0]->setAttribute(self::SINGLE_PROPERTY, \count($stmt->props) === 1);
        $strip = \false;
        foreach ($stmt->props as $property) {
            if ($this->strip($ctx, $phpdoc, $property, $stmt->type, null, \false, $this->getConfig('property', \false))) {
                $strip = \true;
            }
        }
        if ($strip) {
            $stmt->type = null;
        }
        $stmt->setDocComment(new Doc($phpdoc));
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
        if (\is_object($value) && $value instanceof AnonymousClassInterface) {
            return $value::getPhabelOriginalName();
        }
        return \get_debug_type($value);
    }
    /**
     *
     */
    public static function next(array $config) : array
    {
        return [\Phabel\Plugin\StringConcatOptimizer::class];
    }
    /**
     *
     */
    public static function previous(array $config) : array
    {
        return [\Phabel\Plugin\GeneratorDetector::class];
    }
}
