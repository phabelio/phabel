<?php

namespace Phabel;

/**
 * Exception.
 */
class Exception extends \Exception
{
    private $trace;
    /**
     * Get trace.
     *
     * @return string
     */
    public function __toString() : string
    {
        return $this->trace ?? parent::__toString();
    }
    /**
     * Constructor.
     *
     * @param string $message
     * @param integer $code
     * @param \Throwable $previous
     * @param string $file
     * @param int $line
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null, string $file = '', int $line = -1)
    {
        if ($file !== '') {
            $this->file = $file;
        }
        if ($line !== -1) {
            $this->line = $line;
        }
        parent::__construct($message, $code, $previous);
    }
    /**
     * Set the value of trace.
     *
     * @param ?string $trace
     *
     * @return self
     */
    public function setTrace($trace) : self
    {
        if (!\is_null($trace)) {
            if (!\is_string($trace)) {
                if (!(\is_string($trace) || \Phabel\Target\Php72\Polyfill::is_object($trace) && \method_exists($trace, '__toString') || (\is_bool($trace) || \is_numeric($trace)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($trace) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($trace) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $trace = (string) $trace;
            }
        }
        $this->trace = $trace;
        return $this;
    }
}
