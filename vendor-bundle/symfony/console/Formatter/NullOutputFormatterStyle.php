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
    public function apply($text)
    {
        if (!\is_string($text)) {
            if (!(\is_string($text) || \is_object($text) && \method_exists($text, '__toString') || (\is_bool($text) || \is_numeric($text)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($text) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($text) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $text = (string) $text;
            }
        }
        $phabelReturn = $text;
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
     * {@inheritdoc}
     */
    public function setBackground($color = null)
    {
        if (!\is_null($color)) {
            if (!\is_string($color)) {
                if (!(\is_string($color) || \is_object($color) && \method_exists($color, '__toString') || (\is_bool($color) || \is_numeric($color)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($color) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($color) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $color = (string) $color;
                }
            }
        }
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function setForeground($color = null)
    {
        if (!\is_null($color)) {
            if (!\is_string($color)) {
                if (!(\is_string($color) || \is_object($color) && \method_exists($color, '__toString') || (\is_bool($color) || \is_numeric($color)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($color) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($color) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $color = (string) $color;
                }
            }
        }
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function setOption($option)
    {
        if (!\is_string($option)) {
            if (!(\is_string($option) || \is_object($option) && \method_exists($option, '__toString') || (\is_bool($option) || \is_numeric($option)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($option) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($option) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $option = (string) $option;
            }
        }
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
    public function unsetOption($option)
    {
        if (!\is_string($option)) {
            if (!(\is_string($option) || \is_object($option) && \method_exists($option, '__toString') || (\is_bool($option) || \is_numeric($option)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($option) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($option) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $option = (string) $option;
            }
        }
        // do nothing
    }
}
