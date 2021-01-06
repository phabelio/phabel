<?php

namespace Phabel;

/**
 * Exception.
 */
class Exception extends \Exception
{
    /**
     * Constructor.
     *
     * @param string $message
     * @param integer $code
     * @param self $previous
     * @param string $file
     * @param int $line
     */
    public function __construct($message = null, $code = 0, \Throwable $previous = null, $file = null, $line = null)
    {
        if ($file !== null) {
            $this->file = $file;
        }
        if ($line !== null) {
            $this->line = $line;
        }
        parent::__construct($message, $code, $previous);
    }
}
