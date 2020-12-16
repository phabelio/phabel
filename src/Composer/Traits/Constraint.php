<?php

namespace Phabel\Composer\Traits;

trait Constraint
{
    public function compile($otherOperator)
    {
        if (\strpos($this->version, 'dev-') === 0) {
            if (self::OP_EQ === $this->operator) {
                if (self::OP_EQ === $otherOperator) {
                    return \sprintf('$b && $v === %s', \var_export($this->version, true));
                }
                if (self::OP_NE === $otherOperator) {
                    return \sprintf('!$b || $v !== %s', \var_export($this->version, true));
                }
                return 'false';
            }

            if (self::OP_NE === $this->operator) {
                if (self::OP_EQ === $otherOperator) {
                    return \sprintf('!$b || $v !== %s', \var_export($this->version, true));
                }
                if (self::OP_NE === $otherOperator) {
                    return 'true';
                }
                return '!$b';
            }

            return 'false';
        }

        if (self::OP_EQ === $this->operator) {
            if (self::OP_EQ === $otherOperator) {
                return \sprintf('\version_compare($v, %s, \'==\')', \var_export($this->version, true));
            }
            if (self::OP_NE === $otherOperator) {
                return \sprintf('$b || \version_compare($v, %s, \'!=\')', \var_export($this->version, true));
            }

            return \sprintf('!$b && \version_compare(%s, $v, \'%s\')', \var_export($this->version, true), self::$transOpInt[$otherOperator]);
        }

        if (self::OP_NE === $this->operator) {
            if (self::OP_EQ === $otherOperator) {
                return \sprintf('$b || (!$b && \version_compare($v, %s, \'!=\'))', \var_export($this->version, true));
            }

            if (self::OP_NE === $otherOperator) {
                return 'true';
            }
            return '!$b';
        }

        if (self::OP_LT === $this->operator || self::OP_LE === $this->operator) {
            if (self::OP_LT === $otherOperator || self::OP_LE === $otherOperator) {
                return '!$b';
            }
        } elseif (self::OP_GT === $this->operator || self::OP_GE === $this->operator) {
            if (self::OP_GT === $otherOperator || self::OP_GE === $otherOperator) {
                return '!$b';
            }
        }

        if (self::OP_NE === $otherOperator) {
            return 'true';
        }

        $codeComparison = \sprintf('\version_compare($v, %s, \'%s\')', \var_export($this->version, true), self::$transOpInt[$this->operator]);
        if ($this->operator === self::OP_LE) {
            if ($otherOperator === self::OP_GT) {
                return \sprintf('!$b && \version_compare($v, %s, \'!=\') && ', \var_export($this->version, true)) . $codeComparison;
            }
        } elseif ($this->operator === self::OP_GE) {
            if ($otherOperator === self::OP_LT) {
                return \sprintf('!$b && \version_compare($v, %s, \'!=\') && ', \var_export($this->version, true)) . $codeComparison;
            }
        }

        return \sprintf('!$b && %s', $codeComparison);
    }
}
