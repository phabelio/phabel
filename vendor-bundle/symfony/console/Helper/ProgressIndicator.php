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
use Phabel\Symfony\Component\Console\Exception\LogicException;
use Phabel\Symfony\Component\Console\Output\OutputInterface;
/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ProgressIndicator
{
    private $output;
    private $startTime;
    private $format;
    private $message;
    private $indicatorValues;
    private $indicatorCurrent;
    private $indicatorChangeInterval;
    private $indicatorUpdateTime;
    private $started = \false;
    private static $formatters;
    private static $formats;
    /**
     * @param int        $indicatorChangeInterval Change interval in milliseconds
     * @param array|null $indicatorValues         Animated indicator characters
     */
    public function __construct(OutputInterface $output, $format = null, $indicatorChangeInterval = 100, array $indicatorValues = null)
    {
        if (!\is_null($format)) {
            if (!\is_string($format)) {
                if (!(\is_string($format) || \is_object($format) && \method_exists($format, '__toString') || (\is_bool($format) || \is_numeric($format)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($format) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($format) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $format = (string) $format;
                }
            }
        }
        if (!\is_int($indicatorChangeInterval)) {
            if (!(\is_bool($indicatorChangeInterval) || \is_numeric($indicatorChangeInterval))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($indicatorChangeInterval) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($indicatorChangeInterval) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $indicatorChangeInterval = (int) $indicatorChangeInterval;
            }
        }
        $this->output = $output;
        if (null === $format) {
            $format = $this->determineBestFormat();
        }
        if (null === $indicatorValues) {
            $indicatorValues = ['-', '\\', '|', '/'];
        }
        $indicatorValues = \array_values($indicatorValues);
        if (2 > \count($indicatorValues)) {
            throw new InvalidArgumentException('Must have at least 2 indicator value characters.');
        }
        $this->format = self::getFormatDefinition($format);
        $this->indicatorChangeInterval = $indicatorChangeInterval;
        $this->indicatorValues = $indicatorValues;
        $this->startTime = \time();
    }
    /**
     * Sets the current indicator message.
     */
    public function setMessage($message)
    {
        if (!\is_null($message)) {
            if (!\is_string($message)) {
                if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $message = (string) $message;
                }
            }
        }
        $this->message = $message;
        $this->display();
    }
    /**
     * Starts the indicator output.
     */
    public function start($message)
    {
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        if ($this->started) {
            throw new LogicException('Progress indicator already started.');
        }
        $this->message = $message;
        $this->started = \true;
        $this->startTime = \time();
        $this->indicatorUpdateTime = $this->getCurrentTimeInMilliseconds() + $this->indicatorChangeInterval;
        $this->indicatorCurrent = 0;
        $this->display();
    }
    /**
     * Advances the indicator.
     */
    public function advance()
    {
        if (!$this->started) {
            throw new LogicException('Progress indicator has not yet been started.');
        }
        if (!$this->output->isDecorated()) {
            return;
        }
        $currentTime = $this->getCurrentTimeInMilliseconds();
        if ($currentTime < $this->indicatorUpdateTime) {
            return;
        }
        $this->indicatorUpdateTime = $currentTime + $this->indicatorChangeInterval;
        ++$this->indicatorCurrent;
        $this->display();
    }
    /**
     * Finish the indicator with message.
     *
     * @param $message
     */
    public function finish($message)
    {
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        if (!$this->started) {
            throw new LogicException('Progress indicator has not yet been started.');
        }
        $this->message = $message;
        $this->display();
        $this->output->writeln('');
        $this->started = \false;
    }
    /**
     * Gets the format for a given name.
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
        return isset(self::$formats[$name]) ? self::$formats[$name] : null;
    }
    /**
     * Sets a placeholder formatter for a given name.
     *
     * This method also allow you to override an existing placeholder.
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
     * Gets the placeholder formatter for a given name (including the delimiter char like %).
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
        return isset(self::$formatters[$name]) ? self::$formatters[$name] : null;
    }
    private function display()
    {
        if (OutputInterface::VERBOSITY_QUIET === $this->output->getVerbosity()) {
            return;
        }
        $this->overwrite(\preg_replace_callback("{%([a-z\\-_]+)(?:\\:([^%]+))?%}i", function ($matches) {
            if ($formatter = self::getPlaceholderFormatterDefinition($matches[1])) {
                return $formatter($this);
            }
            return $matches[0];
        }, isset($this->format) ? $this->format : ''));
    }
    private function determineBestFormat()
    {
        switch ($this->output->getVerbosity()) {
            // OutputInterface::VERBOSITY_QUIET: display is disabled anyway
            case OutputInterface::VERBOSITY_VERBOSE:
                $phabelReturn = $this->output->isDecorated() ? 'verbose' : 'verbose_no_ansi';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            case OutputInterface::VERBOSITY_VERY_VERBOSE:
            case OutputInterface::VERBOSITY_DEBUG:
                $phabelReturn = $this->output->isDecorated() ? 'very_verbose' : 'very_verbose_no_ansi';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            default:
                $phabelReturn = $this->output->isDecorated() ? 'normal' : 'normal_no_ansi';
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
        if ($this->output->isDecorated()) {
            $this->output->write("\r\x1b[2K");
            $this->output->write($message);
        } else {
            $this->output->writeln($message);
        }
    }
    private function getCurrentTimeInMilliseconds()
    {
        $phabelReturn = \round(\microtime(\true) * 1000);
        if (!\is_float($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (double) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    private static function initPlaceholderFormatters()
    {
        $phabelReturn = ['indicator' => function (self $indicator) {
            return $indicator->indicatorValues[$indicator->indicatorCurrent % \count($indicator->indicatorValues)];
        }, 'message' => function (self $indicator) {
            return $indicator->message;
        }, 'elapsed' => function (self $indicator) {
            return Helper::formatTime(\time() - $indicator->startTime);
        }, 'memory' => function () {
            return Helper::formatMemory(\memory_get_usage(\true));
        }];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private static function initFormats()
    {
        $phabelReturn = ['normal' => ' %indicator% %message%', 'normal_no_ansi' => ' %message%', 'verbose' => ' %indicator% %message% (%elapsed:6s%)', 'verbose_no_ansi' => ' %message% (%elapsed:6s%)', 'very_verbose' => ' %indicator% %message% (%elapsed:6s%, %memory:6s%)', 'very_verbose_no_ansi' => ' %message% (%elapsed:6s%, %memory:6s%)'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
