<?php

namespace Phabel;

use Amp\Promise;

use function Amp\call;
use function Amp\Parallel\Worker\enqueueCallable;

/**
 * Async AST traverser.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TraverserAsync
{
    /**
     * Run phabel.
     *
     * @param array $plugins Plugins
     * @param string $input  Input file/directory
     * @param string $output Output file/directory
     * @param string $coverage Coverage prefix
     * @return Promise<array>
     */
    public static function run(array $plugins, string $input, string $output, string $coverage): Promise
    {
        if (!\interface_exists(Promise::class)) {
            throw new Exception("amphp/parallel must be installed to parallelize transforms!");
        }
        return call(function () use ($plugins, $input, $output, $coverage) {
            $result = [];

            if (!\file_exists($output)) {
                \mkdir($output, 0777, true);
            }
            $output = \realpath($output);

            $count = 0;
            $promises = [];
            $classStorage = null;

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
                        $promise = call(function () use ($plugins, $file, $targetPath, $coverage, $count, &$result, &$promises, &$classStorage) {
                            $res = yield enqueueCallable(
                                [self::class, 'runInternal'],
                                $plugins,
                                $file->getRealPath(),
                                $targetPath,
                                "$coverage$count.php"
                            );
                            if ($res instanceof ExceptionWrapper) {
                                throw $res->getException();
                            }
                            [$classes, $result] = $res;
                            if ($classStorage) {
                                $classStorage->merge($classes);
                            } elseif ($classes) {
                                $classStorage = $classes;
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

            if ($classStorage) {
                self::run($classStorage->finish(), $output, $output, $coverage);
            }

            return $result;
        });
    }

    /**
     * Run phabel.
     *
     * @param array $plugins   Plugins
     * @param string $input    Input file/directory
     * @param string $output   Output file/directory
     * @param string $coverage Coverage path
     *
     * @psalm-param array<class-string<PluginInterface>, array> $plugins
     *
     * @return array<string, string>|ExceptionWrapper
     */
    public static function runAsyncInternal(array $plugins, string $input, string $output, string $coverage = '')
    {
        try {
            return Traverser::runInternal($plugins, $input, $output, null, $coverage);
        } catch (\Throwable $e) {
            return new ExceptionWrapper($e);
        }
    }
}
