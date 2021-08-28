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

use Phabel\Symfony\Component\Console\Cursor;
use Phabel\Symfony\Component\Console\Exception\LogicException;
use Phabel\Symfony\Component\Console\Output\ConsoleOutputInterface;
use Phabel\Symfony\Component\Console\Output\ConsoleSectionOutput;
use Phabel\Symfony\Component\Console\Output\OutputInterface;
use Phabel\Symfony\Component\Console\Terminal;
/**
 * The ProgressBar provides helpers to display progress output.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Chris Jones <leeked@gmail.com>
 */
final class ProgressBar
{
    const FORMAT_VERBOSE = 'verbose';
    const FORMAT_VERY_VERBOSE = 'very_verbose';
    const FORMAT_DEBUG = 'debug';
    const FORMAT_NORMAL = 'normal';
    const FORMAT_VERBOSE_NOMAX = 'verbose_nomax';
    const FORMAT_VERY_VERBOSE_NOMAX = 'very_verbose_nomax';
    const FORMAT_DEBUG_NOMAX = 'debug_nomax';
    const FORMAT_NORMAL_NOMAX = 'normal_nomax';
    private $barWidth = 28;
    private $barChar;
    private $emptyBarChar = '-';
    private $progressChar = '>';
    private $format;
    private $internalFormat;
    private $redrawFreq = 1;
    private $writeCount;
    private $lastWriteTime;
    private $minSecondsBetweenRedraws = 0;
    private $maxSecondsBetweenRedraws = 1;
    private $output;
    private $step = 0;
    private $max;
    private $startTime;
    private $stepWidth;
    private $percent = 0.0;
    private $formatLineCount;
    private $messages = [];
    private $overwrite = \true;
    private $terminal;
    private $previousMessage;
    private $cursor;
    private static $formatters;
    private static $formats;
    /**
     * @param int $max Maximum steps (0 if unknown)
     */
    public function __construct(OutputInterface $output, $max = 0, $minSecondsBetweenRedraws = 1 / 25)
    {
        if (!\is_int($max)) {
            if (!(\is_bool($max) || \is_numeric($max))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($max) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($max) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $max = (int) $max;
            }
        }
        if (!\is_float($minSecondsBetweenRedraws)) {
            if (!(\is_bool($minSecondsBetweenRedraws) || \is_numeric($minSecondsBetweenRedraws))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($minSecondsBetweenRedraws) must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($minSecondsBetweenRedraws) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $minSecondsBetweenRedraws = (double) $minSecondsBetweenRedraws;
            }
        }
        if ($output instanceof ConsoleOutputInterface) {
            $output = $output->getErrorOutput();
        }
        $this->output = $output;
        $this->setMaxSteps($max);
        $this->terminal = new Terminal();
        if (0 < $minSecondsBetweenRedraws) {
            $this->redrawFreq = null;
            $this->minSecondsBetweenRedraws = $minSecondsBetweenRedraws;
        }
        if (!$this->output->isDecorated()) {
            // disable overwrite when output does not support ANSI codes.
            $this->overwrite = \false;
            // set a reasonable redraw frequency so output isn't flooded
            $this->redrawFreq = null;
        }
        $this->startTime = \time();
        $this->cursor = new Cursor($output);
    }
    /**
     * Sets a placeholder formatter for a given name.
     *
     * This method also allow you to override an existing placeholder.
     *
     * @param string   $name     The placeholder name (including the delimiter char like %)
     * @param callable $callable A PHP callable
     */
    public static function setPlaceholderFormatterDefinition($name, callable $callable)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!self::$formatters) {
            self::$formatters = self::initPlaceholderFormatters();
        }
        self::$formatters[$name] = $callable;
    }
    /**
     * Gets the placeholder formatter for a given name.
     *
     * @param string $name The placeholder name (including the delimiter char like %)
     *
     * @return callable|null A PHP callable
     */
    public static function getPlaceholderFormatterDefinition($name)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!self::$formatters) {
            self::$formatters = self::initPlaceholderFormatters();
        }
        $phabelReturn = isset(self::$formatters[$name]) ? self::$formatters[$name] : null;
        if (!(\is_callable($phabelReturn) || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Sets a format for a given name.
     *
     * This method also allow you to override an existing format.
     *
     * @param string $name   The format name
     * @param string $format A format string
     */
    public static function setFormatDefinition($name, $format)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!\is_string($format)) {
            if (!(\is_string($format) || \is_object($format) && \method_exists($format, '__toString') || (\is_bool($format) || \is_numeric($format)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($format) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($format) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $format = (string) $format;
            }
        }
        if (!self::$formats) {
            self::$formats = self::initFormats();
        }
        self::$formats[$name] = $format;
    }
    /**
     * Gets the format for a given name.
     *
     * @param string $name The format name
     *
     * @return string|null A format string
     */
    public static function getFormatDefinition($name)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!self::$formats) {
            self::$formats = self::initFormats();
        }
        $phabelReturn = isset(self::$formats[$name]) ? self::$formats[$name] : null;
        if (!\is_null($phabelReturn)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
    /**
     * Associates a text with a named placeholder.
     *
     * The text is displayed when the progress bar is rendered but only
     * when the corresponding placeholder is part of the custom format line
     * (by wrapping the name with %).
     *
     * @param string $message The text to associate with the placeholder
     * @param string $name    The name of the placeholder
     */
    public function setMessage($message, $name = 'message')
    {
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        $this->messages[$name] = $message;
    }
    public function getMessage($name = 'message')
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        return $this->messages[$name];
    }
    public function getStartTime()
    {
        $phabelReturn = $this->startTime;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function getMaxSteps()
    {
        $phabelReturn = $this->max;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function getProgress()
    {
        $phabelReturn = $this->step;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    private function getStepWidth()
    {
        $phabelReturn = $this->stepWidth;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function getProgressPercent()
    {
        $phabelReturn = $this->percent;
        if (!\is_float($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (double) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function getBarOffset()
    {
        $phabelReturn = \floor($this->max ? $this->percent * $this->barWidth : (null === $this->redrawFreq ? (int) (\min(5, $this->barWidth / 15) * $this->writeCount) : $this->step) % $this->barWidth);
        if (!\is_float($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (double) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function getEstimated()
    {
        if (!$this->step) {
            $phabelReturn = 0;
            if (!\is_float($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (double) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = \round((\time() - $this->startTime) / $this->step * $this->max);
        if (!\is_float($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (double) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function getRemaining()
    {
        if (!$this->step) {
            $phabelReturn = 0;
            if (!\is_float($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (double) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = \round((\time() - $this->startTime) / $this->step * ($this->max - $this->step));
        if (!\is_float($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (double) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function setBarWidth($size)
    {
        if (!\is_int($size)) {
            if (!(\is_bool($size) || \is_numeric($size))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($size) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($size) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $size = (int) $size;
            }
        }
        $this->barWidth = \max(1, $size);
    }
    public function getBarWidth()
    {
        $phabelReturn = $this->barWidth;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function setBarCharacter($char)
    {
        if (!\is_string($char)) {
            if (!(\is_string($char) || \is_object($char) && \method_exists($char, '__toString') || (\is_bool($char) || \is_numeric($char)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($char) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($char) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $char = (string) $char;
            }
        }
        $this->barChar = $char;
    }
    public function getBarCharacter()
    {
        if (null === $this->barChar) {
            $phabelReturn = $this->max ? '=' : $this->emptyBarChar;
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = $this->barChar;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function setEmptyBarCharacter($char)
    {
        if (!\is_string($char)) {
            if (!(\is_string($char) || \is_object($char) && \method_exists($char, '__toString') || (\is_bool($char) || \is_numeric($char)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($char) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($char) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $char = (string) $char;
            }
        }
        $this->emptyBarChar = $char;
    }
    public function getEmptyBarCharacter()
    {
        $phabelReturn = $this->emptyBarChar;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function setProgressCharacter($char)
    {
        if (!\is_string($char)) {
            if (!(\is_string($char) || \is_object($char) && \method_exists($char, '__toString') || (\is_bool($char) || \is_numeric($char)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($char) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($char) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $char = (string) $char;
            }
        }
        $this->progressChar = $char;
    }
    public function getProgressCharacter()
    {
        $phabelReturn = $this->progressChar;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function setFormat($format)
    {
        if (!\is_string($format)) {
            if (!(\is_string($format) || \is_object($format) && \method_exists($format, '__toString') || (\is_bool($format) || \is_numeric($format)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($format) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($format) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $format = (string) $format;
            }
        }
        $this->format = null;
        $this->internalFormat = $format;
    }
    /**
     * Sets the redraw frequency.
     *
     * @param int|null $freq The frequency in steps
     */
    public function setRedrawFrequency($freq)
    {
        if (!\is_null($freq)) {
            if (!\is_int($freq)) {
                if (!(\is_bool($freq) || \is_numeric($freq))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($freq) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($freq) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $freq = (int) $freq;
                }
            }
        }
        $this->redrawFreq = null !== $freq ? \max(1, $freq) : null;
    }
    public function minSecondsBetweenRedraws($seconds)
    {
        if (!\is_float($seconds)) {
            if (!(\is_bool($seconds) || \is_numeric($seconds))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($seconds) must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($seconds) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $seconds = (double) $seconds;
            }
        }
        $this->minSecondsBetweenRedraws = $seconds;
    }
    public function maxSecondsBetweenRedraws($seconds)
    {
        if (!\is_float($seconds)) {
            if (!(\is_bool($seconds) || \is_numeric($seconds))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($seconds) must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($seconds) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $seconds = (double) $seconds;
            }
        }
        $this->maxSecondsBetweenRedraws = $seconds;
    }
    /**
     * Returns an iterator that will automatically update the progress bar when iterated.
     *
     * @param int|null $max Number of steps to complete the bar (0 if indeterminate), if null it will be inferred from $iterable
     */
    public function iterate($iterable, $max = null)
    {
        if (!(\is_array($iterable) || $iterable instanceof \Traversable)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($iterable) must be of type iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($iterable) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_null($max)) {
            if (!\is_int($max)) {
                if (!(\is_bool($max) || \is_numeric($max))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($max) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($max) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $max = (int) $max;
                }
            }
        }
        $this->start(isset($max) ? $max : (\is_countable($iterable) ? \count($iterable) : 0));
        foreach ($iterable as $key => $value) {
            (yield $key => $value);
            $this->advance();
        }
        $this->finish();
    }
    /**
     * Starts the progress output.
     *
     * @param int|null $max Number of steps to complete the bar (0 if indeterminate), null to leave unchanged
     */
    public function start($max = null)
    {
        if (!\is_null($max)) {
            if (!\is_int($max)) {
                if (!(\is_bool($max) || \is_numeric($max))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($max) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($max) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $max = (int) $max;
                }
            }
        }
        $this->startTime = \time();
        $this->step = 0;
        $this->percent = 0.0;
        if (null !== $max) {
            $this->setMaxSteps($max);
        }
        $this->display();
    }
    /**
     * Advances the progress output X steps.
     *
     * @param int $step Number of steps to advance
     */
    public function advance($step = 1)
    {
        if (!\is_int($step)) {
            if (!(\is_bool($step) || \is_numeric($step))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($step) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($step) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $step = (int) $step;
            }
        }
        $this->setProgress($this->step + $step);
    }
    /**
     * Sets whether to overwrite the progressbar, false for new line.
     */
    public function setOverwrite($overwrite)
    {
        if (!\is_bool($overwrite)) {
            if (!(\is_bool($overwrite) || \is_numeric($overwrite) || \is_string($overwrite))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($overwrite) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($overwrite) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $overwrite = (bool) $overwrite;
            }
        }
        $this->overwrite = $overwrite;
    }
    public function setProgress($step)
    {
        if (!\is_int($step)) {
            if (!(\is_bool($step) || \is_numeric($step))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($step) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($step) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $step = (int) $step;
            }
        }
        if ($this->max && $step > $this->max) {
            $this->max = $step;
        } elseif ($step < 0) {
            $step = 0;
        }
        $redrawFreq = isset($this->redrawFreq) ? $this->redrawFreq : ($this->max ?: 10) / 10;
        $prevPeriod = (int) ($this->step / $redrawFreq);
        $currPeriod = (int) ($step / $redrawFreq);
        $this->step = $step;
        $this->percent = $this->max ? (float) $this->step / $this->max : 0;
        $timeInterval = \microtime(\true) - $this->lastWriteTime;
        // Draw regardless of other limits
        if ($this->max === $step) {
            $this->display();
            return;
        }
        // Throttling
        if ($timeInterval < $this->minSecondsBetweenRedraws) {
            return;
        }
        // Draw each step period, but not too late
        if ($prevPeriod !== $currPeriod || $timeInterval >= $this->maxSecondsBetweenRedraws) {
            $this->display();
        }
    }
    public function setMaxSteps($max)
    {
        if (!\is_int($max)) {
            if (!(\is_bool($max) || \is_numeric($max))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($max) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($max) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $max = (int) $max;
            }
        }
        $this->format = null;
        $this->max = \max(0, $max);
        $this->stepWidth = $this->max ? Helper::width((string) $this->max) : 4;
    }
    /**
     * Finishes the progress output.
     */
    public function finish()
    {
        if (!$this->max) {
            $this->max = $this->step;
        }
        if ($this->step === $this->max && !$this->overwrite) {
            // prevent double 100% output
            return;
        }
        $this->setProgress($this->max);
    }
    /**
     * Outputs the current progress string.
     */
    public function display()
    {
        if (OutputInterface::VERBOSITY_QUIET === $this->output->getVerbosity()) {
            return;
        }
        if (null === $this->format) {
            $this->setRealFormat($this->internalFormat ?: $this->determineBestFormat());
        }
        $this->overwrite($this->buildLine());
    }
    /**
     * Removes the progress bar from the current line.
     *
     * This is useful if you wish to write some output
     * while a progress bar is running.
     * Call display() to show the progress bar again.
     */
    public function clear()
    {
        if (!$this->overwrite) {
            return;
        }
        if (null === $this->format) {
            $this->setRealFormat($this->internalFormat ?: $this->determineBestFormat());
        }
        $this->overwrite('');
    }
    private function setRealFormat($format)
    {
        if (!\is_string($format)) {
            if (!(\is_string($format) || \is_object($format) && \method_exists($format, '__toString') || (\is_bool($format) || \is_numeric($format)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($format) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($format) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $format = (string) $format;
            }
        }
        // try to use the _nomax variant if available
        if (!$this->max && null !== self::getFormatDefinition($format . '_nomax')) {
            $this->format = self::getFormatDefinition($format . '_nomax');
        } elseif (null !== self::getFormatDefinition($format)) {
            $this->format = self::getFormatDefinition($format);
        } else {
            $this->format = $format;
        }
        $this->formatLineCount = \substr_count($this->format, "\n");
    }
    /**
     * Overwrites a previous message to the output.
     */
    private function overwrite($message)
    {
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        if ($this->previousMessage === $message) {
            return;
        }
        $originalMessage = $message;
        if ($this->overwrite) {
            if (null !== $this->previousMessage) {
                if ($this->output instanceof ConsoleSectionOutput) {
                    $messageLines = \explode("\n", $message);
                    $lineCount = \count($messageLines);
                    foreach ($messageLines as $messageLine) {
                        $messageLineLength = Helper::width(Helper::removeDecoration($this->output->getFormatter(), $messageLine));
                        if ($messageLineLength > $this->terminal->getWidth()) {
                            $lineCount += \floor($messageLineLength / $this->terminal->getWidth());
                        }
                    }
                    $this->output->clear($lineCount);
                } else {
                    if ($this->formatLineCount > 0) {
                        $this->cursor->moveUp($this->formatLineCount);
                    }
                    $this->cursor->moveToColumn(1);
                    $this->cursor->clearLine();
                }
            }
        } elseif ($this->step > 0) {
            $message = \PHP_EOL . $message;
        }
        $this->previousMessage = $originalMessage;
        $this->lastWriteTime = \microtime(\true);
        $this->output->write($message);
        ++$this->writeCount;
    }
    private function determineBestFormat()
    {
        switch ($this->output->getVerbosity()) {
            // OutputInterface::VERBOSITY_QUIET: display is disabled anyway
            case OutputInterface::VERBOSITY_VERBOSE:
                $phabelReturn = $this->max ? self::FORMAT_VERBOSE : self::FORMAT_VERBOSE_NOMAX;
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            case OutputInterface::VERBOSITY_VERY_VERBOSE:
                $phabelReturn = $this->max ? self::FORMAT_VERY_VERBOSE : self::FORMAT_VERY_VERBOSE_NOMAX;
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            case OutputInterface::VERBOSITY_DEBUG:
                $phabelReturn = $this->max ? self::FORMAT_DEBUG : self::FORMAT_DEBUG_NOMAX;
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            default:
                $phabelReturn = $this->max ? self::FORMAT_NORMAL : self::FORMAT_NORMAL_NOMAX;
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
        }
        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    private static function initPlaceholderFormatters()
    {
        $phabelReturn = ['bar' => function (self $bar, OutputInterface $output) {
            $completeBars = $bar->getBarOffset();
            $display = \str_repeat($bar->getBarCharacter(), $completeBars);
            if ($completeBars < $bar->getBarWidth()) {
                $emptyBars = $bar->getBarWidth() - $completeBars - Helper::length(Helper::removeDecoration($output->getFormatter(), $bar->getProgressCharacter()));
                $display .= $bar->getProgressCharacter() . \str_repeat($bar->getEmptyBarCharacter(), $emptyBars);
            }
            return $display;
        }, 'elapsed' => function (self $bar) {
            return Helper::formatTime(\time() - $bar->getStartTime());
        }, 'remaining' => function (self $bar) {
            if (!$bar->getMaxSteps()) {
                throw new LogicException('Unable to display the remaining time if the maximum number of steps is not set.');
            }
            return Helper::formatTime($bar->getRemaining());
        }, 'estimated' => function (self $bar) {
            if (!$bar->getMaxSteps()) {
                throw new LogicException('Unable to display the estimated time if the maximum number of steps is not set.');
            }
            return Helper::formatTime($bar->getEstimated());
        }, 'memory' => function (self $bar) {
            return Helper::formatMemory(\memory_get_usage(\true));
        }, 'current' => function (self $bar) {
            return \str_pad($bar->getProgress(), $bar->getStepWidth(), ' ', \STR_PAD_LEFT);
        }, 'max' => function (self $bar) {
            return $bar->getMaxSteps();
        }, 'percent' => function (self $bar) {
            return \floor($bar->getProgressPercent() * 100);
        }];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private static function initFormats()
    {
        $phabelReturn = [self::FORMAT_NORMAL => ' %current%/%max% [%bar%] %percent:3s%%', self::FORMAT_NORMAL_NOMAX => ' %current% [%bar%]', self::FORMAT_VERBOSE => ' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%', self::FORMAT_VERBOSE_NOMAX => ' %current% [%bar%] %elapsed:6s%', self::FORMAT_VERY_VERBOSE => ' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s%', self::FORMAT_VERY_VERBOSE_NOMAX => ' %current% [%bar%] %elapsed:6s%', self::FORMAT_DEBUG => ' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%', self::FORMAT_DEBUG_NOMAX => ' %current% [%bar%] %elapsed:6s% %memory:6s%'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function buildLine()
    {
        $regex = "{%([a-z\\-_]+)(?:\\:([^%]+))?%}i";
        $callback = function ($matches) {
            if ($formatter = $this::getPlaceholderFormatterDefinition($matches[1])) {
                $text = $formatter($this, $this->output);
            } elseif (isset($this->messages[$matches[1]])) {
                $text = $this->messages[$matches[1]];
            } else {
                return $matches[0];
            }
            if (isset($matches[2])) {
                $text = \sprintf('%' . $matches[2], $text);
            }
            return $text;
        };
        $line = \preg_replace_callback($regex, $callback, $this->format);
        // gets string length for each sub line with multiline format
        $linesLength = \array_map(function ($subLine) {
            return Helper::width(Helper::removeDecoration($this->output->getFormatter(), \rtrim($subLine, "\r")));
        }, \explode("\n", $line));
        $linesWidth = \max($linesLength);
        $terminalWidth = $this->terminal->getWidth();
        if ($linesWidth <= $terminalWidth) {
            $phabelReturn = $line;
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $this->setBarWidth($this->barWidth - $linesWidth + $terminalWidth);
        $phabelReturn = \preg_replace_callback($regex, $callback, $this->format);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
