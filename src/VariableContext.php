<?php

namespace Phabel;

/**
 * Represent variables currently in scope.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class VariableContext
{
    /**
     * Variable list.
     *
     * @var array<string, true>
     */
    private $variables;
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
    public function addVar(string $var)
    {
        $this->variables[$var] = \true;
    }
    /**
     * Add variables.
     *
     * @param array<string, true> $vars Variables
     *
     * @return void
     */
    public function addVars(array $vars)
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
    public function removeVar(string $var)
    {
        unset($this->variables[$var]);
    }
    /**
     * Check if variable is present.
     *
     * @param string $var
     * @return boolean
     */
    public function hasVar(string $var) : bool
    {
        return isset($this->variables[$var]);
    }
    /**
     * Get unused variable name.
     *
     * @return string
     */
    public function getVar() : string
    {
        do {
            $var = 'phabel_' . \bin2hex(\random_bytes(8));
        } while (isset($this->variables[$var]));
        $this->variables[$var] = \true;
        return $var;
    }
    /**
     * Get all variables currently defined.
     *
     * @return array
     */
    public function getVars() : array
    {
        return $this->variables;
    }
}
