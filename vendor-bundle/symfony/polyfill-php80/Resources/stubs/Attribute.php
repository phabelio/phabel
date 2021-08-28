<?php

namespace Phabel;

#[Attribute(\Attribute::TARGET_CLASS)]
final class Attribute
{
    const TARGET_CLASS = 1;
    const TARGET_FUNCTION = 2;
    const TARGET_METHOD = 4;
    const TARGET_PROPERTY = 8;
    const TARGET_CLASS_CONSTANT = 16;
    const TARGET_PARAMETER = 32;
    const TARGET_ALL = 63;
    const IS_REPEATABLE = 64;
    /** @var int */
    public $flags;
    public function __construct($flags = self::TARGET_ALL)
    {
        if (!\is_int($flags)) {
            if (!(\is_bool($flags) || \is_numeric($flags))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($flags) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($flags) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $flags = (int) $flags;
            }
        }
        $this->flags = $flags;
    }
}
\class_alias('Phabel\\Attribute', 'Attribute', \false);
