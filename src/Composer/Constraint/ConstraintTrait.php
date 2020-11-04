<?php

namespace Phabel\Composer\Constraint;

use Composer\Semver\Constraint\ConstraintInterface;

trait ConstraintTrait
{
    /**
     * Constraint.
     */
    private ConstraintInterface $previous;
    /**
     * Config.
     */
    private array $config;
    /**
     * Constructs new constraint with phabel config.
     *
     * @param ConstraintInterface $previous
     * @param array $config
     */
    public function __construct(ConstraintInterface $previous, array $config)
    {
        $this->previous = $previous;
        $this->config = $config;
    }

    /**
     * Checks whether the given constraint intersects in any way with this constraint.
     *
     * @param ConstraintInterface $provider
     *
     * @return bool
     */
    public function matches(ConstraintInterface $provider)
    {
        return $this->previous->matches($provider);
    }

    /**
     * Provides a compiled version of the constraint for the given operator
     * The compiled version must be a PHP expression.
     * Executor of compile version must provide 2 variables:
     * - $v = the string version to compare with
     * - $b = whether or not the version is a non-comparable branch (starts with "dev-").
     *
     * @see Constraint::OP_* for the list of available operators.
     * @example return '!$b && version_compare($v, '1.0', '>')';
     *
     * @param int $operator one Constraint::OP_*
     *
     * @return string
     */
    public function compile($operator)
    {
        return $this->previous->compile($operator);
    }

    /**
     * @return Bound
     */
    public function getUpperBound()
    {
        return $this->previous->getUpperBound();
    }

    /**
     * @return Bound
     */
    public function getLowerBound()
    {
        return $this->previous->getLowerBound();
    }

    /**
     * @return string
     */
    public function getPrettyString()
    {
        return $this->previous->getPrettyString();
    }

    /**
     * @param string|null $prettyString
     */
    public function setPrettyString($prettyString)
    {
        return $this->previous->setPrettyString($prettyString);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->previous->__toString();
    }

    /**
     * Get config.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Get constraint.
     *
     * @return ConstraintInterface
     */
    public function getPrevious(): ConstraintInterface
    {
        return $this->previous;
    }
}
