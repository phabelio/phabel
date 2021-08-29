<?php

namespace Phabel\Commands;

use Phabel\Cli\EventHandler as CliEventHandler;
use Phabel\Cli\Formatter;
use Phabel\Cli\SimpleConsoleLogger;
use Phabel\Target\Php;
use Phabel\Traverser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Run extends Command
{
    protected static $defaultName = 'run';
    protected function configure()
    {
        $target = \getenv('PHABEL_TARGET') ?: null;
        $coverage = \getenv('PHABEL_COVERAGE') ?: false;
        $parallel = \getenv('PHABEL_PARALLEL') ?: 1;
        $this->setDescription('Run transpiler.')->setHelp('This command transpiles the specified files or directories to the specified PHP version.')->addOption('target', null, $target ? InputOption::VALUE_OPTIONAL : InputOption::VALUE_REQUIRED, 'Target PHP version', $target)->addOption('coverage', null, InputOption::VALUE_OPTIONAL, 'PHP coverage path', $coverage)->addOption('parallel', 'j', InputOption::VALUE_OPTIONAL, 'Number of threads to use for transpilation', $parallel)->addArgument('input', InputArgument::REQUIRED, 'Input path')->addArgument('output', InputArgument::REQUIRED, 'Output path');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->setFormatter(Formatter::getFormatter());
        $output->write(PHP_EOL . Formatter::BANNER . PHP_EOL . PHP_EOL);
        if (!$input->getOption('target')) {
            $output->write("<error>Missing --target parameter or PHABEL_TARGET environment variable!</error>" . PHP_EOL . PHP_EOL);
            return Command::INVALID;
        }
        $packages = (new Traverser(new CliEventHandler(new SimpleConsoleLogger($output), !\getenv('CI') && !$output->isDebug() ? function (int $max) use ($output): ProgressBar {
            return new ProgressBar($output, $max, -1);
        } : null)))->setPlugins([Php::class => ['target' => Php::normalizeVersion($input->getOption('target'))]])->setInput($input->getArgument('input'))->setOutput($input->getArgument('output'))->setCoverage($input->getOption('coverage') ?: '')->run($input->getOption('parallel'));
        if (!empty($packages)) {
            $cmd = "composer require --dev ";
            foreach ($packages as $package => $constraint) {
                if ($package === 'php') {
                    continue;
                }
                $cmd .= \escapeshellarg("{$package}:{$constraint}") . " ";
            }
            $output->write("Please run the following command to install required development dependencies:" . PHP_EOL . PHP_EOL);
            $output->write("<bold>{$cmd}</bold>" . PHP_EOL . PHP_EOL);
        }
        return Command::SUCCESS;
    }
}
