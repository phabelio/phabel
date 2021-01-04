<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Foreach_;

/**
 * Polyfills unsupported list assignments
 */
abstract class ListSplitter extends Plugin
{
    /**
     * Parse list foreach with custom keys.
     *
     * @param Foreach_ $node Foreach
     *
     * @return void
     */
    public function enterForeach(Foreach_ $node, Context $ctx): void
    {
        if (!($node->valueVar instanceof List_ || $node->valueVar instanceof Array_) || !$this->shouldSplit($node->valueVar, true)) {
            return;
        }
        $list = $node->valueVar;
        $var = $node->valueVar = $ctx->getVariable();
        $assignments = self::splitList($list, $var);
        $node->stmts = \array_merge($assignments, $node->stmts);
    }
    /**
     * Parse list assignment with custom keys.
     *
     * @param Assign $node List assignment
     *
     * @return mixed
     */
    public function enterAssign(Assign $node, Context $ctx)
    {
        if (!($node->var instanceof List_ || $node->var instanceof Array_) || !$this->shouldSplit($node->var)) {
            return;
        }
        $list = $node->var;
        $var = $ctx->getVariable();
        $assignments = self::splitList($list, $var);
        if ($ctx->parentIsStmt()) {
            $last = \array_pop($assignments);
            $ctx->insertBefore($node, new Assign($node->var, $node->expr), ...$assignments);
            return $last;
        }

        // On newer versions of php, the list assignment expression returns the original array
        $ctx->insertBefore($node, new Assign($var, $node->expr), ...$assignments);
        return $var;
    }
    /**
     * Split referenced list into multiple assignments.
     *
     * @param Array_|List_ $list List
     * @param Variable     $var  Variable
     *
     * @return (Assign|AssignRef)[]
     */
    public static function splitList($list, Variable $var): array
    {
        $assignments = [];
        $key = 0; // Technically a list assignment does not support mixed keys, but we need this for nested assignments
        foreach ($list->items as $item) {
            if (!$item) {
                $key++;
                continue;
            }
            $curKey = $item->key ?? new LNumber($key++);
            if ($item->byRef) {
                $assignments []= new AssignRef($item->value, new ArrayDimFetch($var, $curKey));
            } else {
                $assignments []= new Assign($item->value, new ArrayDimFetch($var, $curKey));
            }
        }
        return $assignments;
    }
    /**
     * Whether we should act on this list.
     *
     * @param List_|Array_ $list    List
     *
     * @return boolean
     */
    abstract protected function shouldSplit($list, bool $recurse = false): bool;
}
