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

use Phabel\Symfony\Component\Console\Formatter\OutputFormatter;
/**
 * The Formatter class provides helpers to format messages.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class FormatterHelper extends Helper
{
    /**
     * Formats a message within a section.
     *
     * @return string The format section
     */
    public function formatSection($section, $message, $style = 'info')
    {
        if (!\is_string($section)) {
            if (!(\is_string($section) || \is_object($section) && \method_exists($section, '__toString') || (\is_bool($section) || \is_numeric($section)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($section) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($section) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $section = (string) $section;
            }
        }
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        if (!\is_string($style)) {
            if (!(\is_string($style) || \is_object($style) && \method_exists($style, '__toString') || (\is_bool($style) || \is_numeric($style)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($style) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($style) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $style = (string) $style;
            }
        }
        return \sprintf('<%s>[%s]</%s> %s', $style, $section, $style, $message);
    }
    /**
     * Formats a message as a block of text.
     *
     * @param string|array $messages The message to write in the block
     *
     * @return string The formatter message
     */
    public function formatBlock($messages, $style, $large = \false)
    {
        if (!\is_string($style)) {
            if (!(\is_string($style) || \is_object($style) && \method_exists($style, '__toString') || (\is_bool($style) || \is_numeric($style)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($style) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($style) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $style = (string) $style;
            }
        }
        if (!\is_bool($large)) {
            if (!(\is_bool($large) || \is_numeric($large) || \is_string($large))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($large) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($large) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $large = (bool) $large;
            }
        }
        if (!\is_array($messages)) {
            $messages = [$messages];
        }
        $len = 0;
        $lines = [];
        foreach ($messages as $message) {
            $message = OutputFormatter::escape($message);
            $lines[] = \sprintf($large ? '  %s  ' : ' %s ', $message);
            $len = \max(self::width($message) + ($large ? 4 : 2), $len);
        }
        $messages = $large ? [\str_repeat(' ', $len)] : [];
        for ($i = 0; isset($lines[$i]); ++$i) {
            $messages[] = $lines[$i] . \str_repeat(' ', $len - self::width($lines[$i]));
        }
        if ($large) {
            $messages[] = \str_repeat(' ', $len);
        }
        for ($i = 0; isset($messages[$i]); ++$i) {
            $messages[$i] = \sprintf('<%s>%s</%s>', $style, $messages[$i], $style);
        }
        return \implode("\n", $messages);
    }
    /**
     * Truncates a message to the given length.
     *
     * @return string
     */
    public function truncate($message, $length, $suffix = '...')
    {
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        if (!\is_int($length)) {
            if (!(\is_bool($length) || \is_numeric($length))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($length) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $length = (int) $length;
            }
        }
        if (!\is_string($suffix)) {
            if (!(\is_string($suffix) || \is_object($suffix) && \method_exists($suffix, '__toString') || (\is_bool($suffix) || \is_numeric($suffix)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($suffix) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($suffix) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $suffix = (string) $suffix;
            }
        }
        $computedLength = $length - self::width($suffix);
        if ($computedLength > self::width($message)) {
            return $message;
        }
        return self::substr($message, 0, $length) . $suffix;
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'formatter';
    }
}
