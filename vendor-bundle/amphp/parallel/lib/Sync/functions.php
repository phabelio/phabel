<?php

namespace Phabel\Amp\Parallel\Sync;

use Phabel\Amp\Serialization\SerializationException as SerializerException;
// Alias must be defined in an always-loaded file as catch blocks do not trigger the autoloader.
\class_alias(SerializerException::class, SerializationException::class);
/**
 * @param \Throwable $exception
 *
 * @return array Serializable exception backtrace, with all function arguments flattened to strings.
 */
function flattenThrowableBacktrace(\Throwable $exception)
{
    $trace = $exception->getTrace();
    foreach ($trace as &$call) {
        unset($call['object']);
        $call['args'] = \array_map(__NAMESPACE__ . '\\flattenArgument', isset($call['args']) ? $call['args'] : []);
    }
    $phabelReturn = $trace;
    if (!\is_array($phabelReturn)) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * @param array $trace Backtrace produced by {@see formatFlattenedBacktrace()}.
 *
 * @return string
 */
function formatFlattenedBacktrace(array $trace)
{
    $output = [];
    foreach ($trace as $index => $call) {
        if (isset($call['class'])) {
            $name = $call['class'] . $call['type'] . $call['function'];
        } else {
            $name = $call['function'];
        }
        $output[] = \sprintf('#%d %s(%d): %s(%s)', $index, isset($call['file']) ? $call['file'] : '[internal function]', isset($call['line']) ? $call['line'] : 0, $name, \implode(', ', isset($call['args']) ? $call['args'] : ['...']));
    }
    $phabelReturn = \implode("\n", $output);
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
 * @param mixed $value
 *
 * @return string Serializable string representation of $value for backtraces.
 */
function flattenArgument($value)
{
    if ($value instanceof \Closure) {
        $closureReflection = new \ReflectionFunction($value);
        $phabelReturn = \sprintf('Closure(%s:%s)', $closureReflection->getFileName(), $closureReflection->getStartLine());
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    if (\is_object($value)) {
        $phabelReturn = \sprintf('Object(%s)', \get_class($value));
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    if (\is_array($value)) {
        $phabelReturn = 'Array([' . \implode(', ', \array_map(__FUNCTION__, $value)) . '])';
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    if (\is_resource($value)) {
        $phabelReturn = \sprintf('Resource(%s)', \get_resource_type($value));
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    if (\is_string($value)) {
        $phabelReturn = '"' . $value . '"';
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    if (\is_null($value)) {
        $phabelReturn = 'null';
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    if (\is_bool($value)) {
        $phabelReturn = $value ? 'true' : 'false';
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    $phabelReturn = (string) $value;
    if (!\is_string($phabelReturn)) {
        if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        } else {
            $phabelReturn = (string) $phabelReturn;
        }
    }
    return $phabelReturn;
}
