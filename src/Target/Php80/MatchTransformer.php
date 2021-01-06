<?php

namespace Phabel\Target\Php80;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Plugin\ArrowClosureVariableFinder;
use Phabel\Traverser;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Match_;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Throw_;

/**
 * Polyfill match expression.
 */
class MatchTransformer extends Plugin
{
    private ArrowClosureVariableFinder $finder;
    private Traverser $finderTraverser;
    public function __construct()
    {
        $this->finder = new ArrowClosureVariableFinder;
        $this->finder->setConfigArray(['byRef' => true]);
        $this->finderTraverser = Traverser::fromPlugin($this->finder);
    }
    public function enter(Match_ $match, Context $context): FuncCall
    {
        $this->finderTraverser->traverseAst($match);
        $closure = new Closure(
            [
                'params' => [new Param($var = $context->getVariable())],
                'uses' => $this->finder->getFound()
            ]
        );


        $cases = [];
        $default = null;
        foreach ($match->arms as $arm) {
            if ($arm->conds === null) {
                $default = $arm->body;
                continue;
            }
            foreach ($arm->conds as $cond) {
                $cases []= [new Identical($var, $cond), $arm->body];
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
            $closure->stmts = [
                new If_(
                    $ifCond,
                    [
                        'stmts' => [$ifBody],
                        'elseifs' => $cases,
                        'else' => new Else_([$default])
                    ]
                )
            ];
        }

        return new FuncCall($closure, [new Arg($match->cond)]);
    }
}
