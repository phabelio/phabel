<?php

namespace Phabel\Amp\Parallel\Worker;

use Phabel\Amp\Parallel\Context\Parallel;
use Phabel\Amp\Parallel\Context\Thread;
/**
 * The built-in worker factory type.
 */
final class DefaultWorkerFactory implements WorkerFactory
{
    /** @var string */
    private $className;
    /**
     * @param string $envClassName Name of class implementing \Amp\Parallel\Worker\Environment to instigate in each
     *     worker. Defaults to \Amp\Parallel\Worker\BasicEnvironment.
     *
     * @throws \Error If the given class name does not exist or does not implement {@see Environment}.
     */
    public function __construct($envClassName = BasicEnvironment::class)
    {
        if (!\is_string($envClassName)) {
            if (!(\is_string($envClassName) || \is_object($envClassName) && \method_exists($envClassName, '__toString') || (\is_bool($envClassName) || \is_numeric($envClassName)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($envClassName) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($envClassName) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $envClassName = (string) $envClassName;
            }
        }
        if (!\class_exists($envClassName)) {
            throw new \Error(\sprintf("Invalid environment class name '%s'", $envClassName));
        }
        if (!\is_subclass_of($envClassName, Environment::class)) {
            throw new \Error(\sprintf("The class '%s' does not implement '%s'", $envClassName, Environment::class));
        }
        $this->className = $envClassName;
    }
    /**
     * {@inheritdoc}
     *
     * The type of worker created depends on the extensions available. If multi-threading is enabled, a WorkerThread
     * will be created. If threads are not available a WorkerProcess will be created.
     */
    public function create()
    {
        if (Parallel::isSupported()) {
            $phabelReturn = new WorkerParallel($this->className);
            if (!$phabelReturn instanceof Worker) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Worker, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (Thread::isSupported()) {
            $phabelReturn = new WorkerThread($this->className);
            if (!$phabelReturn instanceof Worker) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Worker, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = new WorkerProcess($this->className, [], \getenv("AMP_PHP_BINARY") ?: (\defined("AMP_PHP_BINARY") ? \AMP_PHP_BINARY : null));
        if (!$phabelReturn instanceof Worker) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Worker, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
