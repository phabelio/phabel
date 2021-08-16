<?php

namespace Phabel\Commands;

use Exception;
use Phabel\Cli\Formatter;
use Phabel\Version;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Tag extends Command {
    protected static $defaultName = 'tag';

    protected function configure(): void
    {
        $tags = new Process(['git', 'describe', '--tags', '--abbrev=0']);
        $tags->run();
        $tag = $tags->isSuccessful() ? $tags->getOutput() : null;

        $this
            ->setDescription('Transpile a release.')
            ->setHelp('This command transpiles the specified (or the latest) git tag.')

            ->addArgument('source', $tag ? InputArgument::OPTIONAL : InputArgument::REQUIRED, 'Source tag name', $tag)
            ->addArgument('destination', $tag ? InputArgument::OPTIONAL : InputArgument::REQUIRED, 'Destination tag name', $tag ? "$tag.99" : null)
        ;
    }
    

    private function exec(array $command): string
    {
        $proc = new Process($command);
        $proc->run();
        if (!$proc->isSuccessful()) {
            throw new ProcessFailedException($proc);
        }
        return $proc->getOutput();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $src = $input->getArgument('source');
        $dest = $input->getArgument('destination');
        $output->setFormatter(Formatter::getFormatter());
        
        $branch = trim($this->exec(["git", 'rev-parse', '--abbrev-ref', 'HEAD']));
        $stashed = trim($this->exec(['git', 'stash'])) !== 'No local changes to save';
        $this->exec(['git', 'checkout', $src]);

        if (!file_exists('composer.json')) {
            throw new Exception("composer.json doesn't exist!");
        }

        $json = json_decode(file_get_contents('composer.json'), true);
        $json['phabel'] ??= [];
        $json['phabel']['extra'] ??= [];
        $json['phabel']['extra']['require'] = $json['require'];
        $json['require'] = [
            'phabel/phabel' => Version::VERSION,
            'php' => '>=7.0'
        ];
        file_put_contents('composer.json', json_encode($json));

        $this->exec(['git', 'commit', '-am', 'phabel.io release']);
        $this->exec(['git', 'tag', $dest]);
        $this->exec(['git', 'push', $dest]);
        $this->exec(['git', 'checkout', $branch]);

        if ($stashed) {
            $this->exec(['git', 'stash', 'pop']);
        }

        $output->write("<phabel>OK, pushed the transpiled $dest tag!</phabel>");

        return Command::SUCCESS;
    }
}