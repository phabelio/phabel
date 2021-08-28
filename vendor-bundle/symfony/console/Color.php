<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console;

use Phabel\Symfony\Component\Console\Exception\InvalidArgumentException;
/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class Color
{
    const COLORS = ['black' => 0, 'red' => 1, 'green' => 2, 'yellow' => 3, 'blue' => 4, 'magenta' => 5, 'cyan' => 6, 'white' => 7, 'default' => 9];
    const BRIGHT_COLORS = ['gray' => 0, 'bright-red' => 1, 'bright-green' => 2, 'bright-yellow' => 3, 'bright-blue' => 4, 'bright-magenta' => 5, 'bright-cyan' => 6, 'bright-white' => 7];
    const AVAILABLE_OPTIONS = ['bold' => ['set' => 1, 'unset' => 22], 'underscore' => ['set' => 4, 'unset' => 24], 'blink' => ['set' => 5, 'unset' => 25], 'reverse' => ['set' => 7, 'unset' => 27], 'conceal' => ['set' => 8, 'unset' => 28]];
    private $foreground;
    private $background;
    private $options = [];
    public function __construct($foreground = '', $background = '', array $options = [])
    {
        if (!\is_string($foreground)) {
            if (!(\is_string($foreground) || \is_object($foreground) && \method_exists($foreground, '__toString') || (\is_bool($foreground) || \is_numeric($foreground)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($foreground) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($foreground) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $foreground = (string) $foreground;
            }
        }
        if (!\is_string($background)) {
            if (!(\is_string($background) || \is_object($background) && \method_exists($background, '__toString') || (\is_bool($background) || \is_numeric($background)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($background) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($background) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $background = (string) $background;
            }
        }
        $this->foreground = $this->parseColor($foreground);
        $this->background = $this->parseColor($background, \true);
        foreach ($options as $option) {
            if (!isset(self::AVAILABLE_OPTIONS[$option])) {
                throw new InvalidArgumentException(\sprintf('Invalid option specified: "%s". Expected one of (%s).', $option, \implode(', ', \array_keys(self::AVAILABLE_OPTIONS))));
            }
            $this->options[$option] = self::AVAILABLE_OPTIONS[$option];
        }
    }
    public function apply($text)
    {
        if (!\is_string($text)) {
            if (!(\is_string($text) || \is_object($text) && \method_exists($text, '__toString') || (\is_bool($text) || \is_numeric($text)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($text) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($text) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $text = (string) $text;
            }
        }
        $phabelReturn = $this->set() . $text . $this->unset();
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function set()
    {
        $setCodes = [];
        if ('' !== $this->foreground) {
            $setCodes[] = $this->foreground;
        }
        if ('' !== $this->background) {
            $setCodes[] = $this->background;
        }
        foreach ($this->options as $option) {
            $setCodes[] = $option['set'];
        }
        if (0 === \count($setCodes)) {
            $phabelReturn = '';
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = \sprintf("\x1b[%sm", \implode(';', $setCodes));
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function unset()
    {
        $unsetCodes = [];
        if ('' !== $this->foreground) {
            $unsetCodes[] = 39;
        }
        if ('' !== $this->background) {
            $unsetCodes[] = 49;
        }
        foreach ($this->options as $option) {
            $unsetCodes[] = $option['unset'];
        }
        if (0 === \count($unsetCodes)) {
            $phabelReturn = '';
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = \sprintf("\x1b[%sm", \implode(';', $unsetCodes));
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    private function parseColor($color, $background = \false)
    {
        if (!\is_string($color)) {
            if (!(\is_string($color) || \is_object($color) && \method_exists($color, '__toString') || (\is_bool($color) || \is_numeric($color)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($color) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($color) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $color = (string) $color;
            }
        }
        if (!\is_bool($background)) {
            if (!(\is_bool($background) || \is_numeric($background) || \is_string($background))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($background) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($background) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $background = (bool) $background;
            }
        }
        if ('' === $color) {
            $phabelReturn = '';
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        if ('#' === $color[0]) {
            $color = \substr($color, 1);
            if (3 === \strlen($color)) {
                $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
            }
            if (6 !== \strlen($color)) {
                throw new InvalidArgumentException(\sprintf('Invalid "%s" color.', $color));
            }
            $phabelReturn = ($background ? '4' : '3') . $this->convertHexColorToAnsi(\hexdec($color));
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        if (isset(self::COLORS[$color])) {
            $phabelReturn = ($background ? '4' : '3') . self::COLORS[$color];
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        if (isset(self::BRIGHT_COLORS[$color])) {
            $phabelReturn = ($background ? '10' : '9') . self::BRIGHT_COLORS[$color];
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        throw new InvalidArgumentException(\sprintf('Invalid "%s" color; expected one of (%s).', $color, \implode(', ', \array_merge(\array_keys(self::COLORS), \array_keys(self::BRIGHT_COLORS)))));
        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    private function convertHexColorToAnsi($color)
    {
        if (!\is_int($color)) {
            if (!(\is_bool($color) || \is_numeric($color))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($color) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($color) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $color = (int) $color;
            }
        }
        $r = $color >> 16 & 255;
        $g = $color >> 8 & 255;
        $b = $color & 255;
        // see https://github.com/termstandard/colors/ for more information about true color support
        if ('truecolor' !== \getenv('COLORTERM')) {
            $phabelReturn = (string) $this->degradeHexColorToAnsi($r, $g, $b);
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = \sprintf('8;2;%d;%d;%d', $r, $g, $b);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    private function degradeHexColorToAnsi($r, $g, $b)
    {
        if (!\is_int($r)) {
            if (!(\is_bool($r) || \is_numeric($r))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($r) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($r) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $r = (int) $r;
            }
        }
        if (!\is_int($g)) {
            if (!(\is_bool($g) || \is_numeric($g))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($g) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($g) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $g = (int) $g;
            }
        }
        if (!\is_int($b)) {
            if (!(\is_bool($b) || \is_numeric($b))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($b) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($b) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $b = (int) $b;
            }
        }
        if (0 === \round($this->getSaturation($r, $g, $b) / 50)) {
            $phabelReturn = 0;
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = \round($b / 255) << 2 | \round($g / 255) << 1 | \round($r / 255);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    private function getSaturation($r, $g, $b)
    {
        if (!\is_int($r)) {
            if (!(\is_bool($r) || \is_numeric($r))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($r) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($r) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $r = (int) $r;
            }
        }
        if (!\is_int($g)) {
            if (!(\is_bool($g) || \is_numeric($g))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($g) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($g) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $g = (int) $g;
            }
        }
        if (!\is_int($b)) {
            if (!(\is_bool($b) || \is_numeric($b))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($b) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($b) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $b = (int) $b;
            }
        }
        $r = $r / 255;
        $g = $g / 255;
        $b = $b / 255;
        $v = \max($r, $g, $b);
        if (0 === ($diff = $v - \min($r, $g, $b))) {
            $phabelReturn = 0;
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = (int) $diff * 100 / $v;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
