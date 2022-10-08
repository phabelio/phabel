<?php

namespace Phabel\Cli;

use Phabel\Composer\EventHandler as ComposerEventHandler;
use PhabelVendor\Symfony\Component\Console\Helper\ProgressBar;
use PhabelVendor\Symfony\Component\Console\Output\ConsoleOutput;
class EventHandler extends ComposerEventHandler
{
    /**
     * Create simple CLI-based preconfigured instance.
     *
     * @return self
     */
    public static function create() : self
    {
        $output = new ConsoleOutput();
        return new self(new \Phabel\Cli\SimpleConsoleLogger($output), function (int $max) use($output) : ProgressBar {
            return new ProgressBar($output, $max, -1);
        });
    }
}
