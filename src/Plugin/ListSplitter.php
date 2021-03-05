<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Foreach_;

/**
 * Polyfills unsupported list assignments.
 */
class ListSplitter extends Plugin
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
        if (!($node->valueVar instanceof List_ || $node->valueVar instanceof Array_)) {
            return;
        }
        if (!$this->shouldSplit($node->valueVar) && !($this->getConfig('parentExpr', false) && $ctx->isParentStmt())) {
            return;
        }
        $list = $node->valueVar;
        $var = $node->valueVar = $ctx->getVariable();
        $assignments = self::splitList($list, $var);
        $node->stmts = \array_merge($assignments, $node->stmts);
        $node->byRef = $this->hasReference($list);
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
        $var = $ctx->getVariable();
        $assignments = self::splitList($node->var, $var);
        $hasReference = $this->hasReference($node->var);
        if ($ctx->isParentStmt()) {
            $last = \array_pop($assignments);
            $ctx->insertBefore($node, $hasReference ? new AssignRef($var, $node->expr) : new Assign($var, $node->expr), ...$assignments);
            return $last;
        }
        // On newer versions of php, the list assignment expression returns the original array
        $ctx->insertBefore($node, $hasReference ? new AssignRef($var, $node->expr) : new Assign($var, $node->expr), ...$assignments);
        return $var;
    }
    /**
     * Split referenced list into multiple assignments.
     *
     * @param Array_|List_ $list   List
     * @param Variable     $var    Variable
     *
     * @return (Assign|AssignRef)[]
     * @psalm-return array<int, Assign|AssignRef>
     */
    public static function splitList($list, Variable $var): array
    {
        $assignments = [];
        $key = 0;
        // Technically a list assignment does not support mixed keys, but we need this for nested assignments
        foreach ($list->items as $item) {
            if (!$item) {
                $key++;
                continue;
            }
            $curKey = $item->key ?? new LNumber($key++);
            if ($item->byRef) {
                $assignments[] = new AssignRef($item->value, new ArrayDimFetch($var, $curKey));
            } else {
                $assignments[] = new Assign($item->value, new ArrayDimFetch($var, $curKey));
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
    private function hasReference($list): bool
    {
        $c = $this->getConfigArray();
        $this->setConfigArray(['byRef' => true]);
        $res = $this->shouldSplit($list);
        $this->setConfigArray($c);
        return $res;
    }
    /**
     * Whether we should act on this list.
     *
     * @param List_|Array_ $list    List
     *
     * @return boolean
     */
    private function shouldSplit($list): bool
    {
        foreach ($list->items as $item) {
            if (!$item) {
                continue;
            }
            if ($this->getConfig('byRef', false) && $item->byRef) {
                return true;
            } elseif ($this->getConfig('key', false) && isset($item->key)) {
                return true;
            } elseif ($item->value instanceof List_ || $item->value instanceof Array_) {
                if ($this->shouldSplit($item->value)) {
                    return true;
                }
            }
        }
        return false;
    }
}
