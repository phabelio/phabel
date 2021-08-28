<?php

namespace Phabel\Amp\Parallel\Sync;

final class ExitFailure implements ExitResult
{
    /** @var string */
    private $type;
    /** @var string */
    private $message;
    /** @var int|string */
    private $code;
    /** @var string[] */
    private $trace;
    /** @var self|null */
    private $previous;
    public function __construct(\Throwable $exception)
    {
        $this->type = \get_class($exception);
        $this->message = $exception->getMessage();
        $this->code = $exception->getCode();
        $this->trace = flattenThrowableBacktrace($exception);
        if ($previous = $exception->getPrevious()) {
            $this->previous = new self($previous);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        throw $this->createException();
    }
    private function createException()
    {
        $previous = $this->previous ? $this->previous->createException() : null;
        $phabelReturn = new ContextPanicError($this->type, $this->message, $this->code, $this->trace, $previous);
        if (!$phabelReturn instanceof ContextPanicError) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ContextPanicError, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
