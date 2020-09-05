<?php

namespace Phabel;

use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Cast\Bool_;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\If_;
use SplStack;

/**
 * AST Context.
 *
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
     * Get child currently being iterated on.
     *
     * @param Node $node
     * @return Node
     */
    public static function getCurrentChild(Node $node): Node
    {
        if (!$subNode = $node->getAttribute('currentNode')) {
            throw new \RuntimeException('Node is not a part of the current AST stack!');
        }
        $child = $node->{$subNode};
        if ($index = $node->getAttribute('currentNodeIndex')) {
            return $child[$index];
        }
        return $child;
    }
    /**
     * Insert nodes before node.
     *
     * @param Node $node     Node before which to insert nodes
     * @param Node ...$nodes Nodes to insert
     * @return void
     */
    public function insertBefore(Node $node, Node ...$nodes): void
    {
        if (empty($nodes)) {
            return;
        }
        $found = false;
        foreach ($this->parents as $parent) {
            if ($this->getCurrentChild($parent) === $node) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            throw new \RuntimeException('Node is not a part of the current AST stack!');
        }
        $this->insertBeforeParent($parent, $nodes);
    }
    /**
     * Insert nodes before node.
     *
     * @param Node   $node  Node before which to insert nodes
     * @param Node[] $nodes Nodes to insert
     * @return void
     */
    private function insertBeforeParent(Node $parent, array $nodes): void
    {
        $subNode = $parent->getAttribute('currentNode');
        if ($subNode === 'stmts') {
            $subNodeIndex = $parent->getAttribute('currentNodeIndex');
            \array_splice($parent->{$subNode}, $subNodeIndex, 0, $nodes);
            $skips = $parent->getAttribute('skipNodes', []);
            $skips []= $subNodeIndex+\count($nodes);
            $parent->setAttribute('skipNodes', $skips);
            $parent->setAttribute('currentNodeIndex', $subNodeIndex - 1);
        } else { // Cannot insert, is not in a statement
            $curNode = &$parent->{$subNode};
            if ($curNode instanceof BooleanOr && $subNode === 'right') {
                $result = $this->getVariable();
                $nodes = new If_(
                    $curNode->left,
                    [
                        'stmts' => [
                            new Assign($result, BuilderHelpers::normalizeValue(true))
                        ],
                        'else' => [
                            ...$nodes,
                            new Assign($result, new Bool_($curNode->right))
                        ]
                    ]
                );
                $curNode = $result;
            } elseif ($curNode instanceof BooleanAnd && $subNode === 'right') {
                $result = $this->getVariable();
                $nodes = new If_(
                    $curNode->left,
                    [
                        'stmts' => [
                            ...$nodes,
                            new Assign($result, new Bool_($curNode->right))
                        ],
                        'else' => [
                            new Assign($result, BuilderHelpers::normalizeValue(false))
                        ]
                    ]
                );
                $curNode = $result;
            } elseif ($curNode instanceof Ternary && $subNode !== 'cond') {
                $result = $this->getVariable();
                if (!$curNode->if) { // ?:
                    $nodes = new If_(
                        new BooleanNot(
                            new Assign($result, $curNode->cond)
                        ),
                        [
                            'stmts' => [
                                ...$nodes,
                                new Assign($result, $curNode->else)
                            ]
                        ]
                    );
                } else {
                    $nodes = new If_(
                        $curNode->cond,
                        [
                            'stmts' => [
                                ...$subNode === 'if' ? $nodes : [],
                                new Assign($result, $curNode->if)
                            ],
                            'else' => [
                                ...$subNode === 'else' ? $nodes : [],
                                new Assign($result, $curNode->else)
                            ]
                        ]
                    );
                }
                $curNode = $result;
            } elseif ($subNode instanceof Coalesce && $subNode === 'right') {
                $result = $this->getVariable();
                $nodes = new If_(
                    Plugin::call(
                        'is_null',
                        new Assign($result, $curNode->left)
                    ),
                    [
                        'stmts' => [
                            ...$nodes,
                            new Assign($result, $curNode->right)
                        ]
                    ]
                );
                $curNode = $result;
            } elseif ($subNode instanceof FuncCall) {
            }
            $this->insertBefore($parent, $nodes);
        }
    }
    /**
     * Insert nodes after node.
     *
     * @param Node $node     Node ater which to insert nodes
     * @param Node ...$nodes Nodes to insert
     * @return void
     */
    public function insertAfter(Node $node, Node ...$nodes): void
    {
        if (empty($nodes)) {
            return;
        }
        $found = false;
        foreach ($this->parents as $parent) {
            if ($this->getCurrentChild($parent) === $node) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            throw new \RuntimeException('Node is not a part of the current AST stack!');
        }
        $subNode = $parent->getAttribute('currentNode');
        $subNodeIndex = $parent->getAttribute('currentNodeIndex');
        \array_splice($parent->{$subNode}, $subNodeIndex+1, 0, $nodes);
    }
}
