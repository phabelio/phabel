<?php

namespace Phabel\Tasks;

use Amp\Parallel\Worker\Environment;
use Amp\Parallel\Worker\Task;
use Phabel\Exception;
use Phabel\PluginGraph\ResolvedGraph;
use Phabel\Traverser;

class Init implements Task
{
    public function __construct(private ResolvedGraph $graph)
    {
    }
    public function run(Environment $environment)
    {
        \set_error_handler(
            function (int $errno = 0, string $errstr = '', string $errfile = '', int $errline = -1): bool {
                // If error is suppressed with @, don't throw an exception
                if (\error_reporting() === 0) {
                    return false;
                }
                throw new Exception($errstr, $errno, null, $errfile, $errline);
            }
        );
        $environment->set(Traverser::class, (new Traverser())->setGraph($this->graph));
    }
}
