<?php

namespace Phabel\Cli;

use Phabel\Composer\EventHandler as ComposerEventHandler;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class EventHandler extends ComposerEventHandler
{
    /**
     * Create simple CLI-based preconfigured instance.
     *
     * @return self
     */
    public static function create(): self
    {
        $output = new ConsoleOutput();
        return new self(new SimpleConsoleLogger($output), fn (int $max): ProgressBar => (new ProgressBar($output, $max, -1)));
    }
}
