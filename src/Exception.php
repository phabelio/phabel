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
    public function setTrace(?string $trace) : self
    {
        $this->trace = $trace;
        return $this;
    }
}
