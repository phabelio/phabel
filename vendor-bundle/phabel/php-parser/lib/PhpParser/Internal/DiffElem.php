<?php

namespace Phabel\PhpParser\Internal;

/**
 * @internal
 */
class DiffElem
{
    const TYPE_KEEP = 0;
    const TYPE_REMOVE = 1;
    const TYPE_ADD = 2;
    const TYPE_REPLACE = 3;
    /** @var int One of the TYPE_* constants */
    public $type;
    /** @var mixed Is null for add operations */
    public $old;
    /** @var mixed Is null for remove operations */
    public $new;
    public function __construct($type, $old, $new)
    {
        if (!\is_int($type)) {
            if (!(\is_bool($type) || \is_numeric($type))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($type) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $type = (int) $type;
            }
        }
        $this->type = $type;
        $this->old = $old;
        $this->new = $new;
    }
}
