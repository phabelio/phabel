<?php

namespace Phabel\PhpParser\Lexer\TokenEmulator;

use Phabel\PhpParser\Lexer\Emulative;
final class FlexibleDocStringEmulator extends TokenEmulator
{
    const FLEXIBLE_DOC_STRING_REGEX = <<<'REGEX'
/<<<[ \t]*(['"]?)([a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)\1\r?\n
(?:.*\r?\n)*?
(?<indentation>\h*)\2(?![a-zA-Z0-9_\x80-\xff])(?<separator>(?:;?[\r\n])?)/x
REGEX;
    public function getPhpVersion()
    {
        $phabelReturn = Emulative::PHP_7_3;
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
        $phabelReturn = \strpos($code, '<<<') !== \false;
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
        $phabelReturn = $tokens;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        // Handled by preprocessing + fixup.
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
        // Not supported.
        return $phabelReturn;
    }
    public function preprocessCode($code, array &$patches)
    {
        if (!\is_string($code)) {
            if (!(\is_string($code) || \is_object($code) && \method_exists($code, '__toString') || (\is_bool($code) || \is_numeric($code)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($code) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($code) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $code = (string) $code;
            }
        }
        if (!\preg_match_all(self::FLEXIBLE_DOC_STRING_REGEX, $code, $matches, \PREG_SET_ORDER | \PREG_OFFSET_CAPTURE)) {
            $phabelReturn = $code;
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            // No heredoc/nowdoc found
            return $phabelReturn;
        }
        // Keep track of how much we need to adjust string offsets due to the modifications we
        // already made
        $posDelta = 0;
        foreach ($matches as $match) {
            $indentation = $match['indentation'][0];
            $indentationStart = $match['indentation'][1];
            $separator = $match['separator'][0];
            $separatorStart = $match['separator'][1];
            if ($indentation === '' && $separator !== '') {
                // Ordinary heredoc/nowdoc
                continue;
            }
            if ($indentation !== '') {
                // Remove indentation
                $indentationLen = \strlen($indentation);
                $code = \substr_replace($code, '', $indentationStart + $posDelta, $indentationLen);
                $patches[] = [$indentationStart + $posDelta, 'add', $indentation];
                $posDelta -= $indentationLen;
            }
            if ($separator === '') {
                // Insert newline as separator
                $code = \substr_replace($code, "\n", $separatorStart + $posDelta, 0);
                $patches[] = [$separatorStart + $posDelta, 'remove', "\n"];
                $posDelta += 1;
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
