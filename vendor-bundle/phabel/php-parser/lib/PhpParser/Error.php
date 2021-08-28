<?php

namespace Phabel\PhpParser;

class Error extends \RuntimeException
{
    protected $rawMessage;
    protected $attributes;
    /**
     * Creates an Exception signifying a parse error.
     *
     * @param string    $message    Error message
     * @param array|int $attributes Attributes of node/token where error occurred
     *                              (or start line of error -- deprecated)
     */
    public function __construct($message, $attributes = [])
    {
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        $this->rawMessage = $message;
        if (\is_array($attributes)) {
            $this->attributes = $attributes;
        } else {
            $this->attributes = ['startLine' => $attributes];
        }
        $this->updateMessage();
    }
    /**
     * Gets the error message
     *
     * @return string Error message
     */
    public function getRawMessage()
    {
        $phabelReturn = $this->rawMessage;
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
     * Gets the line the error starts in.
     *
     * @return int Error start line
     */
    public function getStartLine()
    {
        $phabelReturn = isset($this->attributes['startLine']) ? $this->attributes['startLine'] : -1;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Gets the line the error ends in.
     *
     * @return int Error end line
     */
    public function getEndLine()
    {
        $phabelReturn = isset($this->attributes['endLine']) ? $this->attributes['endLine'] : -1;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Gets the attributes of the node/token the error occurred at.
     *
     * @return array
     */
    public function getAttributes()
    {
        $phabelReturn = $this->attributes;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Sets the attributes of the node/token the error occurred at.
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        $this->updateMessage();
    }
    /**
     * Sets the line of the PHP file the error occurred in.
     *
     * @param string $message Error message
     */
    public function setRawMessage($message)
    {
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        $this->rawMessage = $message;
        $this->updateMessage();
    }
    /**
     * Sets the line the error starts in.
     *
     * @param int $line Error start line
     */
    public function setStartLine($line)
    {
        if (!\is_int($line)) {
            if (!(\is_bool($line) || \is_numeric($line))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($line) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($line) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $line = (int) $line;
            }
        }
        $this->attributes['startLine'] = $line;
        $this->updateMessage();
    }
    /**
     * Returns whether the error has start and end column information.
     *
     * For column information enable the startFilePos and endFilePos in the lexer options.
     *
     * @return bool
     */
    public function hasColumnInfo()
    {
        $phabelReturn = isset($this->attributes['startFilePos'], $this->attributes['endFilePos']);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Gets the start column (1-based) into the line where the error started.
     *
     * @param string $code Source code of the file
     * @return int
     */
    public function getStartColumn($code)
    {
        if (!\is_string($code)) {
            if (!(\is_string($code) || \is_object($code) && \method_exists($code, '__toString') || (\is_bool($code) || \is_numeric($code)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($code) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($code) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $code = (string) $code;
            }
        }
        if (!$this->hasColumnInfo()) {
            throw new \RuntimeException('Error does not have column information');
        }
        $phabelReturn = $this->toColumn($code, $this->attributes['startFilePos']);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Gets the end column (1-based) into the line where the error ended.
     *
     * @param string $code Source code of the file
     * @return int
     */
    public function getEndColumn($code)
    {
        if (!\is_string($code)) {
            if (!(\is_string($code) || \is_object($code) && \method_exists($code, '__toString') || (\is_bool($code) || \is_numeric($code)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($code) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($code) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $code = (string) $code;
            }
        }
        if (!$this->hasColumnInfo()) {
            throw new \RuntimeException('Error does not have column information');
        }
        $phabelReturn = $this->toColumn($code, $this->attributes['endFilePos']);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Formats message including line and column information.
     *
     * @param string $code Source code associated with the error, for calculation of the columns
     *
     * @return string Formatted message
     */
    public function getMessageWithColumnInfo($code)
    {
        if (!\is_string($code)) {
            if (!(\is_string($code) || \is_object($code) && \method_exists($code, '__toString') || (\is_bool($code) || \is_numeric($code)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($code) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($code) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $code = (string) $code;
            }
        }
        $phabelReturn = \sprintf('%s from %d:%d to %d:%d', $this->getRawMessage(), $this->getStartLine(), $this->getStartColumn($code), $this->getEndLine(), $this->getEndColumn($code));
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
     * Converts a file offset into a column.
     *
     * @param string $code Source code that $pos indexes into
     * @param int    $pos  0-based position in $code
     *
     * @return int 1-based column (relative to start of line)
     */
    private function toColumn($code, $pos)
    {
        if (!\is_string($code)) {
            if (!(\is_string($code) || \is_object($code) && \method_exists($code, '__toString') || (\is_bool($code) || \is_numeric($code)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($code) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($code) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $code = (string) $code;
            }
        }
        if (!\is_int($pos)) {
            if (!(\is_bool($pos) || \is_numeric($pos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($pos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($pos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $pos = (int) $pos;
            }
        }
        if ($pos > \strlen($code)) {
            throw new \RuntimeException('Invalid position information');
        }
        $lineStartPos = \strrpos($code, "\n", $pos - \strlen($code));
        if (\false === $lineStartPos) {
            $lineStartPos = -1;
        }
        $phabelReturn = $pos - $lineStartPos;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Updates the exception message after a change to rawMessage or rawLine.
     */
    protected function updateMessage()
    {
        $this->message = $this->rawMessage;
        if (-1 === $this->getStartLine()) {
            $this->message .= ' on unknown line';
        } else {
            $this->message .= ' on line ' . $this->getStartLine();
        }
    }
}
