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

use Phabel\Symfony\Component\Console\Exception\InvalidArgumentException;
/**
 * @author Abdellatif Ait boudad <a.aitboudad@gmail.com>
 */
class TableCell
{
    private $value;
    private $options = ['rowspan' => 1, 'colspan' => 1, 'style' => null];
    public function __construct($value = '', array $options = [])
    {
        if (!\is_string($value)) {
            if (!(\is_string($value) || \is_object($value) && \method_exists($value, '__toString') || (\is_bool($value) || \is_numeric($value)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($value) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($value) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $value = (string) $value;
            }
        }
        $this->value = $value;
        // check option names
        if ($diff = \array_diff(\array_keys($options), \array_keys($this->options))) {
            throw new InvalidArgumentException(\sprintf('The TableCell does not support the following options: \'%s\'.', \implode('\', \'', $diff)));
        }
        if (isset($options['style']) && !$options['style'] instanceof TableCellStyle) {
            throw new InvalidArgumentException('The style option must be an instance of "TableCellStyle".');
        }
        $this->options = \array_merge($this->options, $options);
    }
    /**
     * Returns the cell value.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
    /**
     * Gets number of colspan.
     *
     * @return int
     */
    public function getColspan()
    {
        return (int) $this->options['colspan'];
    }
    /**
     * Gets number of rowspan.
     *
     * @return int
     */
    public function getRowspan()
    {
        return (int) $this->options['rowspan'];
    }
    public function getStyle()
    {
        $phabelReturn = $this->options['style'];
        if (!($phabelReturn instanceof TableCellStyle || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?TableCellStyle, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
