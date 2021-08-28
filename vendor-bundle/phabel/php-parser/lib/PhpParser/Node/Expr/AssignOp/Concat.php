<?php

namespace Phabel\PhpParser\Node\Expr\AssignOp;

use Phabel\PhpParser\Node\Expr\AssignOp;
class Concat extends AssignOp
{
    public function getType()
    {
        $phabelReturn = 'Expr_AssignOp_Concat';
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
