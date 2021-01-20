<?php

namespace PhabelTest;

use Amp\Parallel\Worker\Environment;
use Amp\Parallel\Worker\Task;
use Amp\Promise;
use Phabel\Traverser;

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
     * Coverage output path.
     */
    private string $coverage;
    /**
     * Run phabel.
     *
     * @param array $plugins Plugins
     * @param string $input  Input file/directory
     * @param string $output Output file/directory
     * @param string $prefix Coverage prefix
     * @return Promise<array>
     */
    public static function runAsync(array $plugins, string $input, string $output, string $prefix): Promise
    {
        return call(function () use ($plugins, $input, $output, $prefix) {
            $result = [];

            if (!\file_exists($output)) {
                \mkdir($output, 0777, true);
            }

            $count = 0;
            $promises = [];

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
                        $promise = call(function () use ($plugins, $file, $targetPath, $prefix, $count, &$result, &$promises) {
                            $result = yield enqueue(new self($plugins, $file->getRealPath(), $targetPath, "$prefix$count.php"));
                            if ($result instanceof ExceptionWrapper) {
                                throw $result->getException();
                            }
                            unset($promises[$count]);
                        });
                        $promises[$count] = $promise;
                        if (!($count++ % 10)) {
                            yield $promise;
                        }
                    } elseif (\realpath($targetPath) !== $file->getRealPath()) {
                        \copy($file->getRealPath(), $targetPath);
                    }
                }
            }

            yield $promises;

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
    private function __construct(array $plugins, string $input, string $output, string $coverage)
    {
        $this->plugins = $plugins;
        $this->input = $input;
        $this->output = $output;
        $this->coverage = $coverage;
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
            return Traverser::run($this->plugins, $this->input, $this->output, $this->coverage);
        } catch (\Throwable $e) {
            return new ExceptionWrapper($e);
        }
    }
}
