<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Helper;

/**
 * Marks a row as being a separator.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class TableSeparator extends TableCell
{
    public function __construct($options = [])
    {
        if (!\is_array($options)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($options) must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($options) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        parent::__construct('', $options);
    }
}
