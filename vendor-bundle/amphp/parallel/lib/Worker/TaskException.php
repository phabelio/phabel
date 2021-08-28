<?php

namespace Phabel\Amp\Parallel\Worker;

/**
 * @deprecated TaskFailureException will be thrown from failed Tasks instead of this class.
 */
class TaskException extends \Exception
{
    /** @var string Class name of exception thrown from task. */
    private $name;
    /** @var string Stack trace of the exception thrown from task. */
    private $trace;
    /**
     * @param string          $name     The exception class name.
     * @param string          $message  The panic message.
     * @param string          $trace    The panic stack trace.
     * @param \Throwable|null $previous Previous exception.
     */
    public function __construct($name, $message = '', $trace = '', $previous = null)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        if (!\is_string($trace)) {
            if (!(\is_string($trace) || \is_object($trace) && \method_exists($trace, '__toString') || (\is_bool($trace) || \is_numeric($trace)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($trace) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($trace) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $trace = (string) $trace;
            }
        }
        if (!($previous instanceof \Exception || $previous instanceof \Error || \is_null($previous))) {
            throw new \TypeError(__METHOD__ . '(): Argument #4 ($previous) must be of type ?Throwable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($previous) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        parent::__construct($message, 0, $previous);
        $this->name = $name;
        $this->trace = $trace;
    }
    /**
     * @deprecated Use TaskFailureThrowable::getOriginalClassName() instead.
     *
     * Returns the class name of the exception thrown from the task.
     *
     * @return string
     */
    public function getName()
    {
        $phabelReturn = $this->name;
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
     * @deprecated Use TaskFailureThrowable::getOriginalTraceAsString() instead.
     *
     * Gets the stack trace at the point the exception was thrown in the task.
     *
     * @return string
     */
    public function getWorkerTrace()
    {
        $phabelReturn = $this->trace;
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
