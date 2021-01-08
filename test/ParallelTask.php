<?php

namespace PhabelTest;

use Amp\Parallel\Worker\Environment;
use Amp\Parallel\Worker\Task;
use Amp\Parallel\Worker\TaskWorker;
use PhpParser\Node;
use PhpParser\Builder\Class_;
use PhpParser\Builder\Method;
use PhpParser\PrettyPrinter\Standard;

class ParallelTask implements Task
{
    private $node;
    private $count;
    public function __construct($node, $count)
    {
        $this->node = $node;
        $this->count = $count;
    }
    public function run(Environment $environment)
    {
        if (!$environment->exists('printer')) {
            $environment->set('printer', new Standard(['shortArraySyntax' => true]));
        }

        $code = (new Class_("lmao{$this->count}"))->addStmt(
            (new Method("te"))
            ->addStmt($this->node)
            ->getNode()
        )->getNode();
        return $environment->get('printer')->prettyPrintFile([$code]);
    }
}
