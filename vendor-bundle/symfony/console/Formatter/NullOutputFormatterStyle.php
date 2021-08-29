<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Formatter;

/**
 * @author Tien Xuan Vo <tien.xuan.vo@gmail.com>
 */
final class NullOutputFormatterStyle implements OutputFormatterStyleInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(string $text) : string
    {
        return $text;
    }
    /**
     * {@inheritdoc}
     */
    public function setBackground(string $color = null)
    {
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function setForeground(string $color = null)
    {
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function setOption(string $option)
    {
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function unsetOption(string $option)
    {
        // do nothing
    }
}
