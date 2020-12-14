<?php

namespace Phabel\Target\Php74;

use Phabel\Plugin;
use Phabel\Target\Php70\NullCoalesceReplacer;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\Coalesce as BinaryOpCoalesce;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class NullCoalesceAssignment extends Plugin
{
    public function enter(Coalesce $coalesce): Assign
    {
        return new Assign($coalesce->var, new BinaryOpCoalesce($coalesce->var, $coalesce->expr), $coalesce->getAttributes());
    }
    public static function runWithBefore(array $config): array
    {
        return [NullCoalesceReplacer::class];
    }
}
