<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhabelVendor\Symfony\Component\Console\Output;

use PhabelVendor\Symfony\Component\Console\Exception\InvalidArgumentException;
use PhabelVendor\Symfony\Component\Console\Formatter\OutputFormatterInterface;
/**
 * A BufferedOutput that keeps only the last N chars.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class TrimmedBufferOutput extends Output
{
    private $maxLength;
    private $buffer = '';
    /**
     *
     * @param (int | null) $verbosity
     */
    public function __construct(int $maxLength, $verbosity = self::VERBOSITY_NORMAL, bool $decorated = \false, OutputFormatterInterface $formatter = NULL)
    {
        if (!(\is_int($verbosity) || \is_null($verbosity))) {
            if (!(\is_bool($verbosity) || \is_numeric($verbosity))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($verbosity) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($verbosity) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $verbosity = (int) $verbosity;
            }
        }
        if ($maxLength <= 0) {
            throw new InvalidArgumentException(\sprintf('"%s()" expects a strictly positive maxLength. Got %d.', __METHOD__, $maxLength));
        }
        parent::__construct($verbosity, $decorated, $formatter);
        $this->maxLength = $maxLength;
    }
    /**
     * Empties buffer and returns its content.
     *
     * @return string
     */
    public function fetch()
    {
        $content = $this->buffer;
        $this->buffer = '';
        return $content;
    }
    /**
     * {@inheritdoc}
     */
    protected function doWrite(string $message, bool $newline)
    {
        $this->buffer .= $message;
        if ($newline) {
            $this->buffer .= \PHP_EOL;
        }
        $this->buffer = \Phabel\Target\Php80\Polyfill::substr($this->buffer, 0 - $this->maxLength);
    }
}
