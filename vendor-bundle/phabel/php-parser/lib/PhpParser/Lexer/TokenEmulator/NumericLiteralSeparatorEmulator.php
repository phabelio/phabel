<?php

namespace Phabel\PhpParser\Lexer\TokenEmulator;

use Phabel\PhpParser\Lexer\Emulative;
final class NumericLiteralSeparatorEmulator extends TokenEmulator
{
    const BIN = '(?:0b[01]+(?:_[01]+)*)';
    const HEX = '(?:0x[0-9a-f]+(?:_[0-9a-f]+)*)';
    const DEC = '(?:[0-9]+(?:_[0-9]+)*)';
    const SIMPLE_FLOAT = '(?:' . self::DEC . '\\.' . self::DEC . '?|\\.' . self::DEC . ')';
    const EXP = '(?:e[+-]?' . self::DEC . ')';
    const FLOAT = '(?:' . self::SIMPLE_FLOAT . self::EXP . '?|' . self::DEC . self::EXP . ')';
    const NUMBER = '~' . self::FLOAT . '|' . self::BIN . '|' . self::HEX . '|' . self::DEC . '~iA';
    public function getPhpVersion()
    {
        $phabelReturn = Emulative::PHP_7_4;
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
        $phabelReturn = \preg_match('~[0-9]_[0-9]~', $code) || \preg_match('~0x[0-9a-f]+_[0-9a-f]~i', $code);
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
        $codeOffset = 0;
        for ($i = 0, $c = \count($tokens); $i < $c; ++$i) {
            $token = $tokens[$i];
            $tokenLen = \strlen(\is_array($token) ? $token[1] : $token);
            if ($token[0] !== \T_LNUMBER && $token[0] !== \T_DNUMBER) {
                $codeOffset += $tokenLen;
                continue;
            }
            $res = \preg_match(self::NUMBER, $code, $matches, 0, $codeOffset);
            \assert($res, "No number at number token position");
            $match = $matches[0];
            $matchLen = \strlen($match);
            if ($matchLen === $tokenLen) {
                // Original token already holds the full number.
                $codeOffset += $tokenLen;
                continue;
            }
            $tokenKind = $this->resolveIntegerOrFloatToken($match);
            $newTokens = [[$tokenKind, $match, $token[2]]];
            $numTokens = 1;
            $len = $tokenLen;
            while ($matchLen > $len) {
                $nextToken = $tokens[$i + $numTokens];
                $nextTokenText = \is_array($nextToken) ? $nextToken[1] : $nextToken;
                $nextTokenLen = \strlen($nextTokenText);
                $numTokens++;
                if ($matchLen < $len + $nextTokenLen) {
                    // Split trailing characters into a partial token.
                    \assert(\is_array($nextToken), "Partial token should be an array token");
                    $partialText = \substr($nextTokenText, $matchLen - $len);
                    $newTokens[] = [$nextToken[0], $partialText, $nextToken[2]];
                    break;
                }
                $len += $nextTokenLen;
            }
            \array_splice($tokens, $i, $numTokens, $newTokens);
            $c -= $numTokens - \count($newTokens);
            $codeOffset += $matchLen;
        }
        $phabelReturn = $tokens;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function resolveIntegerOrFloatToken($str)
    {
        if (!\is_string($str)) {
            if (!(\is_string($str) || \is_object($str) && \method_exists($str, '__toString') || (\is_bool($str) || \is_numeric($str)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($str) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($str) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $str = (string) $str;
            }
        }
        $str = \str_replace('_', '', $str);
        if (\stripos($str, '0b') === 0) {
            $num = \bindec($str);
        } elseif (\stripos($str, '0x') === 0) {
            $num = \hexdec($str);
        } elseif (\stripos($str, '0') === 0 && \ctype_digit($str)) {
            $num = \octdec($str);
        } else {
            $num = +$str;
        }
        $phabelReturn = \is_float($num) ? \T_DNUMBER : \T_LNUMBER;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
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
        // Numeric separators were not legal code previously, don't bother.
        return $phabelReturn;
    }
}
