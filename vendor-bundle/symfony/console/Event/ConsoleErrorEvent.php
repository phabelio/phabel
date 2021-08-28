<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Event;

use Phabel\Symfony\Component\Console\Command\Command;
use Phabel\Symfony\Component\Console\Input\InputInterface;
use Phabel\Symfony\Component\Console\Output\OutputInterface;
/**
 * Allows to handle throwables thrown while running a command.
 *
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
final class ConsoleErrorEvent extends ConsoleEvent
{
    private $error;
    private $exitCode;
    public function __construct(InputInterface $input, OutputInterface $output, \Throwable $error, Command $command = null)
    {
        parent::__construct($command, $input, $output);
        $this->error = $error;
    }
    public function getError()
    {
        $phabelReturn = $this->error;
        if (!($phabelReturn instanceof \Exception || $phabelReturn instanceof \Error)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Throwable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function setError(\Throwable $error)
    {
        $this->error = $error;
    }
    public function setExitCode($exitCode)
    {
        if (!\is_int($exitCode)) {
            if (!(\is_bool($exitCode) || \is_numeric($exitCode))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($exitCode) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($exitCode) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $exitCode = (int) $exitCode;
            }
        }
        $this->exitCode = $exitCode;
        $r = new \ReflectionProperty($this->error, 'code');
        $r->setAccessible(\true);
        $r->setValue($this->error, $this->exitCode);
    }
    public function getExitCode()
    {
        $phabelReturn = isset($this->exitCode) ? $this->exitCode : (\is_int($this->error->getCode()) && 0 !== $this->error->getCode() ? $this->error->getCode() : 1);
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
