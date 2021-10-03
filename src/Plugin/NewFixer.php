<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use PhabelVendor\PhpParser\Node;
use PhabelVendor\PhpParser\Node\Expr;
use PhabelVendor\PhpParser\Node\Expr\Assign;
use PhabelVendor\PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhabelVendor\PhpParser\Node\Expr\Instanceof_;
use PhabelVendor\PhpParser\Node\Expr\New_;
use PhabelVendor\PhpParser\Node\Expr\Ternary;
use PhabelVendor\PhpParser\Node\Scalar;
use PhabelVendor\PhpParser\NodeFinder;
/**
 * Fix certain new expressions.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 */
class NewFixer extends Plugin
{
    private $finder;
    public function __construct()
    {
        $this->finder = new NodeFinder();
    }
    private function isParenthesised(Node $node) : bool
    {
        return !($node instanceof Expr\Variable || $node instanceof Node\Name || $node instanceof Expr\ArrayDimFetch || $node instanceof Expr\PropertyFetch || $node instanceof Expr\NullsafePropertyFetch || $node instanceof Expr\StaticPropertyFetch || $node instanceof Expr\Array_ || $node instanceof Scalar\String_ || $node instanceof Expr\ConstFetch || $node instanceof Expr\ClassConstFetch);
    }
    private function hasParenthesised(Node $node) : bool
    {
        return $node instanceof Expr && $this->finder->findFirst($node, function (Node $node) : bool {
            return $this->isParenthesised($node);
        }) !== null;
    }
    public function enterNew(New_ $new, Context $context)
    {
        if ($this->hasParenthesised($new->class)) {
            $valueCopy = $new->class;
            return new Ternary(new BooleanOr(new Assign($new->class = $context->getVariable(), $valueCopy), self::fromLiteral(\true)), $new, self::fromLiteral(\false));
        }
    }
    public function enterInstanceof(Instanceof_ $expr)
    {
        if ($this->hasParenthesised($expr->class)) {
            return self::callPoly('instanceOf', $expr->expr, $expr->class);
        }
    }
    /**
     * Check if a is instance of b.
     *
     * @param class-string|object $a
     * @param class-string|object $b
     *
     * @return boolean
     */
    public static function instanceOf($a, $b) : bool
    {
        return $a instanceof $b;
    }
}
