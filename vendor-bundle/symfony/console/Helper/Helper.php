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

use Phabel\Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Phabel\Symfony\Component\String\UnicodeString;
/**
 * Helper is the base class for all helper classes.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class Helper implements HelperInterface
{
    protected $helperSet = null;
    /**
     * {@inheritdoc}
     */
    public function setHelperSet(HelperSet $helperSet = null)
    {
        $this->helperSet = $helperSet;
    }
    /**
     * {@inheritdoc}
     */
    public function getHelperSet()
    {
        return $this->helperSet;
    }
    /**
     * Returns the length of a string, using mb_strwidth if it is available.
     *
     * @deprecated since 5.3
     *
     * @return int The length of the string
     */
    public static function strlen($string)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        trigger_deprecation('symfony/console', '5.3', 'Method "%s()" is deprecated and will be removed in Symfony 6.0. Use Helper::width() or Helper::length() instead.', __METHOD__);
        return self::width($string);
    }
    /**
     * Returns the width of a string, using mb_strwidth if it is available.
     * The width is how many characters positions the string will use.
     */
    public static function width($string)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        isset($string) ? $string : ($string = '');
        if (\preg_match('//u', $string)) {
            $phabelReturn = (new UnicodeString($string))->width(\false);
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        if (\false === ($encoding = \mb_detect_encoding($string, null, \true))) {
            $phabelReturn = \strlen($string);
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = \mb_strwidth($string, $encoding);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Returns the length of a string, using mb_strlen if it is available.
     * The length is related to how many bytes the string will use.
     */
    public static function length($string)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        isset($string) ? $string : ($string = '');
        if (\preg_match('//u', $string)) {
            $phabelReturn = (new UnicodeString($string))->length();
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        if (\false === ($encoding = \mb_detect_encoding($string, null, \true))) {
            $phabelReturn = \strlen($string);
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = \mb_strlen($string, $encoding);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Returns the subset of a string, using mb_substr if it is available.
     *
     * @return string The string subset
     */
    public static function substr($string, $from, $length = null)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!\is_int($from)) {
            if (!(\is_bool($from) || \is_numeric($from))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($from) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($from) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $from = (int) $from;
            }
        }
        if (!\is_null($length)) {
            if (!\is_int($length)) {
                if (!(\is_bool($length) || \is_numeric($length))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($length) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $length = (int) $length;
                }
            }
        }
        isset($string) ? $string : ($string = '');
        if (\false === ($encoding = \mb_detect_encoding($string, null, \true))) {
            return \substr($string, $from, $length);
        }
        return \mb_substr($string, $from, $length, $encoding);
    }
    public static function formatTime($secs)
    {
        static $timeFormats = [[0, '< 1 sec'], [1, '1 sec'], [2, 'secs', 1], [60, '1 min'], [120, 'mins', 60], [3600, '1 hr'], [7200, 'hrs', 3600], [86400, '1 day'], [172800, 'days', 86400]];
        foreach ($timeFormats as $index => $format) {
            if ($secs >= $format[0]) {
                if (isset($timeFormats[$index + 1]) && $secs < $timeFormats[$index + 1][0] || $index == \count($timeFormats) - 1) {
                    if (2 == \count($format)) {
                        return $format[1];
                    }
                    return \floor($secs / $format[2]) . ' ' . $format[1];
                }
            }
        }
    }
    public static function formatMemory($memory)
    {
        if (!\is_int($memory)) {
            if (!(\is_bool($memory) || \is_numeric($memory))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($memory) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($memory) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $memory = (int) $memory;
            }
        }
        if ($memory >= 1024 * 1024 * 1024) {
            return \sprintf('%.1f GiB', $memory / 1024 / 1024 / 1024);
        }
        if ($memory >= 1024 * 1024) {
            return \sprintf('%.1f MiB', $memory / 1024 / 1024);
        }
        if ($memory >= 1024) {
            return \sprintf('%d KiB', $memory / 1024);
        }
        return \sprintf('%d B', $memory);
    }
    /**
     * @deprecated since 5.3
     */
    public static function strlenWithoutDecoration(OutputFormatterInterface $formatter, $string)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        trigger_deprecation('symfony/console', '5.3', 'Method "%s()" is deprecated and will be removed in Symfony 6.0. Use Helper::removeDecoration() instead.', __METHOD__);
        return self::width(self::removeDecoration($formatter, $string));
    }
    public static function removeDecoration(OutputFormatterInterface $formatter, $string)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        $isDecorated = $formatter->isDecorated();
        $formatter->setDecorated(\false);
        // remove <...> formatting
        $string = $formatter->format(isset($string) ? $string : '');
        // remove already formatted characters
        $string = \preg_replace("/\x1b\\[[^m]*m/", '', $string);
        $formatter->setDecorated($isDecorated);
        return $string;
    }
}
