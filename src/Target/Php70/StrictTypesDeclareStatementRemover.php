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
    public function leave(Declare_ $node): ?Nop
    {
        $node->declares = \array_filter($node->declares, fn (DeclareDeclare $declare) => $declare->key->name !== 'strict_types');
        if (empty($node->declares)) {
            return new Nop();
        }
        return null;
    }
}
