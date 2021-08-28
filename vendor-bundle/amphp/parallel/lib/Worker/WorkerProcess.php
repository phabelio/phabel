<?php

namespace Phabel\Amp\Parallel\Worker;

/**
 * A worker process that executes task objects.
 */
final class WorkerProcess extends TaskWorker
{
    const SCRIPT_PATH = __DIR__ . "/Internal/worker-process.php";
    /**
     * @param string      $envClassName  Name of class implementing \Amp\Parallel\Worker\Environment to instigate.
     *     Defaults to \Amp\Parallel\Worker\BasicEnvironment.
     * @param mixed[]     $env           Array of environment variables to pass to the worker. Empty array inherits from the current
     *     PHP process. See the $env parameter of \Amp\Process\Process::__construct().
     * @param string|null $binary        Path to PHP binary. Null will attempt to automatically locate the binary.
     * @param string|null $bootstrapPath Path to custom bootstrap file.
     *
     * @throws \Error If the PHP binary path given cannot be found or is not executable.
     */
    public function __construct($envClassName = BasicEnvironment::class, array $env = [], $binary = null, $bootstrapPath = null)
    {
        if (!\is_string($envClassName)) {
            if (!(\is_string($envClassName) || \is_object($envClassName) && \method_exists($envClassName, '__toString') || (\is_bool($envClassName) || \is_numeric($envClassName)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($envClassName) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($envClassName) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $envClassName = (string) $envClassName;
            }
        }
        if (!\is_null($binary)) {
            if (!\is_string($binary)) {
                if (!(\is_string($binary) || \is_object($binary) && \method_exists($binary, '__toString') || (\is_bool($binary) || \is_numeric($binary)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($binary) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($binary) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $binary = (string) $binary;
                }
            }
        }
        if (!\is_null($bootstrapPath)) {
            if (!\is_string($bootstrapPath)) {
                if (!(\is_string($bootstrapPath) || \is_object($bootstrapPath) && \method_exists($bootstrapPath, '__toString') || (\is_bool($bootstrapPath) || \is_numeric($bootstrapPath)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($bootstrapPath) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($bootstrapPath) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $bootstrapPath = (string) $bootstrapPath;
                }
            }
        }
        $script = [self::SCRIPT_PATH, $envClassName];
        if ($bootstrapPath !== null) {
            $script[] = $bootstrapPath;
        }
        parent::__construct(new Internal\WorkerProcess($script, $env, $binary));
    }
}
