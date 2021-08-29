<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\DeclareDeclare;
use PhpParser\Node\Stmt\Nop;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class StrictTypesDeclareStatementRemover extends Plugin
{
    public function leave(Declare_ $node)
    {
        $node->declares = \Phabel\Target\Php74\Polyfill::array_filter($node->declares, function (DeclareDeclare $declare) {
            return $declare->key->name !== 'strict_types';
        });
        if (empty($node->declares)) {
            $phabelReturn = new Nop();
            if (!($phabelReturn instanceof Nop || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Nop, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = null;
        if (!($phabelReturn instanceof Nop || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Nop, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
