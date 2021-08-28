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
    public function addVar($var)
    {
        if (!\is_string($var)) {
            if (!(\is_string($var) || \is_object($var) && \method_exists($var, '__toString') || (\is_bool($var) || \is_numeric($var)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($var) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($var) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $var = (string) $var;
        }
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
    public function removeVar($var)
    {
        if (!\is_string($var)) {
            if (!(\is_string($var) || \is_object($var) && \method_exists($var, '__toString') || (\is_bool($var) || \is_numeric($var)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($var) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($var) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $var = (string) $var;
        }
        unset($this->variables[$var]);
    }
    /**
     * Check if variable is present.
     *
     * @param string $var
     * @return boolean
     */
    public function hasVar($var)
    {
        if (!\is_string($var)) {
            if (!(\is_string($var) || \is_object($var) && \method_exists($var, '__toString') || (\is_bool($var) || \is_numeric($var)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($var) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($var) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $var = (string) $var;
        }
        $phabelReturn = isset($this->variables[$var]);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Get unused variable name.
     *
     * @return string
     */
    public function getVar()
    {
        do {
            $var = 'phabel_' . \bin2hex(\random_bytes(8));
        } while (isset($this->variables[$var]));
        $this->variables[$var] = \true;
        $phabelReturn = $var;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Get all variables currently defined.
     *
     * @return array
     */
    public function getVars()
    {
        $phabelReturn = $this->variables;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
