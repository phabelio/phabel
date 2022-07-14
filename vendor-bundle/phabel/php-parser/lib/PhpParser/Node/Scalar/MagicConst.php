<?php

declare (strict_types=1);
namespace PhabelVendor\PhpParser\Node\Scalar;

use PhabelVendor\PhpParser\Node\Scalar;
abstract class MagicConst extends Scalar
{
    /**
     * Constructs a magic constant node.
     *
     * @param array $attributes Additional attributes
     */
    public function __construct(array $attributes = array())
    {
        $this->attributes = $attributes;
    }
    /**
     *
     */
    public function getSubNodeNames() : array
    {
        return [];
    }
    /**
     * Get name of magic constant.
     *
     * @return string Name of magic constant
     */
    public abstract function getName() : string;
}
