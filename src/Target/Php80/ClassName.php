<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\Tools;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;

/**
 * Polyfill fetching class name from object.
 */
class ClassName extends Plugin
{
    public function enter(ClassConstFetch $fetch)
    {
        if ($fetch->name instanceof Identifier && $fetch->name->name === 'class' && !$fetch->class instanceof Name) {
            $phabelReturn = Tools::call('get_class', $fetch->class);
            if (!($phabelReturn instanceof FuncCall || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?FuncCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = null;
        if (!($phabelReturn instanceof FuncCall || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?FuncCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
