<?php

namespace Phabel\Amp;

/**
 * A "safe" struct trait for public property aggregators.
 *
 * This trait is intended to make using public properties a little safer by throwing when
 * nonexistent property names are read or written.
 */
trait Struct
{
    /**
     * The minimum percentage [0-100] at which to recommend a similar property
     * name when generating error messages.
     */
    private $__propertySuggestThreshold = 70;
    /**
     * @param string $property
     *
     * @psalm-return no-return
     */
    public function __get($property)
    {
        if (!\is_string($property)) {
            if (!(\is_string($property) || \is_object($property) && \method_exists($property, '__toString') || (\is_bool($property) || \is_numeric($property)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($property) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($property) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $property = (string) $property;
            }
        }
        throw new \Error($this->generateStructPropertyError($property));
    }
    /**
     * @param string $property
     * @param mixed  $value
     *
     * @psalm-return no-return
     */
    public function __set($property, $value)
    {
        if (!\is_string($property)) {
            if (!(\is_string($property) || \is_object($property) && \method_exists($property, '__toString') || (\is_bool($property) || \is_numeric($property)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($property) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($property) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $property = (string) $property;
            }
        }
        throw new \Error($this->generateStructPropertyError($property));
    }
    private function generateStructPropertyError($property)
    {
        if (!\is_string($property)) {
            if (!(\is_string($property) || \is_object($property) && \method_exists($property, '__toString') || (\is_bool($property) || \is_numeric($property)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($property) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($property) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $property = (string) $property;
            }
        }
        $suggestion = $this->suggestPropertyName($property);
        $suggestStr = $suggestion == "" ? "" : " ... did you mean \"{$suggestion}?\"";
        $phabelReturn = \sprintf(
            "%s property \"%s\" does not exist%s",
            \str_replace("\x00", "@", \get_class($this)),
            // Handle anonymous class names.
            $property,
            $suggestStr
        );
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    private function suggestPropertyName($badProperty)
    {
        if (!\is_string($badProperty)) {
            if (!(\is_string($badProperty) || \is_object($badProperty) && \method_exists($badProperty, '__toString') || (\is_bool($badProperty) || \is_numeric($badProperty)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($badProperty) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($badProperty) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $badProperty = (string) $badProperty;
            }
        }
        $badProperty = \strtolower($badProperty);
        $bestMatch = "";
        $bestMatchPercentage = 0;
        /** @psalm-suppress RawObjectIteration */
        foreach ($this as $property => $value) {
            // Never suggest properties that begin with an underscore
            if ($property[0] === "_") {
                continue;
            }
            \similar_text($badProperty, \strtolower($property), $byRefPercentage);
            if ($byRefPercentage > $bestMatchPercentage) {
                $bestMatchPercentage = $byRefPercentage;
                $bestMatch = $property;
            }
        }
        $phabelReturn = $bestMatchPercentage >= $this->__propertySuggestThreshold ? $bestMatch : "";
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
