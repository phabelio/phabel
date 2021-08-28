<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use Phabel\PhpParser\Node\Expr\BinaryOp\Spaceship;
use Phabel\PhpParser\Node\Expr\StaticCall;
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
    public function enter(Spaceship $node)
    {
        $phabelReturn = self::callPoly('spaceship', $node->left, $node->right);
        if (!$phabelReturn instanceof StaticCall) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type StaticCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Spacesip operator.
     *
     * @param integer|string|float $a A
     * @param integer|string|float $b B
     *
     * @return integer
     */
    public static function spaceship($a, $b)
    {
        $phabelReturn = $a < $b ? -1 : ($a === $b ? 0 : 1);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (int) $phabelReturn;
        }
        return $phabelReturn;
    }
}
