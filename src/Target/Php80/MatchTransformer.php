<?php

namespace Phabel\Target\Php80;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Plugin\VariableFinder;
use PhabelVendor\PhpParser\Node\Arg;
use PhabelVendor\PhpParser\Node\Expr\BinaryOp\Concat;
use PhabelVendor\PhpParser\Node\Expr\BinaryOp\Identical;
use PhabelVendor\PhpParser\Node\Expr\Closure;
use PhabelVendor\PhpParser\Node\Expr\FuncCall;
use PhabelVendor\PhpParser\Node\Expr\Match_;
use PhabelVendor\PhpParser\Node\Expr\New_;
use PhabelVendor\PhpParser\Node\Name\FullyQualified;
use PhabelVendor\PhpParser\Node\Param;
use PhabelVendor\PhpParser\Node\Scalar\String_;
use PhabelVendor\PhpParser\Node\Stmt\Else_;
use PhabelVendor\PhpParser\Node\Stmt\ElseIf_;
use PhabelVendor\PhpParser\Node\Stmt\If_;
use PhabelVendor\PhpParser\Node\Stmt\Return_;
use PhabelVendor\PhpParser\Node\Stmt\Throw_;
/**
 * Polyfill match expression.
 */
class MatchTransformer extends Plugin
{
    public function enter(Match_ $match, Context $context) : FuncCall
    {
        $closure = new Closure(['params' => [new Param($var = $context->getVariable())], 'uses' => \array_values(VariableFinder::find($match, \true))]);
        $cases = [];
        $default = null;
        foreach ($match->arms as $arm) {
            if ($arm->conds === null) {
                $default = new Return_($arm->body);
                continue;
            }
            foreach ($arm->conds as $cond) {
                $cases[] = [new Identical($var, $cond), new Return_($arm->body)];
            }
        }
        if (!$default) {
            $string = new Concat(new String_("Unhandled match value of type "), self::call('get_debug_type', $var));
            $default = new Throw_(new New_(new FullyQualified(\UnhandledMatchError::class), [new Arg($string)]));
        }
        if (empty($cases)) {
            $closure->stmts = [$default];
        } else {
            [$ifCond, $ifBody] = \array_shift($cases);
            foreach ($cases as &$case) {
                [$cond, $body] = $case;
                $case = new ElseIf_($cond, [$body]);
            }
            $closure->stmts = [new If_($ifCond, ['stmts' => [$ifBody], 'elseifs' => $cases, 'else' => new Else_([$default])])];
        }
        return new FuncCall($closure, [new Arg($match->cond)]);
    }
}
