<?php

namespace Phabel\PhpParser\Lexer\TokenEmulator;

/** @internal */
abstract class TokenEmulator
{
    public abstract function getPhpVersion();
    public abstract function isEmulationNeeded($code);
    /**
     * @return array Modified Tokens
     */
    public abstract function emulate($code, array $tokens);
    /**
     * @return array Modified Tokens
     */
    public abstract function reverseEmulate($code, array $tokens);
    public function preprocessCode($code, array &$patches)
    {
        if (!\is_string($code)) {
            if (!(\is_string($code) || \is_object($code) && \method_exists($code, '__toString') || (\is_bool($code) || \is_numeric($code)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($code) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($code) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $code = (string) $code;
            }
        }
        $phabelReturn = $code;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
