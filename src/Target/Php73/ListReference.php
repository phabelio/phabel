<?php

namespace Phabel\Target\Php73;

use Phabel\Context;
use Phabel\Plugin;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Foreach_;

/**
 * Polyfills list assignment by reference.
 */
class ListReference extends Plugin
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
     * @return void
     */
    public function enterAssign(Assign $node, Context $ctx): void
    {
        if (!($node->var instanceof List_ || $node->var instanceof Array_) || !$this->shouldSplit($node->var)) {
            return;
        }
        $isStmt = $ctx->parents[0]->getAttribute('currentNode') === 'stmts';
        $list = $node->var;
        $var = $ctx->getVariable();
        $assignments = self::splitList($list, $var);
        if ($isStmt) {
            $last = \array_pop($assignments);
            $ctx->insertBefore($node, new Assign($node->var, $node->expr), ...$assignments);
            return $last;
        }

        // On newer versions of php, list assignment returns the original array
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
    private static function splitList($list, Variable $var): array
    {
        $assignments = [];
        $key = 0; // Technically a list assignment does not support mixed keys, but we need this for nested assignments
        foreach ($list->items as $item) {
            if (!$item) {
                continue;
            }
            $curKey = $item->key ?? $key++;
            if ($item->byRef) {
                $assignments []= new AssignRef($item->value, new ArrayDimFetch($var, $curKey));
            } else {
                $assignments []= new Assign($item->value, new ArrayDimFetch($var, $curKey));
            }
        }
        return $assignments;
    }
    /**
     * Whether this is a referenced list.
     *
     * @param List_|Array_ $list    List
     * @param bool         $recurse Whether to recurse while checking
     *
     * @return boolean
     */
    private function shouldSplit($list, bool $recurse = false): bool
    {
        /** @var ArrayItem $item */
        foreach ($list->items ?? [] as $item) {
            if ($item->byRef) {
                return true;
            } elseif ($recurse && ($item->value instanceof List_ || $item->value instanceof Array_)) {
                if ($this->shouldSplit($item->value, $recurse)) {
                    return true;
                }
            }
        }
        return false;
    }
}