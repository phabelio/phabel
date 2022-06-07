<?php

namespace Phabel\ClassStorage;

/**
 * Stores information about a function.
 */
class FunctionStorage
{
    /**
     * Constructor.
     *
     * @param array<string, mixed> $arguments
     * @param boolean $hasVariadic
     */
    public function __construct(array $arguments, bool $hasVariadic)
    {
        $this->hasVariadic = $hasVariadic;
        $this->arguments = $arguments;
    }
    /**
     * @var bool $hasVariadic
     */
    private $hasVariadic;
    /**
     * @var array $arguments
     */
    private $arguments;
    /**
     * Get arguments.
     *
     * @return array<string, mixed>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
    /**
     * Check if function is variadic.
     *
     * @return boolean
     */
    public function isVariadic(): bool
    {
        return $this->hasVariadic;
    }
}
