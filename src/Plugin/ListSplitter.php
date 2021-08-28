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
    public function enterForeach(Foreach_ $node, Context $ctx)
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
    public static function splitList($list, Variable $var)
    {
        $assignments = [];
        $key = 0;
        // Technically a list assignment does not support mixed keys, but we need this for nested assignments
        foreach ($list->items as $item) {
            if (!$item) {
                $key++;
                continue;
            }
            $curKey = isset($item->key) ? $item->key : new LNumber($key++);
            if ($item->byRef) {
                $assignments[] = new AssignRef($item->value, new ArrayDimFetch($var, $curKey));
            } else {
                $assignments[] = new Assign($item->value, new ArrayDimFetch($var, $curKey));
            }
        }
        $phabelReturn = $assignments;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Whether we should act on this list.
     *
     * @param List_|Array_ $list    List
     *
     * @return boolean
     */
    private function hasReference($list)
    {
        $c = $this->getConfigArray();
        $this->setConfigArray(['byRef' => true]);
        $res = $this->shouldSplit($list);
        $this->setConfigArray($c);
        $phabelReturn = $res;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Whether we should act on this list.
     *
     * @param List_|Array_ $list    List
     *
     * @return boolean
     */
    private function shouldSplit($list)
    {
        foreach ($list->items as $item) {
            if (!$item) {
                continue;
            }
            if ($this->getConfig('byRef', false) && $item->byRef) {
                $phabelReturn = true;
                if (!\is_bool($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (bool) $phabelReturn;
                }
                return $phabelReturn;
            } elseif ($this->getConfig('key', false) && isset($item->key)) {
                $phabelReturn = true;
                if (!\is_bool($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (bool) $phabelReturn;
                }
                return $phabelReturn;
            } elseif ($item->value instanceof List_ || $item->value instanceof Array_) {
                if ($this->shouldSplit($item->value)) {
                    $phabelReturn = true;
                    if (!\is_bool($phabelReturn)) {
                        if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                            throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        }
                        $phabelReturn = (bool) $phabelReturn;
                    }
                    return $phabelReturn;
                }
            }
        }
        $phabelReturn = false;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
}
