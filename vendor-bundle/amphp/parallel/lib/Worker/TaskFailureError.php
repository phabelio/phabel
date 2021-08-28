<?php

namespace Phabel\Amp\Parallel\Worker;

use function Phabel\Amp\Parallel\Sync\formatFlattenedBacktrace;
final class TaskFailureError extends TaskError implements TaskFailureThrowable
{
    /** @var string */
    private $originalMessage;
    /** @var int|string */
    private $originalCode;
    /** @var string[] */
    private $originalTrace;
    /**
     * @param string                    $className Original exception class name.
     * @param string                    $message   Original exception message.
     * @param int|string                $code      Original exception code.
     * @param array                     $trace     Backtrace generated by
     *                                             {@see \Amp\Parallel\Sync\flattenThrowableBacktrace()}.
     * @param TaskFailureThrowable|null $previous  Instance representing any previous exception thrown in the Task.
     */
    public function __construct($className, $message, $code, $trace, $previous = null)
    {
        if (!\is_array($trace)) {
            throw new \TypeError(__METHOD__ . '(): Argument #4 ($trace) must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($trace) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($className)) {
            if (!(\is_string($className) || \is_object($className) && \method_exists($className, '__toString') || (\is_bool($className) || \is_numeric($className)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($className) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($className) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $className = (string) $className;
            }
        }
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        if (!($previous instanceof TaskFailureThrowable || \is_null($previous))) {
            throw new \TypeError(__METHOD__ . '(): Argument #5 ($previous) must be of type ?TaskFailureThrowable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($previous) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $format = 'Uncaught %s in worker with message "%s" and code "%s"; use %s::getOriginalTrace() for the stack trace in the worker';
        parent::__construct($className, \sprintf($format, $className, $message, $code, self::class), formatFlattenedBacktrace($trace), $previous);
        $this->originalMessage = $message;
        $this->originalCode = $code;
        $this->originalTrace = $trace;
    }
    /**
     * @return string Original exception class name.
     */
    public function getOriginalClassName()
    {
        $phabelReturn = $this->getName();
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * @return string Original exception message.
     */
    public function getOriginalMessage()
    {
        $phabelReturn = $this->originalMessage;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * @return int|string Original exception code.
     */
    public function getOriginalCode()
    {
        return $this->originalCode;
    }
    /**
     * Returns the original exception stack trace.
     *
     * @return array Same as {@see Throwable::getTrace()}, except all function arguments are formatted as strings.
     */
    public function getOriginalTrace()
    {
        $phabelReturn = $this->originalTrace;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Original backtrace flattened to a human-readable string.
     *
     * @return string
     */
    public function getOriginalTraceAsString()
    {
        $phabelReturn = $this->getWorkerTrace();
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
