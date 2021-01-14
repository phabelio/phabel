<?php

namespace PhabelTest;

use Amp\Parallel\Worker\Environment;
use Amp\Parallel\Worker\Task;
use Amp\Promise;
use Amp\Success;
use Phabel\Traverser;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use SebastianBergmann\CodeCoverage\Filter;

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
     * Code coverage.
     */
    private static ?CodeCoverage $coverage = null;
    /**
     * Run phabel.
     *
     * @param array $plugins Plugins
     * @param string $input  Input file/directory
     * @param string $output Output file/directory
     * @param bool   $async  Whether to enable parallelization
     * @return Promise<array>
     */
    public static function runAsync(array $plugins, string $input, string $output, bool $async = true): Promise
    {
        if (!$async) {
            if (!self::$coverage) {
                try {
                    $filter = new Filter;
                    $filter->includeDirectory(\realpath(__DIR__.'/../src'));

                    self::$coverage = new CodeCoverage(
                        (new Selector)->forLineCoverage($filter),
                        $filter
                    );
                    self::$coverage->start('phabel');
                } catch (\Throwable $e) {
                }
            } else {
                self::$coverage->start('phabel')
            } 
            $res = Traverser::run($plugins, $input, $output);
            if (self::$coverage) {
                self::$coverage->stop();
            }
            return new Success($res);
        }
        return call(function () use ($plugins, $input, $output) {
            $result = yield enqueue(new self($plugins, $input, $output));
            if ($result instanceof ExceptionWrapper) {
                throw $result->getException();
            }
            [$result, $coverage] = $result;
            if ($coverage) {
                if (self::$coverage) {
                    self::$coverage->merge($coverage, 'phabel');
                } else {
                    self::$coverage = $coverage;
                }
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
        if ($environment->exists('coverage')) {
            $coverage = $environment->get('coverage');
        } else {
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
            $environment->set('coverage', $coverage);
        }
        if ($coverage) {
            $coverage->start('phabel');
        }

        try {
            $result = Traverser::run($this->plugins, $this->input, $this->output);
            if ($coverage) {
                $coverage->stop();
            }
            return [$result, $coverage];
        } catch (\Throwable $e) {
            return new ExceptionWrapper($e);
        }
    }

    /**
     * Get code coverage.
     *
     * @return ?CodeCoverage
     */
    public static function getCoverage(): ?CodeCoverage
    {
        return self::$coverage;
    }
}
