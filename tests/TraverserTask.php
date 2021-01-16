<?php

namespace PhabelTest;

use Amp\Parallel\Worker\Environment;
use Amp\Parallel\Worker\Task;
use Amp\Promise;
use Phabel\Traverser;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Report\PHP as ReportPHP;

use function Amp\call;
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
     * Static counter.
     */
    private static int $count = 0;
    /**
     * Coverage output path.
     */
    private string $coverageOutput;
    /**
     * Run phabel.
     *
     * @param array $plugins Plugins
     * @param string $input  Input file/directory
     * @param string $output Output file/directory
     * @param string $prefix Coverage prefix
     * @return Promise<array>
     */
    public static function runAsync(array $plugins, string $input, string $output, string $prefix = 'transpiler'): Promise
    {
        return call(function () use ($plugins, $input, $output, $prefix) {
            $result = yield enqueue(new self($plugins, $input, $output, $prefix));
            if ($result instanceof ExceptionWrapper) {
                throw $result->getException();
            }
            return $result;
        });
    }
    /**
     * Constructor.
     *
     * @param array $plugins
     * @param string $input
     * @param string $output
     * @param string $prefix
     */
    private function __construct(array $plugins, string $input, string $output, string $prefix)
    {
        $this->plugins = $plugins;
        $this->input = $input;
        $this->output = $output;

        $prefix .= self::$count++;
        $this->coverageOutput = __DIR__."/../coverage/$prefix.php";
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
        $coverage = null;
        try {
            $filter = new Filter;
            $filter->includeDirectory(\realpath(__DIR__.'/../src'));

            $coverage = new CodeCoverage(
                (new Selector)->forLineCoverage($filter),
                $filter
            );
            $coverage->start('phabel');
        } catch (\Throwable $e) {
        }

        try {
            $result = Traverser::run($this->plugins, $this->input, $this->output);
            if ($coverage) {
                $coverage->stop();
                (new ReportPHP)->process($coverage, $this->coverageOutput);
            }
            return $result;
        } catch (\Throwable $e) {
            return new ExceptionWrapper($e);
        }
    }
}
