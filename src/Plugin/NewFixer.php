<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\PhpParser\Node;
use Phabel\PhpParser\Node\Expr;
use Phabel\PhpParser\Node\Expr\Assign;
use Phabel\PhpParser\Node\Expr\BinaryOp\BooleanOr;
use Phabel\PhpParser\Node\Expr\Instanceof_;
use Phabel\PhpParser\Node\Expr\New_;
use Phabel\PhpParser\Node\Expr\Ternary;
use Phabel\PhpParser\Node\Scalar;
use Phabel\PhpParser\NodeFinder;
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
    private function isParenthesised(Node $node)
    {
        $phabelReturn = !($node instanceof Expr\Variable || $node instanceof Node\Name || $node instanceof Expr\ArrayDimFetch || $node instanceof Expr\PropertyFetch || $node instanceof Expr\NullsafePropertyFetch || $node instanceof Expr\StaticPropertyFetch || $node instanceof Expr\Array_ || $node instanceof Scalar\String_ || $node instanceof Expr\ConstFetch || $node instanceof Expr\ClassConstFetch);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
    private function hasParenthesised(Node $node)
    {
        $phabelReturn = $node instanceof Expr && $this->finder->findFirst($node, function (Node $node) {
            $phabelReturn = $this->isParenthesised($node);
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (bool) $phabelReturn;
            }
            return $phabelReturn;
        }) !== null;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
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
    public static function instanceOf($a, $b)
    {
        $phabelReturn = \Phabel\Target\Php70\ThrowableReplacer::isInstanceofThrowable($a, $b);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
}
