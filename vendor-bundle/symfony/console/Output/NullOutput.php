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

use Phabel\Symfony\Component\Console\Formatter\NullOutputFormatter;
use Phabel\Symfony\Component\Console\Formatter\OutputFormatterInterface;
/**
 * NullOutput suppresses all output.
 *
 *     $output = new NullOutput();
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Tobias Schultze <http://tobion.de>
 */
class NullOutput implements OutputInterface
{
    private $formatter;
    /**
     * {@inheritdoc}
     */
    public function setFormatter(OutputFormatterInterface $formatter)
    {
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function getFormatter()
    {
        if ($this->formatter) {
            return $this->formatter;
        }
        // to comply with the interface we must return a OutputFormatterInterface
        return $this->formatter = new NullOutputFormatter();
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
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function isDecorated()
    {
        return \false;
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
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function getVerbosity()
    {
        return self::VERBOSITY_QUIET;
    }
    /**
     * {@inheritdoc}
     */
    public function isQuiet()
    {
        return \true;
    }
    /**
     * {@inheritdoc}
     */
    public function isVerbose()
    {
        return \false;
    }
    /**
     * {@inheritdoc}
     */
    public function isVeryVerbose()
    {
        return \false;
    }
    /**
     * {@inheritdoc}
     */
    public function isDebug()
    {
        return \false;
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
        // do nothing
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
        // do nothing
    }
}
