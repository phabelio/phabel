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
 * @author Yewhen Khoptynskyi <khoptynskyi@gmail.com>
 */
class TableCellStyle
{
    const DEFAULT_ALIGN = 'left';
    private $options = ['fg' => 'default', 'bg' => 'default', 'options' => null, 'align' => self::DEFAULT_ALIGN, 'cellFormat' => null];
    private $tagOptions = ['fg', 'bg', 'options'];
    private $alignMap = ['left' => \STR_PAD_RIGHT, 'center' => \STR_PAD_BOTH, 'right' => \STR_PAD_LEFT];
    public function __construct(array $options = [])
    {
        if ($diff = \array_diff(\array_keys($options), \array_keys($this->options))) {
            throw new InvalidArgumentException(\sprintf('The TableCellStyle does not support the following options: \'%s\'.', \implode('\', \'', $diff)));
        }
        if (isset($options['align']) && !\array_key_exists($options['align'], $this->alignMap)) {
            throw new InvalidArgumentException(\sprintf('Wrong align value. Value must be following: \'%s\'.', \implode('\', \'', \array_keys($this->alignMap))));
        }
        $this->options = \array_merge($this->options, $options);
    }
    public function getOptions() : array
    {
        return $this->options;
    }
    /**
     * Gets options we need for tag for example fg, bg.
     *
     * @return string[]
     */
    public function getTagOptions()
    {
        return \Phabel\Target\Php74\Polyfill::array_filter($this->getOptions(), function ($key) {
            return \in_array($key, $this->tagOptions) && isset($this->options[$key]);
        }, \ARRAY_FILTER_USE_KEY);
    }
    public function getPadByAlign()
    {
        return $this->alignMap[$this->getOptions()['align']];
    }
    public function getCellFormat()
    {
        $phabelReturn = $this->getOptions()['cellFormat'];
        if (!\is_null($phabelReturn)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
