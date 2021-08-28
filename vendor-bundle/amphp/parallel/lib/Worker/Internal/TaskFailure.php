<?php

namespace Phabel\Amp\Parallel\Worker\Internal;

use Phabel\Amp\Failure;
use Phabel\Amp\Parallel\Sync;
use Phabel\Amp\Parallel\Worker\TaskFailureError;
use Phabel\Amp\Parallel\Worker\TaskFailureException;
use Phabel\Amp\Parallel\Worker\TaskFailureThrowable;
use Phabel\Amp\Promise;
/** @internal */
final class TaskFailure extends TaskResult
{
    const PARENT_EXCEPTION = 0;
    const PARENT_ERROR = 1;
    /** @var string */
    private $type;
    /** @var int */
    private $parent;
    /** @var string */
    private $message;
    /** @var int|string */
    private $code;
    /** @var string[] */
    private $trace;
    /** @var self|null */
    private $previous;
    public function __construct($id, \Throwable $exception)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        parent::__construct($id);
        $this->type = \get_class($exception);
        $this->parent = $exception instanceof \Error ? self::PARENT_ERROR : self::PARENT_EXCEPTION;
        $this->message = $exception->getMessage();
        $this->code = $exception->getCode();
        $this->trace = Sync\flattenThrowableBacktrace($exception);
        if ($previous = $exception->getPrevious()) {
            $this->previous = new self($id, $previous);
        }
    }
    public function promise()
    {
        $phabelReturn = new Failure($this->createException());
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function createException()
    {
        $previous = $this->previous ? $this->previous->createException() : null;
        if ($this->parent === self::PARENT_ERROR) {
            $phabelReturn = new TaskFailureError($this->type, $this->message, $this->code, $this->trace, $previous);
            if (!$phabelReturn instanceof TaskFailureThrowable) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type TaskFailureThrowable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = new TaskFailureException($this->type, $this->message, $this->code, $this->trace, $previous);
        if (!$phabelReturn instanceof TaskFailureThrowable) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type TaskFailureThrowable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
