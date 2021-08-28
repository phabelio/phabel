<?php

namespace Phabel\Amp\Process;

const BIN_DIR = __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'bin';
const IS_WINDOWS = (\PHP_OS & "\xdf\xdf\xdf") === 'WIN';
if (IS_WINDOWS) {
    /**
     * Escapes the command argument for safe inclusion into a Windows command string.
     *
     * @param string $arg
     *
     * @return string
     */
    function escapeArguments($arg)
    {
        if (!\is_string($arg)) {
            if (!(\is_string($arg) || \is_object($arg) && \method_exists($arg, '__toString') || (\is_bool($arg) || \is_numeric($arg)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($arg) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($arg) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $arg = (string) $arg;
            }
        }
        $phabelReturn = '"' . \preg_replace_callback('(\\\\*("|$))', function (array $m) {
            $phabelReturn = \str_repeat('\\', \strlen($m[0])) . $m[0];
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }, $arg) . '"';
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
} else {
    /**
     * Escapes the command argument for safe inclusion into a Posix shell command string.
     *
     * @param string $arg
     *
     * @return string
     */
    function escapeArguments($arg)
    {
        if (!\is_string($arg)) {
            if (!(\is_string($arg) || \is_object($arg) && \method_exists($arg, '__toString') || (\is_bool($arg) || \is_numeric($arg)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($arg) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($arg) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $arg = (string) $arg;
            }
        }
        $phabelReturn = \escapeshellarg($arg);
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
