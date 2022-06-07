<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Cli;

use PhabelVendor\Psr\Log\AbstractLogger;
use PhabelVendor\Psr\Log\InvalidArgumentException;
use PhabelVendor\Psr\Log\LogLevel;
use PhabelVendor\Symfony\Component\Console\Output\ConsoleOutputInterface;
use PhabelVendor\Symfony\Component\Console\Output\OutputInterface;
/**
 * PSR-3 compliant console logger.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 *
 * @see https://www.php-fig.org/psr/psr-3/
 */
class SimpleConsoleLogger extends AbstractLogger
{
    public const INFO = 'info';
    public const ERROR = 'error';
    private $verbosityLevelMap = [LogLevel::EMERGENCY => OutputInterface::VERBOSITY_NORMAL, LogLevel::ALERT => OutputInterface::VERBOSITY_NORMAL, LogLevel::CRITICAL => OutputInterface::VERBOSITY_NORMAL, LogLevel::ERROR => OutputInterface::VERBOSITY_NORMAL, LogLevel::WARNING => OutputInterface::VERBOSITY_NORMAL, LogLevel::NOTICE => OutputInterface::VERBOSITY_VERBOSE, LogLevel::INFO => OutputInterface::VERBOSITY_VERY_VERBOSE, LogLevel::DEBUG => OutputInterface::VERBOSITY_DEBUG];
    private $formatLevelMap = [LogLevel::EMERGENCY => self::ERROR, LogLevel::ALERT => self::ERROR, LogLevel::CRITICAL => self::ERROR, LogLevel::ERROR => self::ERROR, LogLevel::WARNING => self::INFO, LogLevel::NOTICE => self::INFO, LogLevel::INFO => self::INFO, LogLevel::DEBUG => self::INFO];
    private $errored = \false;
    public function __construct(private $output, array $verbosityLevelMap = [], array $formatLevelMap = [])
    {
        $this->verbosityLevelMap = $verbosityLevelMap + $this->verbosityLevelMap;
        $this->formatLevelMap = $formatLevelMap + $this->formatLevelMap;
    }
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        if (!isset($this->verbosityLevelMap[$level])) {
            throw new InvalidArgumentException(\sprintf('The log level "%s" does not exist.', $level));
        }
        $output = $this->output;
        // Write to the error output if necessary and available
        if (self::ERROR === $this->formatLevelMap[$level]) {
            if ($this->output instanceof ConsoleOutputInterface) {
                $output = $output->getErrorOutput();
            }
            $this->errored = \true;
        }
        // the if condition check isn't necessary -- it's the same one that $output will do internally anyway.
        // We only do it for efficiency here as the message formatting is relatively expensive.
        if ($output->getVerbosity() >= $this->verbosityLevelMap[$level]) {
            $output->writeln(\sprintf('<%1$s>%3$s</%1$s>', $this->formatLevelMap[$level], $level, $this->interpolate($message, $context)), $this->verbosityLevelMap[$level]);
        }
    }
    /**
     * Returns true when any messages have been logged at error levels.
     *
     * @return bool
     */
    public function hasErrored()
    {
        return $this->errored;
    }
    /**
     * Interpolates context values into the message placeholders.
     *
     * @author PHP Framework Interoperability Group
     */
    private function interpolate(string $message, array $context) : string
    {
        if (!\str_contains($message, '{')) {
            return $message;
        }
        $replacements = [];
        foreach ($context as $key => $val) {
            if (null === $val || \is_scalar($val) || \is_object($val) && \method_exists($val, '__toString')) {
                $replacements["{{$key}}"] = $val;
            } elseif ($val instanceof \DateTimeInterface) {
                $replacements["{{$key}}"] = $val->format(\DateTime::RFC3339);
            } elseif (\is_object($val)) {
                $replacements["{{$key}}"] = '[object ' . \get_class($val) . ']';
            } else {
                $replacements["{{$key}}"] = '[' . \gettype($val) . ']';
            }
        }
        return \strtr($message, $replacements);
    }
}
