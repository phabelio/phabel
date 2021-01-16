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
     * Instance counter.
     */
    private int $instCount = 0;
    /**
     * Coverage output path.
     */
    private string $coverageOutput;
    /**
     * Code coverage instance.
     */
    private ?CodeCoverage $coverage = null;
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
            $result = [];

            if (!\file_exists($output)) {
                \mkdir($output, 0777, true);
            }

            $it = new \RecursiveDirectoryIterator($input, \RecursiveDirectoryIterator::SKIP_DOTS);
            $ri = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::SELF_FIRST);
            /** @var \SplFileInfo $file */
            foreach ($ri as $file) {
                $targetPath = $output.DIRECTORY_SEPARATOR.$ri->getSubPathname();
                if ($file->isDir()) {
                    if (!\file_exists($targetPath)) {
                        \mkdir($targetPath, 0777, true);
                    }
                } elseif ($file->isFile()) {
                    if ($file->getExtension() == 'php') {
                        $result []= enqueue(new self($plugins, $input, $output, $prefix));
                    } elseif (\realpath($targetPath) !== $file->getRealPath()) {
                        \copy($file->getRealPath(), $targetPath);
                    }
                }
            }
            foreach ($result as $r) {
                $r->onResolve(function ($e, $result) {
                    if ($e) {
                        throw $e;
                    }
                    if ($result instanceof ExceptionWrapper) {
                        throw $result->getException();
                    }    
                });
            }

            $result = yield $result;
            return $result[0];
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
        $this->coverageOutput = __DIR__."/../coverage/$prefix";
    }
    private function startCoverage(): void
    {
        try {
            $filter = new Filter;
            $filter->includeDirectory(\realpath(__DIR__.'/../src'));

            $this->coverage = new CodeCoverage(
                (new Selector)->forLineCoverage($filter),
                $filter
            );
            $this->coverage->start('phabel');
        } catch (\Throwable $e) {
        }
    }
    private function stopCoverage(): void
    {
        if ($this->coverage) {
            $this->coverage->stop();
            $output = $this->coverageOutput;
            $output .= "_";
            $output .= $this->instCount++;
            $output .= ".php";
            (new ReportPHP)->process($this->coverage, $output);
            $this->coverage = null;
        }
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
        try {
            $this->startCoverage();
            $result = Traverser::run($this->plugins, $this->input, $this->output);
            $this->stopCoverage();
            return $result;
        } catch (\Throwable $e) {
            return new ExceptionWrapper($e);
        }
    }
}
