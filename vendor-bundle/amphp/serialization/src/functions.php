<?php

namespace Phabel\Amp\Serialization;

/**
 * @param string $data Binary data.
 *
 * @return string Unprintable characters encoded as \x##.
 */
function encodeUnprintableChars($data)
{
    if (!\is_string($data)) {
        if (!(\is_string($data) || \is_object($data) && \method_exists($data, '__toString') || (\is_bool($data) || \is_numeric($data)))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($data) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($data) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        } else {
            $data = (string) $data;
        }
    }
    $phabelReturn = \preg_replace_callback("/[^ -~]/", function (array $matches) {
        $phabelReturn = "\\x" . \dechex(\ord($matches[0]));
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }, $data);
    if (!\is_string($phabelReturn)) {
        if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        } else {
            $phabelReturn = (string) $phabelReturn;
        }
    }
    return $phabelReturn;
}
