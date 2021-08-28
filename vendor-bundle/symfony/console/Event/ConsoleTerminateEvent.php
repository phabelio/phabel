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
 * Allows to manipulate the exit code of a command after its execution.
 *
 * @author Francesco Levorato <git@flevour.net>
 */
final class ConsoleTerminateEvent extends ConsoleEvent
{
    private $exitCode;
    public function __construct(Command $command, InputInterface $input, OutputInterface $output, $exitCode)
    {
        if (!\is_int($exitCode)) {
            if (!(\is_bool($exitCode) || \is_numeric($exitCode))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($exitCode) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($exitCode) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $exitCode = (int) $exitCode;
            }
        }
        parent::__construct($command, $input, $output);
        $this->setExitCode($exitCode);
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
    }
    public function getExitCode()
    {
        $phabelReturn = $this->exitCode;
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
