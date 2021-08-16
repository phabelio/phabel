<?php

namespace Phabel\Commands;

use Exception;
use Phabel\Cli\EventHandler as CliEventHandler;
use Phabel\Cli\Formatter;
use Phabel\Cli\SimpleConsoleLogger;
use Phabel\EventHandler;
use Phabel\Target\Php;
use Phabel\Traverser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Run extends Command {
    protected static $defaultName = 'run';

    protected function configure(): void
    {
        $target = getenv('PHABEL_TARGET') ?: null;
        $coverage = getenv('PHABEL_COVERAGE') ?: false;

        $this
            ->setDescription('Run transpiler.')
            ->setHelp('This command transpiles the specified files or directories to the specified PHP version.')

            ->addOption('target', null, $target ? InputOption::VALUE_OPTIONAL : InputOption::VALUE_REQUIRED, 'Target PHP version', $target)
            ->addOption('coverage', null, InputOption::VALUE_OPTIONAL, 'PHP coverage path', $coverage)

            ->addArgument('input', InputArgument::REQUIRED, 'Input path')
            ->addArgument('output', InputArgument::REQUIRED, 'Output path')
        ;
    }
    

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->setFormatter(Formatter::getFormatter());
        $output->write(PHP_EOL.Formatter::BANNER.PHP_EOL.PHP_EOL);

        if (!$input->getOption('target')) {
            $output->write("<error>Missing --target parameter or PHABEL_TARGET environment variable!</error>".PHP_EOL.PHP_EOL);
            return Command::INVALID;
        }

        $packages = (new Traverser(
                new CliEventHandler(
                    new SimpleConsoleLogger($output), 
                    !\getenv('CI')
                        && !$output->isDebug() ? fn (int $max): ProgressBar => new ProgressBar($output, $max, -1) : null
                )
            ))
            ->setPlugins([
                Php::class => ['target' => Php::normalizeVersion($input->getOption('target'))]
            ])
            ->setInput($input->getArgument('input'))
            ->setOutput($input->getArgument('output'))
            ->setCoverage($input->getOption('coverage') ?: '')
            ->run();
        
        if (!empty($packages)) {
            $cmd = "composer require --dev ";
            foreach ($packages as $package => $constraint) {
                $cmd .= \escapeshellarg("$package:$constraint")." ";
            }
            $output->write("<bold>All done, OK!</bold>");
            $output->write("Please run the following command to install required development dependencies:".PHP_EOL);
            $output->write($cmd.PHP_EOL);
        }
        
        return Command::SUCCESS;
    }
}