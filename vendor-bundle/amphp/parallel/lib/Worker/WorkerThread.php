<?php

namespace Phabel\Amp\Parallel\Worker;

use Phabel\Amp\Parallel\Context\Thread;
use Phabel\Amp\Parallel\Sync\Channel;
use Phabel\Amp\Promise;
/**
 * A worker thread that executes task objects.
 *
 * @deprecated ext-pthreads development has been halted, see https://github.com/krakjoe/pthreads/issues/929
 */
final class WorkerThread extends TaskWorker
{
    /**
     * @param string $envClassName Name of class implementing \Amp\Parallel\Worker\Environment to instigate.
     *     Defaults to \Amp\Parallel\Worker\BasicEnvironment.
     * @param string|null $bootstrapPath Path to custom autoloader.
     */
    public function __construct($envClassName = BasicEnvironment::class, $bootstrapPath = null)
    {
        if (!\is_string($envClassName)) {
            if (!(\is_string($envClassName) || \is_object($envClassName) && \method_exists($envClassName, '__toString') || (\is_bool($envClassName) || \is_numeric($envClassName)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($envClassName) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($envClassName) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $envClassName = (string) $envClassName;
            }
        }
        if (!\is_null($bootstrapPath)) {
            if (!\is_string($bootstrapPath)) {
                if (!(\is_string($bootstrapPath) || \is_object($bootstrapPath) && \method_exists($bootstrapPath, '__toString') || (\is_bool($bootstrapPath) || \is_numeric($bootstrapPath)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($bootstrapPath) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($bootstrapPath) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $bootstrapPath = (string) $bootstrapPath;
                }
            }
        }
        parent::__construct(new Thread(static function (Channel $channel, $className, $bootstrapPath = null) {
            if (!\is_string($className)) {
                if (!(\is_string($className) || \is_object($className) && \method_exists($className, '__toString') || (\is_bool($className) || \is_numeric($className)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($className) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($className) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $className = (string) $className;
                }
            }
            if (!\is_null($bootstrapPath)) {
                if (!\is_string($bootstrapPath)) {
                    if (!(\is_string($bootstrapPath) || \is_object($bootstrapPath) && \method_exists($bootstrapPath, '__toString') || (\is_bool($bootstrapPath) || \is_numeric($bootstrapPath)))) {
                        throw new \TypeError(__METHOD__ . '(): Argument #3 ($bootstrapPath) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($bootstrapPath) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $bootstrapPath = (string) $bootstrapPath;
                    }
                }
            }
            if ($bootstrapPath !== null) {
                if (!\is_file($bootstrapPath)) {
                    throw new \Error(\sprintf("No file found at bootstrap file path given '%s'", $bootstrapPath));
                }
                $phabel_6af5a9d497f5ab37 = \Phabel\Plugin\NestedExpressionFixer::returnMe(static function () use($bootstrapPath) {
                    require $bootstrapPath;
                })->bindTo(null, null);
                // Include file within unbound closure to protect scope.
                $phabel_6af5a9d497f5ab37();
            }
            if (!\class_exists($className)) {
                throw new \Error(\sprintf("Invalid environment class name '%s'", $className));
            }
            if (!\is_subclass_of($className, Environment::class)) {
                throw new \Error(\sprintf("The class '%s' does not implement '%s'", $className, Environment::class));
            }
            $environment = new $className();
            if (!\defined("AMP_WORKER")) {
                \define("AMP_WORKER", \AMP_CONTEXT);
            }
            $runner = new TaskRunner($channel, $environment);
            $phabelReturn = $runner->run();
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }, $envClassName, $bootstrapPath));
    }
}
