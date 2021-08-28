<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Output;

use Phabel\Symfony\Component\Console\Formatter\OutputFormatter;
use Phabel\Symfony\Component\Console\Formatter\OutputFormatterInterface;
/**
 * Base class for output classes.
 *
 * There are five levels of verbosity:
 *
 *  * normal: no option passed (normal output)
 *  * verbose: -v (more output)
 *  * very verbose: -vv (highly extended output)
 *  * debug: -vvv (all debug output)
 *  * quiet: -q (no output)
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class Output implements OutputInterface
{
    private $verbosity;
    private $formatter;
    /**
     * @param int                           $verbosity The verbosity level (one of the VERBOSITY constants in OutputInterface)
     * @param bool                          $decorated Whether to decorate messages
     * @param OutputFormatterInterface|null $formatter Output formatter instance (null to use default OutputFormatter)
     */
    public function __construct($verbosity = self::VERBOSITY_NORMAL, $decorated = \false, OutputFormatterInterface $formatter = null)
    {
        if (!\is_null($verbosity)) {
            if (!\is_int($verbosity)) {
                if (!(\is_bool($verbosity) || \is_numeric($verbosity))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($verbosity) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($verbosity) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $verbosity = (int) $verbosity;
                }
            }
        }
        if (!\is_bool($decorated)) {
            if (!(\is_bool($decorated) || \is_numeric($decorated) || \is_string($decorated))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($decorated) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($decorated) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $decorated = (bool) $decorated;
            }
        }
        $this->verbosity = null === $verbosity ? self::VERBOSITY_NORMAL : $verbosity;
        $this->formatter = isset($formatter) ? $formatter : new OutputFormatter();
        $this->formatter->setDecorated($decorated);
    }
    /**
     * {@inheritdoc}
     */
    public function setFormatter(OutputFormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }
    /**
     * {@inheritdoc}
     */
    public function getFormatter()
    {
        return $this->formatter;
    }
    /**
     * {@inheritdoc}
     */
    public function setDecorated($decorated)
    {
        if (!\is_bool($decorated)) {
            if (!(\is_bool($decorated) || \is_numeric($decorated) || \is_string($decorated))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($decorated) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($decorated) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $decorated = (bool) $decorated;
            }
        }
        $this->formatter->setDecorated($decorated);
    }
    /**
     * {@inheritdoc}
     */
    public function isDecorated()
    {
        return $this->formatter->isDecorated();
    }
    /**
     * {@inheritdoc}
     */
    public function setVerbosity($level)
    {
        if (!\is_int($level)) {
            if (!(\is_bool($level) || \is_numeric($level))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($level) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($level) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $level = (int) $level;
            }
        }
        $this->verbosity = $level;
    }
    /**
     * {@inheritdoc}
     */
    public function getVerbosity()
    {
        return $this->verbosity;
    }
    /**
     * {@inheritdoc}
     */
    public function isQuiet()
    {
        return self::VERBOSITY_QUIET === $this->verbosity;
    }
    /**
     * {@inheritdoc}
     */
    public function isVerbose()
    {
        return self::VERBOSITY_VERBOSE <= $this->verbosity;
    }
    /**
     * {@inheritdoc}
     */
    public function isVeryVerbose()
    {
        return self::VERBOSITY_VERY_VERBOSE <= $this->verbosity;
    }
    /**
     * {@inheritdoc}
     */
    public function isDebug()
    {
        return self::VERBOSITY_DEBUG <= $this->verbosity;
    }
    /**
     * {@inheritdoc}
     */
    public function writeln($messages, $options = self::OUTPUT_NORMAL)
    {
        if (!\is_int($options)) {
            if (!(\is_bool($options) || \is_numeric($options))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($options) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($options) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $options = (int) $options;
            }
        }
        $this->write($messages, \true, $options);
    }
    /**
     * {@inheritdoc}
     */
    public function write($messages, $newline = \false, $options = self::OUTPUT_NORMAL)
    {
        if (!\is_bool($newline)) {
            if (!(\is_bool($newline) || \is_numeric($newline) || \is_string($newline))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($newline) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($newline) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $newline = (bool) $newline;
            }
        }
        if (!\is_int($options)) {
            if (!(\is_bool($options) || \is_numeric($options))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($options) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($options) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $options = (int) $options;
            }
        }
        if (!\is_iterable($messages)) {
            $messages = [$messages];
        }
        $types = self::OUTPUT_NORMAL | self::OUTPUT_RAW | self::OUTPUT_PLAIN;
        $type = $types & $options ?: self::OUTPUT_NORMAL;
        $verbosities = self::VERBOSITY_QUIET | self::VERBOSITY_NORMAL | self::VERBOSITY_VERBOSE | self::VERBOSITY_VERY_VERBOSE | self::VERBOSITY_DEBUG;
        $verbosity = $verbosities & $options ?: self::VERBOSITY_NORMAL;
        if ($verbosity > $this->getVerbosity()) {
            return;
        }
        foreach ($messages as $message) {
            switch ($type) {
                case OutputInterface::OUTPUT_NORMAL:
                    $message = $this->formatter->format($message);
                    break;
                case OutputInterface::OUTPUT_RAW:
                    break;
                case OutputInterface::OUTPUT_PLAIN:
                    $message = \strip_tags($this->formatter->format($message));
                    break;
            }
            $this->doWrite(isset($message) ? $message : '', $newline);
        }
    }
    /**
     * Writes a message to the output.
     */
    protected abstract function doWrite($message, $newline);
}
