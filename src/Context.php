<?php

namespace Phabel;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Param;
use SplStack;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Context
{
    /**
     * Parent nodes stack.
     *
     * @var SplStack<Node>
     */
    public SplStack $parents;
    /**
     * Declared variables stack.
     *
     * @var SplStack<VariableContext>
     */
    public SplStack $variables;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->parents = new SplStack;
        $this->variables = new SplStack;
    }
    /**
     * Push node.
     *
     * @param Node $node Node
     *
     * @return void
     */
    public function push(Node $node): void
    {
        $this->parents->push($node);
        if ($node instanceof RootNode) {
            $this->variables->push(new VariableContext);
        }
        if ($node instanceof FunctionLike) {
            $variables = \array_fill_keys(
                \array_map(
                    fn (Param $param): string => $param->var->name,
                    $node->getParams()
                ),
                true
            );
            if ($node instanceof Closure) {
                foreach ($node->uses as $use) {
                    $variables[$use->var->name] = true;
                    if ($use->byRef) {
                        $this->variables->top()->addVar($use->var->name);
                    }
                }
            } elseif ($node instanceof ArrowFunction) {
                $variables += $this->variables->top()->getVars();
            }
            $this->variables->push(new VariableContext($variables));
        } elseif ($node instanceof Assign || $node instanceof AssignOp || $node instanceof AssignRef) {
            do {
                $node = $node->var;
            } while ($node instanceof ArrayDimFetch && $node->var instanceof ArrayDimFetch);
            if ($node instanceof Variable && \is_string($node->name)) {
                $this->variables->top()->addVar($node->name);
            }
        } elseif ($node instanceof MethodCall || $node instanceof StaticCall || $node instanceof FuncCall) {
            // Cover reference parameters
            foreach ($node->args as $argument) {
                $argument = $argument->value;
                do {
                    $argument = $argument->var;
                } while ($argument instanceof ArrayDimFetch && $argument->var instanceof ArrayDimFetch);
                if ($argument instanceof Variable && \is_string($argument->name)) {
                    $this->variables->top()->addVar($argument->name);
                }
            }
        }
    }
    /**
     * Pop node.
     *
     * @return void
     */
    public function pop(): void
    {
        $popped = $this->parents->pop();
        if ($popped instanceof RootNode || ($popped instanceof FunctionLike && !$popped instanceof ArrowFunction)) {
            $this->variables->pop();
        }
    }
    /**
     * Return new unoccupied variable.
     *
     * @return Variable
     */
    public function getVariable(): Variable
    {
        return new Variable($this->parents->top()->getVar());
    }
    /**
     * Insert nodes before node.
     *
     * @param Node $node
     * @param Node ...$nodes
     * @return void
     */
    public function insertBefore(Node $node, Node ...$nodes): void
    {
        if (empty($nodes)) {
            return;
        }
        $subNode = $node->getAttribute('currentNode');
        $subNodeIndex = $node->getAttribute('currentNodeIndex');
        \array_splice($node->{$subNode}, $subNodeIndex, 0, $nodes);
        $skips = $node->getAttribute('skipNodes', []);
        $skips []= $subNodeIndex+\count($nodes);
        $node->setAttribute('skipNodes', $skips);
        $node->setAttribute('currentNodeIndex', $subNodeIndex - 1);
    }
    /**
     * Insert nodes after node.
     *
     * @param Node $node
     * @param Node ...$nodes
     * @return void
     */
    public function insertAfter(Node $node, Node ...$nodes): void
    {
        $subNode = $node->getAttribute('currentNode');
        $subNodeIndex = $node->getAttribute('currentNodeIndex');
        \array_splice($node->{$subNode}, $subNodeIndex+1, 0, $nodes);
    }
}
