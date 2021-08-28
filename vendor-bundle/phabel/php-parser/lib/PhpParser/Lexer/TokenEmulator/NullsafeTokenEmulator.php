<?php

namespace Phabel\PhpParser\Lexer\TokenEmulator;

use Phabel\PhpParser\Lexer\Emulative;
final class NullsafeTokenEmulator extends TokenEmulator
{
    public function getPhpVersion()
    {
        $phabelReturn = Emulative::PHP_8_0;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function isEmulationNeeded($code)
    {
        if (!\is_string($code)) {
            if (!(\is_string($code) || \is_object($code) && \method_exists($code, '__toString') || (\is_bool($code) || \is_numeric($code)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($code) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($code) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $code = (string) $code;
            }
        }
        $phabelReturn = \strpos($code, '?->') !== \false;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function emulate($code, array $tokens)
    {
        if (!\is_string($code)) {
            if (!(\is_string($code) || \is_object($code) && \method_exists($code, '__toString') || (\is_bool($code) || \is_numeric($code)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($code) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($code) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $code = (string) $code;
            }
        }
        // We need to manually iterate and manage a count because we'll change
        // the tokens array on the way
        $line = 1;
        for ($i = 0, $c = \count($tokens); $i < $c; ++$i) {
            if ($tokens[$i] === '?' && isset($tokens[$i + 1]) && $tokens[$i + 1][0] === \T_OBJECT_OPERATOR) {
                \array_splice($tokens, $i, 2, [[\T_NULLSAFE_OBJECT_OPERATOR, '?->', $line]]);
                $c--;
                continue;
            }
            // Handle ?-> inside encapsed string.
            if ($tokens[$i][0] === \T_ENCAPSED_AND_WHITESPACE && isset($tokens[$i - 1]) && $tokens[$i - 1][0] === \T_VARIABLE && \preg_match('/^\\?->([a-zA-Z_\\x80-\\xff][a-zA-Z0-9_\\x80-\\xff]*)/', $tokens[$i][1], $matches)) {
                $replacement = [[\T_NULLSAFE_OBJECT_OPERATOR, '?->', $line], [\T_STRING, $matches[1], $line]];
                if (\strlen($matches[0]) !== \strlen($tokens[$i][1])) {
                    $replacement[] = [\T_ENCAPSED_AND_WHITESPACE, \substr($tokens[$i][1], \strlen($matches[0])), $line];
                }
                \array_splice($tokens, $i, 1, $replacement);
                $c += \count($replacement) - 1;
                continue;
            }
            if (\is_array($tokens[$i])) {
                $line += \substr_count($tokens[$i][1], "\n");
            }
        }
        $phabelReturn = $tokens;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function reverseEmulate($code, array $tokens)
    {
        if (!\is_string($code)) {
            if (!(\is_string($code) || \is_object($code) && \method_exists($code, '__toString') || (\is_bool($code) || \is_numeric($code)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($code) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($code) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $code = (string) $code;
            }
        }
        $phabelReturn = $tokens;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        // ?-> was not valid code previously, don't bother.
        return $phabelReturn;
    }
}
