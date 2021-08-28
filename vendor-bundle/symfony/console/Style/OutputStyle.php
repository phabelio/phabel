<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Style;

use Phabel\Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Phabel\Symfony\Component\Console\Helper\ProgressBar;
use Phabel\Symfony\Component\Console\Output\ConsoleOutputInterface;
use Phabel\Symfony\Component\Console\Output\OutputInterface;
/**
 * Decorates output to add console style guide helpers.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class OutputStyle implements OutputInterface, StyleInterface
{
    private $output;
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }
    /**
     * {@inheritdoc}
     */
    public function newLine($count = 1)
    {
        if (!\is_int($count)) {
            if (!(\is_bool($count) || \is_numeric($count))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($count) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($count) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $count = (int) $count;
            }
        }
        $this->output->write(\str_repeat(\PHP_EOL, $count));
    }
    /**
     * @return ProgressBar
     */
    public function createProgressBar($max = 0)
    {
        if (!\is_int($max)) {
            if (!(\is_bool($max) || \is_numeric($max))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($max) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($max) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $max = (int) $max;
            }
        }
        return new ProgressBar($this->output, $max);
    }
    /**
     * {@inheritdoc}
     */
    public function write($messages, $newline = \false, $type = self::OUTPUT_NORMAL)
    {
        if (!\is_bool($newline)) {
            if (!(\is_bool($newline) || \is_numeric($newline) || \is_string($newline))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($newline) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($newline) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $newline = (bool) $newline;
            }
        }
        if (!\is_int($type)) {
            if (!(\is_bool($type) || \is_numeric($type))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($type) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $type = (int) $type;
            }
        }
        $this->output->write($messages, $newline, $type);
    }
    /**
     * {@inheritdoc}
     */
    public function writeln($messages, $type = self::OUTPUT_NORMAL)
    {
        if (!\is_int($type)) {
            if (!(\is_bool($type) || \is_numeric($type))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($type) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $type = (int) $type;
            }
        }
        $this->output->writeln($messages, $type);
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
        $this->output->setVerbosity($level);
    }
    /**
     * {@inheritdoc}
     */
    public function getVerbosity()
    {
        return $this->output->getVerbosity();
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
        $this->output->setDecorated($decorated);
    }
    /**
     * {@inheritdoc}
     */
    public function isDecorated()
    {
        return $this->output->isDecorated();
    }
    /**
     * {@inheritdoc}
     */
    public function setFormatter(OutputFormatterInterface $formatter)
    {
        $this->output->setFormatter($formatter);
    }
    /**
     * {@inheritdoc}
     */
    public function getFormatter()
    {
        return $this->output->getFormatter();
    }
    /**
     * {@inheritdoc}
     */
    public function isQuiet()
    {
        return $this->output->isQuiet();
    }
    /**
     * {@inheritdoc}
     */
    public function isVerbose()
    {
        return $this->output->isVerbose();
    }
    /**
     * {@inheritdoc}
     */
    public function isVeryVerbose()
    {
        return $this->output->isVeryVerbose();
    }
    /**
     * {@inheritdoc}
     */
    public function isDebug()
    {
        return $this->output->isDebug();
    }
    protected function getErrorOutput()
    {
        if (!$this->output instanceof ConsoleOutputInterface) {
            return $this->output;
        }
        return $this->output->getErrorOutput();
    }
}
