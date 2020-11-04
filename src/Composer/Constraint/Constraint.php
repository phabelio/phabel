<?php

namespace Phabel\Composer\Constraint;

use Composer\Semver\Constraint\Constraint as ComposerConstraint;

/**
 * Constraint.
 *
 * @property ComposerConstraint $previous
 */
class Constraint extends ComposerConstraint
{
    use ConstraintTrait;


    public function getVersion()
    {
        return $this->previous->getVersion();
    }

    public function getOperator()
    {
        return $this->previous->getOperator();
    }

    /**
     * Get all supported comparison operators.
     *
     * @return array
     */
    public static function getSupportedOperators()
    {
        return ComposerConstraint::getSupportedOperators();
    }

    /**
     * @param  string $operator
     * @return int
     *
     * @phpstan-return self::OP_*
     */
    public static function getOperatorConstant($operator)
    {
        return ComposerConstraint::getOperatorConstant($operator);
    }

    /**
     * @param string $a
     * @param string $b
     * @param string $operator
     * @param bool   $compareBranches
     *
     * @throws \InvalidArgumentException if invalid operator is given.
     *
     * @return bool
     */
    public function versionCompare($a, $b, $operator, $compareBranches = false)
    {
        return $this->previous->versionCompare($a, $b, $operator, $compareBranches);
    }


    /**
     * @param ComposerConstraint $provider
     * @param bool       $compareBranches
     *
     * @return bool
     */
    public function matchSpecific(ComposerConstraint $provider, $compareBranches = false)
    {
        return $this->previous->matchSpecific($provider, $compareBranches);
    }
}
