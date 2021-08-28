<?php

namespace Phabel\Target\Php80;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Plugin\VariableFinder;
use Phabel\PhpParser\Node\Arg;
use Phabel\PhpParser\Node\Expr\BinaryOp\Concat;
use Phabel\PhpParser\Node\Expr\BinaryOp\Identical;
use Phabel\PhpParser\Node\Expr\Closure;
use Phabel\PhpParser\Node\Expr\FuncCall;
use Phabel\PhpParser\Node\Expr\Match_;
use Phabel\PhpParser\Node\Expr\New_;
use Phabel\PhpParser\Node\Name\FullyQualified;
use Phabel\PhpParser\Node\Param;
use Phabel\PhpParser\Node\Scalar\String_;
use Phabel\PhpParser\Node\Stmt\Else_;
use Phabel\PhpParser\Node\Stmt\ElseIf_;
use Phabel\PhpParser\Node\Stmt\If_;
use Phabel\PhpParser\Node\Stmt\Return_;
use Phabel\PhpParser\Node\Stmt\Throw_;
/**
 * Polyfill match expression.
 */
class MatchTransformer extends Plugin
{
    public function enter(Match_ $match, Context $context)
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
            list($ifCond, $ifBody) = \array_shift($cases);
            foreach ($cases as &$case) {
                list($cond, $body) = $case;
                $case = new ElseIf_($cond, [$body]);
            }
            $closure->stmts = [new If_($ifCond, ['stmts' => [$ifBody], 'elseifs' => $cases, 'else' => new Else_([$default])])];
        }
        $phabelReturn = new FuncCall($closure, [new Arg($match->cond)]);
        if (!$phabelReturn instanceof FuncCall) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type FuncCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
