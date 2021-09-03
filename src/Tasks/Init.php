<?php

namespace Phabel\Tasks;

use Phabel\Amp\Parallel\Worker\Environment;
use Phabel\Amp\Parallel\Worker\Task;
use Phabel\Exception;
use Phabel\PluginGraph\ResolvedGraph;
use Phabel\Traverser;
class Init implements Task
{
    public function __construct(ResolvedGraph $graph)
    {
        $this->graph = $graph;
    }
    private $graph;
    public function run(Environment $environment)
    {
        if (\function_exists("cli_set_process_title")) {
            try {
                @\cli_set_process_title("Phabel - PHP transpiler worker");
            } catch (\Throwable $e) {
            }
        }
        \set_error_handler(function (int $errno = 0, string $errstr = '', string $errfile = '', int $errline = -1) : bool {
            // If error is suppressed with @, don't throw an exception
            if (\Phabel\Target\Php80\Polyfill::error_reporting() === 0) {
                return \false;
            }
            throw new Exception($errstr, $errno, null, $errfile, $errline);
        });
        $environment->set(Traverser::class, (new Traverser())->setGraph($this->graph));
    }
}
