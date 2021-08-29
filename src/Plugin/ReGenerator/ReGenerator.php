<?php

namespace Phabel\Plugin\ReGenerator;

/**
 * Regenerator class.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ReGenerator implements \Iterator
{
    /**
     * Variable list for context suspension.
     *
     * @var array
     */
    public $variables = [];
    /**
     * Return value.
     *
     * @var mixed
     */
    public $returnValue;
    /**
     * Yield key.
     *
     * @var mixed
     */
    public $yieldKey;
    /**
     * Yield value.
     *
     * @var mixed
     */
    public $yieldValue;
    /**
     * Value sent from the outside.
     *
     * @var mixed
     */
    public $sentValue;
    /**
     * Exception sent from the outside.
     */
    public $sentException = null;
    /**
     * Current state of state machine.
     */
    public $state = 0;
    /**
     * Whether the generator has returned.
     */
    public $returned = false;
    /**
     * Whether the generator was started.
     */
    public $started = false;
    /**
     * Actual generator function.
     */
    public $generator;
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
    private function start()
    {
        if (!$this->started) {
            ($this->generator)($this->state, $this->variables, $this->yieldKey, $this->yieldValue, $this->sentValue, $this->sentException, $this->returnValue, $this->returned);
            $this->started = true;
        }
    }
    /**
     * Send value into generator.
     *
     * @param mixed $value Value
     *
     * @return mixed
     */
    public function send($value)
    {
        $this->start();
        $value = $this->yieldValue;
        if (!$this->returned) {
            $this->sentValue = $value;
            try {
                ($this->generator)($this->state, $this->variables, $this->yieldKey, $this->yieldValue, $this->sentValue, $this->sentException, $this->returnValue, $this->returned);
            } catch (\Throwable $e) {
                $this->returned = true;
                throw $e;
            } finally {
                $this->sentValue = null;
            }
        }
        return $value;
    }
    /**
     * Throw value into generator.
     *
     * @param \Throwable $throwable Excpeption
     *
     * @return mixed
     */
    public function throw(\Throwable $throwable)
    {
        $this->start();
        $value = $this->yieldValue;
        if (!$this->returned) {
            $this->sentException = $value;
            try {
                ($this->generator)($this->state, $this->variables, $this->yieldKey, $this->yieldValue, $this->sentValue, $this->sentException, $this->returnValue, $this->returned);
            } catch (\Throwable $e) {
                $this->returned = true;
                throw $e;
            } finally {
                $this->sentException = null;
            }
        }
        return $value;
    }
    /**
     * Get current value.
     *
     * @return mixed
     */
    public function current()
    {
        $this->start();
        return $this->yieldValue;
    }
    /**
     * Get current key.
     *
     * @return mixed
     */
    public function key()
    {
        $this->start();
        return $this->yieldKey;
    }
    /**
     * Advance generator.
     *
     * @return void
     */
    public function next()
    {
        $this->send(null);
    }
    /**
     * Rewind generator.
     *
     * @return void
     */
    public function rewind()
    {
        if ($this->started && !$this->returned) {
            throw new \Exception('Cannot rewind a generator that was already run');
        }
        $this->state = 0;
        $this->started = false;
        $this->returned = false;
        $this->returnValue = null;
        $this->yieldKey = null;
        $this->yieldValue = null;
        $this->sentValue = null;
        $this->sentException = null;
        $this->variables = [];
        $this->start();
    }
    /**
     * Check if generator is valid.
     *
     * @return boolean
     */
    public function valid(): bool
    {
        return !$this->returned;
    }
}
