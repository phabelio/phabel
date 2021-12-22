<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use PhabelVendor\PhpParser\Comment\Doc;
use PhabelVendor\PhpParser\Node;
use PhabelVendor\PhpParser\Node\Expr\Array_;
use PhabelVendor\PhpParser\Node\Expr\ArrayDimFetch;
use PhabelVendor\PhpParser\Node\Expr\Assign;
use PhabelVendor\PhpParser\Node\Expr\Isset_;
use PhabelVendor\PhpParser\Node\Expr\Variable;
use PhabelVendor\PhpParser\Node\FunctionLike;
use PhabelVendor\PhpParser\Node\Identifier;
use PhabelVendor\PhpParser\Node\Param;
use PhabelVendor\PhpParser\Node\Stmt\If_;
use PhabelVendor\PhpParser\Node\Stmt\Return_;
use PhabelVendor\PhpParser\Node\Stmt\Static_;
use PhabelVendor\PhpParser\Node\Stmt\StaticVar;
use SplStack;
/**
 * Enable memoization of results based on a parameter.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Memoization extends Plugin
{
    /**
     * Stack of cache objects for current function.
     *
     * @var SplStack<null|ArrayDimFetch>
     */
    private SplStack $cache;
    /**
     * Constructor function.
     */
    public function __construct()
    {
        $this->cache = new SplStack();
    }
    /**
     * Enter functions.
     *
     * @param FunctionLike $node Function
     *
     * @return void
     */
    public function enterFunctionLike(FunctionLike $node, Context $ctx) : void
    {
        if (!\preg_match_all('/@memoize \\$([\\w\\d_]+)/', (string) ($node->getDocComment() ?? ''), $matches)) {
            $this->cache->push(null);
            return;
        }
        if ($node->getReturnType() instanceof Identifier && $node->getReturnType()->name === 'void') {
            throw new \RuntimeException('Cannot memoize void function');
        }
        /** @var Node[] */
        $toPrepend = [];
        /** @var string[] */
        $memoizeParams = $matches[1];
        /** @var array<string, Param> */
        $params = [];
        foreach ($node->getParams() as $param) {
            if (!$param->var instanceof Variable) {
                continue;
            }
            $params[$param->var->name] = $param;
        }
        /** @var Variable[] */
        $memoizeVars = [];
        foreach ($memoizeParams as $memoizeVar) {
            if (!isset($params[$memoizeVar])) {
                throw new \RuntimeException('Cannot find memoization parameter $' . $memoizeVar);
            }
            $memoizeParam = $params[$memoizeVar];
            if ($memoizeParam->type === null) {
                throw new \RuntimeException('Cannot memoize by untyped parameter $' . $memoizeVar);
            }
            if ($memoizeParam->type instanceof Identifier) {
                if ($memoizeParam->type->name === 'array') {
                    throw new \RuntimeException('Cannot memoize by array parameter $' . $memoizeVar);
                }
                if (\in_array($memoizeParam->type->name, ['string', 'int', 'float', 'bool'])) {
                    $memoizeVars[] = $memoizeParam->var;
                    continue;
                }
            }
            $toPrepend[] = Plugin::assign($variable = new Variable($memoizeParam->var->name . '___memo'), Plugin::call('spl_object_hash', $memoizeParam->var));
            $memoizeVars[] = $variable;
        }
        $toPrepend[] = new Static_([new StaticVar($cache = new Variable('memoizeCache'), new Array_())]);
        foreach ($memoizeVars as $var) {
            $cache = new ArrayDimFetch($cache, $var);
        }
        $toPrepend[] = new If_(new Isset_([$cache]), [new Return_($cache)]);
        $this->cache->push($cache);
        if (empty($toPrepend)) {
            return;
        }
        $ctx->toClosure($node);
        $node->stmts = \array_merge($toPrepend, $node->stmts);
        $node->setDocComment(new Doc(\str_replace("@memoize ", "@memoized ", $node->getDocComment())));
    }
    /**
     * Leave function.
     *
     * @param FunctionLike $fun Function
     *
     * @return void
     */
    public function leaveFunctionLike(FunctionLike $fun, Context $context) : void
    {
        $this->cache->pop();
    }
    /**
     * Enter return expression.
     *
     * @param Return_ $return Return expression
     *
     * @return void
     */
    public function enterReturn(Return_ $return)
    {
        if ($this->cache->count() && $this->cache->top()) {
            $return->expr = new Assign($this->cache->top(), $return->expr);
        }
    }
}
