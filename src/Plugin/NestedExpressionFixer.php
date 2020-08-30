<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;

class NestedExpressionFixer extends Plugin
{
    public function enter(Expr $expr): void
    {
        /** @var array<string, array<class-string<Expr>, true>> */
        $subNodes = $this->getConfig($class = \get_class($expr), false);
        if (!$subNodes) {
            return;
        }
        foreach ($subNodes as $key => $types) {
            /** @var Expr $value */
            $value = &$expr->{$key};
            if (!isset($types[\get_class($value)])) {
                continue;
            }
            switch ($class) {
                case ArrayDimFetch::class:
                case PropertyFetch::class:
                case MethodCall::class:
                case New_::class:
                case Instanceof_::class:
                    $value = self::callPoly('returnMe', $value);
                    break;
                case FuncCall::class:
                    $expr->var = self::callPoly('returnMe', $expr->var);
                    break;
            }
        }
    }

    /**
     * Returns the data provided.
     *
     * @param mixed $data Data
     *
     * @return mixed
     *
     * @template T
     *
     * @psalm-param T $data data
     *
     * @psalm-return T
     */
    public static function returnMe($data)
    {
        return $data;
    }
}
