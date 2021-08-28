<?php

namespace Phabel\Amp\Internal;

/**
 * Formats a stacktrace obtained via `debug_backtrace()`.
 *
 * @param array<array{file?: string, line: int, type?: string, class: string, function: string}> $trace Output of
 *     `debug_backtrace()`.
 *
 * @return string Formatted stacktrace.
 *
 * @codeCoverageIgnore
 * @internal
 */
function formatStacktrace(array $trace)
{
    $phabelReturn = \implode("\n", \array_map(static function ($e, $i) {
        $line = "#{$i} ";
        if (isset($e["file"])) {
            $line .= "{$e['file']}:{$e['line']} ";
        }
        if (isset($e["type"])) {
            $line .= $e["class"] . $e["type"];
        }
        return $line . $e["function"] . "()";
    }, $trace, \array_keys($trace)));
    if (!\is_string($phabelReturn)) {
        if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        } else {
            $phabelReturn = (string) $phabelReturn;
        }
    }
    return $phabelReturn;
}
/**
 * Creates a `TypeError` with a standardized error message.
 *
 * @param string[] $expected Expected types.
 * @param mixed    $given Given value.
 *
 * @return \TypeError
 *
 * @internal
 */
function createTypeError(array $expected, $given)
{
    $givenType = \is_object($given) ? \sprintf("instance of %s", \get_class($given)) : \gettype($given);
    if (\count($expected) === 1) {
        $expectedType = "Expected the following type: " . \array_pop($expected);
    } else {
        $expectedType = "Expected one of the following types: " . \implode(", ", $expected);
    }
    $phabelReturn = new \TypeError("{$expectedType}; {$givenType} given");
    if (!$phabelReturn instanceof \TypeError) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type TypeError, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Returns the current time relative to an arbitrary point in time.
 *
 * @return int Time in milliseconds.
 */
function getCurrentTime()
{
    /** @var int|null $startTime */
    static $startTime;
    /** @var int|null $nextWarning */
    static $nextWarning;
    if (\PHP_INT_SIZE === 4) {
        // @codeCoverageIgnoreStart
        if ($startTime === null) {
            $startTime = \PHP_VERSION_ID >= 70300 ? \hrtime(\false)[0] : \time();
            $nextWarning = \PHP_INT_MAX - 86400 * 7;
        }
        if (\PHP_VERSION_ID >= 70300) {
            list($seconds, $nanoseconds) = \hrtime(\false);
            $seconds -= $startTime;
            if ($seconds >= $nextWarning) {
                $timeToOverflow = (\PHP_INT_MAX - $seconds * 1000) / 1000;
                \trigger_error("getCurrentTime() will overflow in {$timeToOverflow} seconds, please restart the process before that. " . "You're using a 32 bit version of PHP, so time will overflow about every 24 days. Regular restarts are required.", \E_USER_WARNING);
                /** @psalm-suppress PossiblyNullOperand */
                $nextWarning += 600;
                // every 10 minutes
            }
            $phabelReturn = (int) ($seconds * 1000 + $nanoseconds / 1000000);
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $seconds = \microtime(\true) - $startTime;
        if ($seconds >= $nextWarning) {
            $timeToOverflow = (\PHP_INT_MAX - $seconds * 1000) / 1000;
            \trigger_error("getCurrentTime() will overflow in {$timeToOverflow} seconds, please restart the process before that. " . "You're using a 32 bit version of PHP, so time will overflow about every 24 days. Regular restarts are required.", \E_USER_WARNING);
            /** @psalm-suppress PossiblyNullOperand */
            $nextWarning += 600;
            // every 10 minutes
        }
        $phabelReturn = (int) ($seconds * 1000);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
        // @codeCoverageIgnoreEnd
    }
    if (\PHP_VERSION_ID >= 70300) {
        list($seconds, $nanoseconds) = \hrtime(\false);
        $phabelReturn = (int) ($seconds * 1000 + $nanoseconds / 1000000);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    $phabelReturn = (int) (\microtime(\true) * 1000);
    if (!\is_int($phabelReturn)) {
        if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        } else {
            $phabelReturn = (int) $phabelReturn;
        }
    }
    return $phabelReturn;
}
