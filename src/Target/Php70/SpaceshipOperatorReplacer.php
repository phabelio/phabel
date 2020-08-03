<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use PhpParser\Node\Expr\BinaryOp\Spaceship;
use PhpParser\Node\Expr\StaticCall;

/**
 * Polyfill spaceship operator.
 */
class SpaceshipOperatorReplacer extends Plugin
{
    /**
     * Replace spaceship operator.
     *
     * @param Spaceship $node Node
     *
     * @return StaticCall
     */
    public function enter(Spaceship $node): StaticCall
    {
        return self::callPoly('spaceship', $node->left, $node->right);
    }
    /**
     * Spacesip operator.
     *
     * @param integer|string|float $a A
     * @param integer|string|float $b B
     *
     * @return integer
     */
    public static function spaceship($a, $b): int
    {
        return $a < $b ? -1 : ($a === $b ? 0 : 1);
    }
}
