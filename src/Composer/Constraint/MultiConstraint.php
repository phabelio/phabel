<?php

namespace Phabel\Composer\Constraint;

use Composer\Semver\Constraint\MultiConstraint as ComposerMultiConstraint;

/**
 * Constraint.
 *
 * @property ComposerMultiConstraint $previous
 */
class MultiConstraint extends ComposerMultiConstraint
{
    use ConstraintTrait;


    /**
     * @return ConstraintInterface[]
     */
    public function getConstraints()
    {
        return $this->previous->getConstraints();
    }

    /**
     * @return bool
     */
    public function isConjunctive()
    {
        return $this->previous->isConjunctive();
    }

    /**
     * @return bool
     */
    public function isDisjunctive()
    {
        return !$this->previous->isDisjunctive();
    }


    /**
     * Tries to optimize the constraints as much as possible, meaning
     * reducing/collapsing congruent constraints etc.
     * Does not necessarily return a MultiConstraint instance if
     * things can be reduced to a simple constraint.
     *
     * @param ConstraintInterface[] $constraints A set of constraints
     * @param bool                  $conjunctive Whether the constraints should be treated as conjunctive or disjunctive
     *
     * @return ConstraintInterface
     */
    public static function create(array $constraints, $conjunctive = true)
    {
        return ComposerMultiConstraint::create($conjunctive, $conjunctive);
    }
}
