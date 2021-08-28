<?php

namespace Phabel\Amp\Parser;

class Parser
{
    /** @var \Generator */
    private $generator;
    /** @var string */
    private $buffer = '';
    /** @var int|string|null */
    private $delimiter;
    /**
     * @param \Generator $generator
     *
     * @throws InvalidDelimiterError If the generator yields an invalid delimiter.
     * @throws \Throwable If the generator throws.
     */
    public function __construct(\Generator $generator)
    {
        $this->generator = $generator;
        $this->delimiter = $this->generator->current();
        if (!$this->generator->valid()) {
            $this->generator = null;
            return;
        }
        if ($this->delimiter !== null && (!\is_int($this->delimiter) || $this->delimiter <= 0) && (!\is_string($this->delimiter) || !\strlen($this->delimiter))) {
            throw new InvalidDelimiterError($generator, \sprintf("Invalid value yielded: Expected NULL, an int greater than 0, or a non-empty string; %s given", \is_object($this->delimiter) ? \sprintf("instance of %s", \get_class($this->delimiter)) : \gettype($this->delimiter)));
        }
    }
    /**
     * Cancels the generator parser and returns any remaining data in the internal buffer. Writing data after calling
     * this method will result in an error.
     *
     * @return string
     */
    public final function cancel()
    {
        $this->generator = null;
        $phabelReturn = $this->buffer;
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
     * @return bool True if the parser can still receive more data to parse, false if it has ended and calling push
     *     will throw an exception.
     */
    public final function isValid()
    {
        $phabelReturn = $this->generator !== null;
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
     * Adds data to the internal buffer and tries to continue parsing.
     *
     * @param string $data Data to append to the internal buffer.
     *
     * @throws InvalidDelimiterError If the generator yields an invalid delimiter.
     * @throws \Error If parsing has already been cancelled.
     * @throws \Throwable If the generator throws.
     */
    public final function push($data)
    {
        if (!\is_string($data)) {
            if (!(\is_string($data) || \is_object($data) && \method_exists($data, '__toString') || (\is_bool($data) || \is_numeric($data)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($data) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($data) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $data = (string) $data;
            }
        }
        if ($this->generator === null) {
            throw new \Error("The parser is no longer writable");
        }
        $this->buffer .= $data;
        $end = \false;
        try {
            while ($this->buffer !== "") {
                if (\is_int($this->delimiter)) {
                    if (\strlen($this->buffer) < $this->delimiter) {
                        break;
                        // Too few bytes in buffer.
                    }
                    $send = \substr($this->buffer, 0, $this->delimiter);
                    $this->buffer = \substr($this->buffer, $this->delimiter);
                } elseif (\is_string($this->delimiter)) {
                    if (($position = \strpos($this->buffer, $this->delimiter)) === \false) {
                        break;
                    }
                    $send = \substr($this->buffer, 0, $position);
                    $this->buffer = \substr($this->buffer, $position + \strlen($this->delimiter));
                } else {
                    $send = $this->buffer;
                    $this->buffer = "";
                }
                $this->delimiter = $this->generator->send($send);
                if (!$this->generator->valid()) {
                    $end = \true;
                    break;
                }
                if ($this->delimiter !== null && (!\is_int($this->delimiter) || $this->delimiter <= 0) && (!\is_string($this->delimiter) || !\strlen($this->delimiter))) {
                    throw new InvalidDelimiterError($this->generator, \sprintf("Invalid value yielded: Expected NULL, an int greater than 0, or a non-empty string; %s given", \is_object($this->delimiter) ? \sprintf("instance of %s", \get_class($this->delimiter)) : \gettype($this->delimiter)));
                }
            }
        } catch (\Exception $exception) {
            $end = \true;
            throw $exception;
        } catch (\Error $exception) {
            $end = \true;
            throw $exception;
        } finally {
            if ($end) {
                $this->generator = null;
            }
        }
    }
}
