<?php

namespace Spatie\Php7to5\NodeVisitors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;
use Spatie\Php7to5\Converter;
use Spatie\Php7to5\Exceptions\InvalidPhpCode;

class AnonymousClassReplacer extends NodeVisitorAbstract
{
    /**
     * @var array
     */
    protected $anonymousClassNodes = [];
    public static $count = 0;
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if (!$node instanceof Node\Expr\New_) {
            return;
        }

        $classNode = $node->class;
        if (!$classNode instanceof Node\Stmt\Class_) {
            return;
        }

        $newClassName = 'AnonymousClass'.(self::$count++);

        $classNode->name = $newClassName;

        $this->anonymousClassNodes[] = $classNode;

        // Generate new code that instantiate our new class
        $newNode = new Node\Expr\New_(
            new Node\Expr\ConstFetch(
                new Node\Name($newClassName)
            ),
            $node->args
        );

        return $newNode;
    }

    /**
     * {@inheritdoc}
     */
    public function afterTraverse(array $nodes)
    {
        if (count($this->anonymousClassNodes) === 0) {
            return $nodes;
        }

        $anonymousClassStatements = $this->anonymousClassNodes;

        $anonymousClassStatements = $this->convertToPhp5Statements($anonymousClassStatements);

        $hookIndex = $this->getAnonymousClassHookIndex($nodes);

        $nodes = $this->moveAnonymousClassesToHook($nodes, $hookIndex, $anonymousClassStatements);

        return $nodes;
    }

    /**
     * Find the index of the first statement that is not a declare, use or namespace statement.
     *
     * @param array $statements
     *
     * @return int
     *
     * @throws \Spatie\Php7to5\Exceptions\InvalidPhpCode
     */
    protected function getAnonymousClassHookIndex(array $statements)
    {
        $hookIndex = false;

        foreach ($statements as $index => $statement) {
            if (!$statement instanceof Declare_ &&
                !$statement instanceof Use_ &&
                !$statement instanceof Namespace_) {
                $hookIndex = $index;
            }
        }

        if ($hookIndex === false) {
            return 1;
            //throw InvalidPhpCode::noValidLocationFoundToInsertClasses();
        }

        return $hookIndex;
    }

    /**
     * @param array $nodes
     * @param $hookIndex
     * @param $anonymousClassStatements
     * 
     * @return array
     */
    protected function moveAnonymousClassesToHook(array $nodes, $hookIndex, $anonymousClassStatements)
    {
        $preStatements = array_slice($nodes, 0, $hookIndex);
        $postStatements = array_slice($nodes, $hookIndex);

        return array_merge($preStatements, $anonymousClassStatements, $postStatements);
    }

    /**
     * @param array $php7statements
     *
     * @return \PhpParser\Node[]
     */
    public function convertToPhp5Statements(array $php7statements)
    {
        $converter = Converter::getTraverser($php7statements);

        $php5Statements = $converter->traverse($php7statements);

        return $php5Statements;
    }
}
