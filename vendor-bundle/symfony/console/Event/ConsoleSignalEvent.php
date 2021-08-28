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
 * @author marie <marie@users.noreply.github.com>
 */
final class ConsoleSignalEvent extends ConsoleEvent
{
    private $handlingSignal;
    public function __construct(Command $command, InputInterface $input, OutputInterface $output, $handlingSignal)
    {
        if (!\is_int($handlingSignal)) {
            if (!(\is_bool($handlingSignal) || \is_numeric($handlingSignal))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($handlingSignal) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($handlingSignal) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $handlingSignal = (int) $handlingSignal;
            }
        }
        parent::__construct($command, $input, $output);
        $this->handlingSignal = $handlingSignal;
    }
    public function getHandlingSignal()
    {
        $phabelReturn = $this->handlingSignal;
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
