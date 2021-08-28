<?php

namespace Phabel\PhpParser\Lexer\TokenEmulator;

abstract class KeywordEmulator extends TokenEmulator
{
    abstract function getKeywordString();
    abstract function getKeywordToken();
    public function isEmulationNeeded($code)
    {
        if (!\is_string($code)) {
            if (!(\is_string($code) || \is_object($code) && \method_exists($code, '__toString') || (\is_bool($code) || \is_numeric($code)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($code) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($code) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $code = (string) $code;
            }
        }
        $phabelReturn = \strpos(\strtolower($code), $this->getKeywordString()) !== \false;
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
        $keywordString = $this->getKeywordString();
        foreach ($tokens as $i => $token) {
            if ($token[0] === \T_STRING && \strtolower($token[1]) === $keywordString) {
                $previousNonSpaceToken = $this->getPreviousNonSpaceToken($tokens, $i);
                if ($previousNonSpaceToken !== null && $previousNonSpaceToken[0] === \T_OBJECT_OPERATOR) {
                    continue;
                }
                $tokens[$i][0] = $this->getKeywordToken();
            }
        }
        $phabelReturn = $tokens;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param mixed[] $tokens
     * @return mixed[]|null
     */
    private function getPreviousNonSpaceToken(array $tokens, $start)
    {
        if (!\is_int($start)) {
            if (!(\is_bool($start) || \is_numeric($start))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($start) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($start) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $start = (int) $start;
            }
        }
        for ($i = $start - 1; $i >= 0; --$i) {
            if ($tokens[$i][0] === \T_WHITESPACE) {
                continue;
            }
            return $tokens[$i];
        }
        return null;
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
        $keywordToken = $this->getKeywordToken();
        foreach ($tokens as $i => $token) {
            if ($token[0] === $keywordToken) {
                $tokens[$i][0] = \T_STRING;
            }
        }
        $phabelReturn = $tokens;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
