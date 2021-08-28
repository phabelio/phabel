<?php

namespace Phabel\Target\Php74;

use Phabel\Plugin;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\Coalesce as BinaryOpCoalesce;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class NullCoalesceAssignment extends Plugin
{
    public function enter(Coalesce $coalesce)
    {
        $phabelReturn = new Assign($coalesce->var, new BinaryOpCoalesce($coalesce->var, $coalesce->expr), $coalesce->getAttributes());
        if (!$phabelReturn instanceof Assign) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Assign, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
