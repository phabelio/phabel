<?php

namespace PhabelTest;

use Amp\Parallel\Worker\Environment;
use Amp\Parallel\Worker\Task;
use Amp\Promise;
use Phabel\Traverser;

use function Amp\Parallel\Worker\enqueue;

class TraverserTask implements Task
{
    /**
     * Plugins.
     */
    private array $plugins;
    /**
     * Input.
     */
    private string $input;
    /**
     * Output.
     */
    private string $output;
    /**
     * Run phabel.
     *
     * @param array $plugins Plugins
     * @param string $input  Input file/directory
     * @param string $output Output file/directory
     * @return Promise<array>
     */
    public static function runAsync(array $plugins, string $input, string $output): Promise
    {
        return enqueue(new self($plugins, $input, $output));
    }
    /**
     * Constructor.
     *
     * @param array $plugins
     * @param string $input
     * @param string $output
     */
    private function __construct(array $plugins, string $input, string $output)
    {
        $this->plugins = $plugins;
        $this->input = $input;
        $this->output = $output;
    }
    /**
     * Runs the task inside the caller's context.
     *
     * Does not have to be a coroutine, can also be a regular function returning a value.
     *
     * @param \Amp\Parallel\Worker\Environment
     *
     * @return mixed|\Amp\Promise|\Generator
     */
    public function run(Environment $environment)
    {
        return Traverser::run($this->plugins, $this->input, $this->output);
    }
}
