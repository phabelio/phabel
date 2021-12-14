<?php

namespace Phabel\Tasks;

use Amp\Parallel\Worker\Environment;
use Amp\Parallel\Worker\Task;
use Phabel\Traverser;

class Shutdown implements Task
{
    /**
     *
     */
    public function run(Environment $environment)
    {
        /** @var Traverser */
        $traverser = $environment->get(Traverser::class);
        $environment->delete(Traverser::class);
        return $traverser->getGraph()->getClassStorage();
    }
}
