<?php

namespace Phabel;

use PhpParser\BuilderHelpers;
use PhpParser\ErrorHandler\Throwing;
use PhpParser\NameContext;
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
use PhpParser\NodeVisitor\NameResolver;
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
     * Name resolver.
     *
     * @var NameResolver
     */
    public NameResolver $nameResolver;
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @var SplStack<Node> */
        $this->parents = new SplStack;
        /** @var SplStack<VariableContext> */
        $this->variables = new SplStack;
        $this->nameResolver = new NameResolver(new Throwing, ['replaceNodes' => false]);
        $this->nameResolver->beforeTraverse([]);
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
        } else {
            $this->nameResolver->enterNode($node);
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
                while ($argument instanceof ArrayDimFetch && $argument->var instanceof ArrayDimFetch) {
                    $argument = $argument->var;
                }
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
        if ($popped instanceof RootNode || $popped instanceof FunctionLike) {
            $poppedVars = $this->variables->pop();
            if ($popped instanceof ArrowFunction) {
                $this->variables->top()->addVars($poppedVars->getVars());
            }
        }
    }
    /**
     * Return new unoccupied variable.
     *
     * @return Variable
     */
    public function getVariable(): Variable
    {
        return new Variable($this->variables->top()->getVar());
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
        if (null !== $index = $node->getAttribute('currentNodeIndex')) {
            return $child[$index];
        }
        return $child;
    }
    /**
     * Insert nodes before node.
     *
     * @param Node $node      Node before which to insert nodes
     * @param Node ...$insert Nodes to insert
     * @return void
     */
    public function insertBefore(Node $node, Node ...$insert): void
    {
        if (empty($insert)) {
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

        /** @var string */
        $nodeKey = $parent->getAttribute('currentNode');
        if ($nodeKey === 'stmts') {
            /** @var int */
            $nodeKeyIndex = $parent->getAttribute('currentNodeIndex');
            \array_splice($parent->{$nodeKey}, $nodeKeyIndex, 0, $insert);
            $skips = $parent->getAttribute('skipNodes', []);
            $skips []= $nodeKeyIndex+\count($insert);
            $parent->setAttribute('skipNodes', $skips);
            $parent->setAttribute('currentNodeIndex', $nodeKeyIndex - 1);
            return; // Done, inserted!
        }

        // Cannot insert, parent is not a statement
        $node = &$parent->{$nodeKey};
        // If we insert before a conditional branch of a conditional expression,
        //   make sure the conditional branch has no side effects;
        //   if it does, turn the entire conditional expression into an if, and bubble it up
        //
        // Unless we want to go crazy, do not consider side effect evaluation order for stuff like function call arguments, maths and so on.
        //
        if ($node instanceof BooleanOr && $nodeKey === 'right' && Tools::hasSideEffects($node->right)) {
            $result = $this->getVariable();
            $insert = new If_(
                $node->left,
                [
                    'stmts' => [
                        new Assign($result, BuilderHelpers::normalizeValue(true))
                    ],
                    'else' => [
                        ...$insert,
                        new Assign($result, new Bool_($node->right))
                    ]
                ]
            );
            $node = $result;
        } elseif ($node instanceof BooleanAnd && $nodeKey === 'right' && Tools::hasSideEffects($node->right)) {
            $result = $this->getVariable();
            $insert = new If_(
                $node->left,
                [
                    'stmts' => [
                        ...$insert,
                        new Assign($result, new Bool_($node->right))
                    ],
                    'else' => [
                        new Assign($result, BuilderHelpers::normalizeValue(false))
                    ]
                ]
            );
            $node = $result;
        } elseif ($node instanceof Ternary && $nodeKey !== 'cond' && (Tools::hasSideEffects($node->if) || Tools::hasSideEffects($node->else))) {
            $result = $this->getVariable();
            if (!$node->if) { // ?:
                $insert = new If_(
                    new BooleanNot(
                        new Assign($result, $node->cond)
                    ),
                    [
                        'stmts' => [
                            ...$insert,
                            new Assign($result, $node->else)
                        ]
                    ]
                );
            } else {
                $insert = new If_(
                    $node->cond,
                    [
                        'stmts' => [
                            ...$nodeKey === 'if' ? $insert : [],
                            new Assign($result, $node->if)
                        ],
                        'else' => [
                            ...$nodeKey === 'else' ? $insert : [],
                            new Assign($result, $node->else)
                        ]
                    ]
                );
            }
            $node = $result;
        } elseif ($node instanceof Coalesce && $nodeKey === 'right' && Tools::hasSideEffects($node->right)) {
            $result = $this->getVariable();
            $insert = new If_(
                Plugin::call(
                    'is_null',
                    new Assign($result, $node->left)
                ),
                [
                    'stmts' => [
                        ...$insert,
                        new Assign($result, $node->right)
                    ]
                ]
            );
            $node = $result;
        }
        $this->insertBefore($parent, ...(\is_array($insert) ? $insert : [$insert]));
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

    /**
     * Gets name context.
     *
     * @return NameContext
     */
    public function getNameContext(): NameContext
    {
        return $this->nameResolver->getNameContext();
    }
}
