<?php

namespace Phabel;

/**
 * Represent variables currently in scope.
 */
class VariableContext
{
    /**
     * Variable list.
     *
     * @var array<string, true>
     */
    private array $variables;
    /**
     * Custom variable counter.
     */
    private int $counter = 0;
    /**
     * Constructor.
     *
     * @param array<string, true> $variables Initial variables
     */
    public function __construct(array $variables = [])
    {
        $this->variables = $variables;
    }
    /**
     * Add variable.
     *
     * @param string $var Variable
     *
     * @return void
     */
    public function addVar(string $var): void
    {
        $this->variables[$var] = true;
    }
    /**
     * Add variables.
     *
     * @param array<string, true> $vars Variables
     *
     * @return void
     */
    public function addVars(array $vars): void
    {
        $this->variables += $vars;
    }
    /**
     * Remove variable.
     *
     * @param string $var Variable name
     *
     * @return void
     */
    public function removeVar(string $var): void
    {
        unset($this->variables[$var]);
    }
    /**
     * Check if variable is present.
     *
     * @param string $var
     * @return boolean
     */
    public function hasVar(string $var): bool
    {
        return isset($this->variables[$var]);
    }
    /**
     * Get unused variable name.
     *
     * @return string
     */
    public function getVar(): string
    {
        do {
            $var = 'phabel'.$this->counter;
            $this->counter++;
        } while (isset($this->variables[$var]));
        $this->variables[$var] = true;
        return $var;
    }
    /**
     * Get all variables currently defined
     *
     * @return array
     */
    public function getVars(): array
    {
        return $this->variables;
    }
}
