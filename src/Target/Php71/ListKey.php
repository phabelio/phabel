<?php

namespace Phabel\Target\Php71;

use Phabel\Plugin;
use PhpParser\BuilderHelpers;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Stmt\Foreach_;

/**
 * Polyfills keyed list assignment.
 */
class ListKey extends Plugin
{
    /**
     * Parse list foreach with custom keys.
     *
     * @param Foreach_ $node Foreach
     *
     * @return void
     */
    public function enterForeach(Foreach_ $node): void
    {
        if (!$node->valueVar instanceof List_ || !$this->shouldSplit($node->valueVar)) {
            return;
        }
        [$node->valueVar, $array] = $this->splitList($node->valueVar);
        $node->expr = self::callPoly('destructureForeach', $array, $node->expr);
    }
    /**
     * Parse list assignment with custom keys.
     *
     * @param Assign $node List assignment
     *
     * @return void
     */
    public function enterAssign(Assign $node): void
    {
        if (!$node->var instanceof List_ || !$this->shouldSplit($node->var)) {
            return;
        }
        [$node->var, $array] = $this->splitList($node->var);
        $node->expr = self::callPoly('destructure', $array, $node->expr);
    }
    /**
     * Whether this is a keyed list.
     *
     * @param List_ $list List
     *
     * @return boolean
     */
    private function shouldSplit(List_ $list): bool
    {
        return isset($list->items[0]->key);
    }
    /**
     * Split keyed list into a new list assignment and array to pass to destructure function.
     *
     * @param List_ $list Keyed list
     *
     * @return array{0: List_, 1: Array_}
     */
    private static function splitList(List_ $list): array
    {
        $newList = [];
        $keys = [];
        $key = 0; // Technically a list assignment does not support mixed keys, but we need this for nested assignments
        foreach ($list->items as $item) {
            if ($item) {
                $curKey = $item->key ?? $key++;
                $item->key = null;
                if ($item->value instanceof List_) {
                    [$item->value, $keys[$curKey]] = self::splitList($list);
                } else {
                    $keys[$curKey] = null;
                }
            } else {
                $newList []= null;
                $keys[$key++] = null;
            }
        }
        /** @var Array_ */
        $keys = BuilderHelpers::normalizeValue($keys);
        return [new List_($newList), $keys];
    }
    /**
     * Destructure array of arrays.
     *
     * @param array $keys  Custom keys
     * @param array $array Array
     *
     * @psalm-param array<string, null|array> $keys Custom keys
     *
     * @return \Generator
     */
    public static function destructureForeach(array $keys, array $array): \Generator
    {
        foreach ($array as $value) {
            yield self::destructure($keys, $value);
        }
    }
    /**
     * Destructure array.
     *
     * @param array $keys  Custom keys
     * @param array $array Array
     *
     * @psalm-param array<string, null|array> $keys Custom keys
     *
     * @return array
     */
    public static function destructure(array $keys, array $array): array
    {
        $res = [];
        foreach ($keys as $key => $subKeys) {
            if ($subKeys === null) {
                $res[] = $array[$key];
            } else {
                $res[] = self::destructure($subKeys, $array[$key]);
            }
        }
        return $res;
    }
    /**
     * {@inheritDoc}
     */
    public static function runAfter(): array
    {
        return [ArrayList::class];
    }
}
