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
    public function __toString()
    {
        $phabelReturn = isset($this->trace) ? $this->trace : parent::__toString();
        if (!\is_string($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
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
    public function __construct($message = '', $code = 0, \Throwable $previous = null, $file = '', $line = -1)
    {
        if (!\is_string($message)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_int($code)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($code) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($code) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($file)) {
            throw new \TypeError(__METHOD__ . '(): Argument #4 ($file) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_int($line)) {
            throw new \TypeError(__METHOD__ . '(): Argument #5 ($line) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($line) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
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
    public function setTrace($trace)
    {
        if (!(\is_string($trace) || \is_null($trace))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($trace) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($trace) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $this->trace = $trace;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
