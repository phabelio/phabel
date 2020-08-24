<?php

namespace Phabel\Plugin\ReGenerator;

/**
 * Regenerator class.
 */
class ReGenerator implements \Iterator
{
    /**
     * Variable list for context suspension.
     *
     * @var array
     */
    private $variables = [];
    /**
     * Return value.
     *
     * @var mixed
     */
    private $returnValue;
    /**
     * Yield key.
     *
     * @var mixed
     */
    private $yieldKey;
    /**
     * Yield value.
     *
     * @var mixed
     */
    private $yieldValue;

    /**
     * Value sent from the outside.
     *
     * @var mixed
     */
    private $sentValue;
    /**
     * Exception sent from the outside.
     */
    private ?\Throwable $sentException = null;

    /**
     * Current state of state machine.
     */
    private int $state = 0;

    /**
     * Whether the generator has returned.
     */
    private bool $returned = false;
    /**
     * Whether the generator was started.
     */
    private bool $started = false;

    /**
     * Actual generator function.
     */
    private \Closure $generator;

    /**
     * Yielded from (re)generator.
     *
     * @var \Generator|self|null
     */
    private $yieldedFrom;

    /**
     * Construct regenerator.
     *
     * @param \Closure $function
     */
    public function __construct(\Closure $function)
    {
        $this->generator = $function;
    }

    /**
     * Get return value.
     *
     * @return mixed
     */
    public function getReturn()
    {
        return $this->returnValue;
    }

    /**
     * Start generator.
     *
     * @return void
     */
    private function start(): void
    {
        if (!$this->started) {
            ($this->generator)($this->state, $this->variables, $this->yieldKey, $this->yieldValue, $this->sentValue, $this->sentException, $this->returnValue, $this->returned, $this->yieldedFrom);
            $this->started = true;
        }
    }

    public function send($value)
    {
        $this->start();
        if ($this->yieldedFrom) {
            try {
                $result = $this->yieldedFrom->send($value);
            } catch (\Throwable $exception) {
                $e = $exception;
            }
            if (!$this->yieldedFrom->valid()) { // Returned from yield from
                $returnValue = \method_exists($this->yieldedFrom, 'getReturn') ? $this->yieldedFrom->getReturn() : null;
                $this->yieldedFrom = null;
                if (isset($e)) {
                    return $this->throw($e);
                }
                return $this->send($returnValue);
            }
            return $result;
        }
        $value = $this->yieldValue;
        if (!$this->returned) {
            $this->sentValue = $value;
            try {
                ($this->generator)($this->state, $this->variables, $this->yieldKey, $this->yieldValue, $this->sentValue, $this->sentException, $this->returnValue, $this->returned, $this->yieldedFrom);
            } catch (\Throwable $e) {
                $this->returned = true;
                throw $e;
            } finally {
                $this->sentValue = null;
            }
        }
        return $value;
    }
    public function throw(\Throwable $throwable)
    {
        $this->start();
        if ($this->yieldedFrom) {
            try {
                $result = $this->yieldedFrom->throw($throwable);
            } catch (\Throwable $exception) {
                $e = $exception;
            }
            if (!$this->yieldedFrom->valid()) { // Returned from yield from
                $returnValue = \method_exists($this->yieldedFrom, 'getReturn') ? $this->yieldedFrom->getReturn() : null;
                $this->yieldedFrom = null;
                if (isset($e)) {
                    return $this->throw($e);
                }
                return $this->send($returnValue);
            }
            return $result;
        }
        $value = $this->yieldValue;
        if (!$this->returned) {
            $this->sentException = $value;
            try {
                ($this->generator)($this->variables, $this->yieldKey, $this->yieldValue, $this->sentValue, $this->sentException, $this->returnValue, $this->returned, $this->yieldedFrom);
            } catch (\Throwable $e) {
                $this->returned = true;
                throw $e;
            } finally {
                $this->sentException = null;
            }
        }
        return $value;
    }

    public function current()
    {
        if ($this->yieldedFrom) {
            return $this->yieldedFrom->current();
        }
        $this->start();
        return $this->yieldValue;
    }
    public function key()
    {
        if ($this->yieldedFrom) {
            return $this->yieldedFrom->key();
        }
        $this->start();
        return $this->yieldKey;
    }
    public function next(): void
    {
        $this->send(null);
    }
    public function rewind(): void
    {
        if ($this->started && !$this->returned) {
            throw new \Exception('Cannot rewind a generator that was already run');
        }
        $this->started = false;
        $this->returned = false;
        $this->returnValue = null;
        $this->yieldKey = null;
        $this->yieldValue = null;
        $this->sentValue = null;
        $this->sentException = null;
        $this->yieldedFrom = null;
        $this->variables = [];
        $this->start();
    }
    public function valid(): bool
    {
        return !$this->returned;
    }
}
