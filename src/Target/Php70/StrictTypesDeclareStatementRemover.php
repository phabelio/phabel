<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use Phabel\PhpParser\Node\Stmt\Declare_;
use Phabel\PhpParser\Node\Stmt\DeclareDeclare;
use Phabel\PhpParser\Node\Stmt\Nop;
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class StrictTypesDeclareStatementRemover extends Plugin
{
    public function leave(Declare_ $node) : ?Nop
    {
        $node->declares = \Phabel\Target\Php74\Polyfill::array_filter($node->declares, function (DeclareDeclare $declare) {
            return $declare->key->name !== 'strict_types';
        });
        if (empty($node->declares)) {
            return new Nop();
        }
        return null;
    }
}
