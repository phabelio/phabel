<?php

namespace Phabel\Tasks;

use PhabelVendor\Amp\Parallel\Worker\Environment;
use PhabelVendor\Amp\Parallel\Worker\Task;
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
        if (\function_exists("cli_set_process_title")) {
            try {
                @\cli_set_process_title("Phabel - PHP transpiler worker");
            } catch (\Throwable $e) {
            }
        }
        \set_error_handler(function (int $errno = 0, string $errstr = '', string $errfile = '', int $errline = -1) : bool {
            // If error is suppressed with @, don't throw an exception
            if (\error_reporting() === 0) {
                return \false;
            }
            throw new Exception($errstr, $errno, null, $errfile, $errline);
        });
        $environment->set(Traverser::class, (new Traverser())->setGraph($this->graph));
    }
}
