<?php

namespace Phabel\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

abstract class BaseCommand extends Command
{
    protected function exec(array $command, bool $ignoreResult = false): string
    {
        $proc = new Process($command);
        $proc->run();
        if (!$proc->isSuccessful() && !$ignoreResult) {
            throw new ProcessFailedException($proc);
        }
        return $proc->getOutput();
    }
}
