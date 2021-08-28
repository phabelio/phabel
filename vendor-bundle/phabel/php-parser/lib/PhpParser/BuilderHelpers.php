<?php

namespace Phabel\PhpParser;

use Phabel\PhpParser\Node\Expr;
use Phabel\PhpParser\Node\Identifier;
use Phabel\PhpParser\Node\Name;
use Phabel\PhpParser\Node\NullableType;
use Phabel\PhpParser\Node\Scalar;
use Phabel\PhpParser\Node\Stmt;
use Phabel\PhpParser\Node\UnionType;
/**
 * This class defines helpers used in the implementation of builders. Don't use it directly.
 *
 * @internal
 */
final class BuilderHelpers
{
    /**
     * Normalizes a node: Converts builder objects to nodes.
     *
     * @param Node|Builder $node The node to normalize
     *
     * @return Node The normalized node
     */
    public static function normalizeNode($node)
    {
        if ($node instanceof Builder) {
            $phabelReturn = $node->getNode();
            if (!$phabelReturn instanceof Node) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        } elseif ($node instanceof Node) {
            $phabelReturn = $node;
            if (!$phabelReturn instanceof Node) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        throw new \LogicException('Expected node or builder object');
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Node, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    /**
     * Normalizes a node to a statement.
     *
     * Expressions are wrapped in a Stmt\Expression node.
     *
     * @param Node|Builder $node The node to normalize
     *
     * @return Stmt The normalized statement node
     */
    public static function normalizeStmt($node)
    {
        $node = self::normalizeNode($node);
        if ($node instanceof Stmt) {
            $phabelReturn = $node;
            if (!$phabelReturn instanceof Stmt) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Stmt, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($node instanceof Expr) {
            $phabelReturn = new Stmt\Expression($node);
            if (!$phabelReturn instanceof Stmt) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Stmt, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        throw new \LogicException('Expected statement or expression node');
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Stmt, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    /**
     * Normalizes strings to Identifier.
     *
     * @param string|Identifier $name The identifier to normalize
     *
     * @return Identifier The normalized identifier
     */
    public static function normalizeIdentifier($name)
    {
        if ($name instanceof Identifier) {
            $phabelReturn = $name;
            if (!$phabelReturn instanceof Identifier) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Identifier, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (\is_string($name)) {
            $phabelReturn = new Identifier($name);
            if (!$phabelReturn instanceof Identifier) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Identifier, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        throw new \LogicException('Phabel\\Expected string or instance of Node\\Identifier');
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Identifier, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    /**
     * Normalizes strings to Identifier, also allowing expressions.
     *
     * @param string|Identifier|Expr $name The identifier to normalize
     *
     * @return Identifier|Expr The normalized identifier or expression
     */
    public static function normalizeIdentifierOrExpr($name)
    {
        if ($name instanceof Identifier || $name instanceof Expr) {
            return $name;
        }
        if (\is_string($name)) {
            return new Identifier($name);
        }
        throw new \LogicException('Phabel\\Expected string or instance of Node\\Identifier or Node\\Expr');
    }
    /**
     * Normalizes a name: Converts string names to Name nodes.
     *
     * @param Name|string $name The name to normalize
     *
     * @return Name The normalized name
     */
    public static function normalizeName($name)
    {
        $phabelReturn = self::normalizeNameCommon($name, \false);
        if (!$phabelReturn instanceof Name) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Name, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Normalizes a name: Converts string names to Name nodes, while also allowing expressions.
     *
     * @param Expr|Name|string $name The name to normalize
     *
     * @return Name|Expr The normalized name or expression
     */
    public static function normalizeNameOrExpr($name)
    {
        return self::normalizeNameCommon($name, \true);
    }
    /**
     * Normalizes a name: Converts string names to Name nodes, optionally allowing expressions.
     *
     * @param Expr|Name|string $name      The name to normalize
     * @param bool             $allowExpr Whether to also allow expressions
     *
     * @return Name|Expr The normalized name, or expression (if allowed)
     */
    private static function normalizeNameCommon($name, $allowExpr)
    {
        if (!\is_bool($allowExpr)) {
            if (!(\is_bool($allowExpr) || \is_numeric($allowExpr) || \is_string($allowExpr))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($allowExpr) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($allowExpr) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $allowExpr = (bool) $allowExpr;
            }
        }
        if ($name instanceof Name) {
            return $name;
        } elseif (\is_string($name)) {
            if (!$name) {
                throw new \LogicException('Name cannot be empty');
            }
            if ($name[0] === '\\') {
                return new Name\FullyQualified(\substr($name, 1));
            } elseif (0 === \strpos($name, 'namespace\\')) {
                return new Name\Relative(\substr($name, \strlen('namespace\\')));
            } else {
                return new Name($name);
            }
        }
        if ($allowExpr) {
            if ($name instanceof Expr) {
                return $name;
            }
            throw new \LogicException('Phabel\\Name must be a string or an instance of Node\\Name or Node\\Expr');
        } else {
            throw new \LogicException('Phabel\\Name must be a string or an instance of Node\\Name');
        }
    }
    /**
     * Normalizes a type: Converts plain-text type names into proper AST representation.
     *
     * In particular, builtin types become Identifiers, custom types become Names and nullables
     * are wrapped in NullableType nodes.
     *
     * @param string|Name|Identifier|NullableType|UnionType $type The type to normalize
     *
     * @return Name|Identifier|NullableType|UnionType The normalized type
     */
    public static function normalizeType($type)
    {
        if (!\is_string($type)) {
            if (!$type instanceof Name && !$type instanceof Identifier && !$type instanceof NullableType && !$type instanceof UnionType) {
                throw new \LogicException('Type must be a string, or an instance of Name, Identifier, NullableType or UnionType');
            }
            return $type;
        }
        $nullable = \false;
        if (\strlen($type) > 0 && $type[0] === '?') {
            $nullable = \true;
            $type = \substr($type, 1);
        }
        $builtinTypes = ['array', 'callable', 'string', 'int', 'float', 'bool', 'iterable', 'void', 'object', 'mixed'];
        $lowerType = \strtolower($type);
        if (\in_array($lowerType, $builtinTypes)) {
            $type = new Identifier($lowerType);
        } else {
            $type = self::normalizeName($type);
        }
        if ($nullable && (string) $type === 'void') {
            throw new \LogicException('void type cannot be nullable');
        }
        if ($nullable && (string) $type === 'mixed') {
            throw new \LogicException('mixed type cannot be nullable');
        }
        return $nullable ? new NullableType($type) : $type;
    }
    /**
     * Normalizes a value: Converts nulls, booleans, integers,
     * floats, strings and arrays into their respective nodes
     *
     * @param Node\Expr|bool|null|int|float|string|array $value The value to normalize
     *
     * @return Expr The normalized value
     */
    public static function normalizeValue($value)
    {
        if ($value instanceof Node\Expr) {
            $phabelReturn = $value;
            if (!$phabelReturn instanceof Expr) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        } elseif (\is_null($value)) {
            $phabelReturn = new Expr\ConstFetch(new Name('null'));
            if (!$phabelReturn instanceof Expr) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        } elseif (\is_bool($value)) {
            $phabelReturn = new Expr\ConstFetch(new Name($value ? 'true' : 'false'));
            if (!$phabelReturn instanceof Expr) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        } elseif (\is_int($value)) {
            $phabelReturn = new Scalar\LNumber($value);
            if (!$phabelReturn instanceof Expr) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        } elseif (\is_float($value)) {
            $phabelReturn = new Scalar\DNumber($value);
            if (!$phabelReturn instanceof Expr) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        } elseif (\is_string($value)) {
            $phabelReturn = new Scalar\String_($value);
            if (!$phabelReturn instanceof Expr) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        } elseif (\is_array($value)) {
            $items = [];
            $lastKey = -1;
            foreach ($value as $itemKey => $itemValue) {
                // for consecutive, numeric keys don't generate keys
                if (null !== $lastKey && ++$lastKey === $itemKey) {
                    $items[] = new Expr\ArrayItem(self::normalizeValue($itemValue));
                } else {
                    $lastKey = null;
                    $items[] = new Expr\ArrayItem(self::normalizeValue($itemValue), self::normalizeValue($itemKey));
                }
            }
            $phabelReturn = new Expr\Array_($items);
            if (!$phabelReturn instanceof Expr) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        } else {
            throw new \LogicException('Invalid value');
        }
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    /**
     * Normalizes a doc comment: Converts plain strings to PhpParser\Comment\Doc.
     *
     * @param Comment\Doc|string $docComment The doc comment to normalize
     *
     * @return Comment\Doc The normalized doc comment
     */
    public static function normalizeDocComment($docComment)
    {
        if ($docComment instanceof Comment\Doc) {
            $phabelReturn = $docComment;
            if (!$phabelReturn instanceof Comment\Doc) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Comment\\Doc, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        } elseif (\is_string($docComment)) {
            $phabelReturn = new Comment\Doc($docComment);
            if (!$phabelReturn instanceof Comment\Doc) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Comment\\Doc, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        } else {
            throw new \LogicException('Phabel\\Doc comment must be a string or an instance of PhpParser\\Comment\\Doc');
        }
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Comment\\Doc, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    /**
     * Adds a modifier and returns new modifier bitmask.
     *
     * @param int $modifiers Existing modifiers
     * @param int $modifier  Modifier to set
     *
     * @return int New modifiers
     */
    public static function addModifier($modifiers, $modifier)
    {
        if (!\is_int($modifiers)) {
            if (!(\is_bool($modifiers) || \is_numeric($modifiers))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($modifiers) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($modifiers) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $modifiers = (int) $modifiers;
            }
        }
        if (!\is_int($modifier)) {
            if (!(\is_bool($modifier) || \is_numeric($modifier))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($modifier) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($modifier) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $modifier = (int) $modifier;
            }
        }
        Stmt\Class_::verifyModifier($modifiers, $modifier);
        $phabelReturn = $modifiers | $modifier;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
