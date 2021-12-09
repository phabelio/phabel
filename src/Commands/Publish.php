<?php

namespace Phabel\Commands;

use Exception;
use Phabel\Cli\Formatter;
use Phabel\Plugin\ComposerSanitizer;
use Phabel\Version;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class Publish extends BaseCommand
{
    private const PLATFORM_PACKAGE = '{^(?:php(?:-64bit|-ipv6|-zts|-debug)?|hhvm|(?:ext|lib)-[a-z0-9](?:[_.-]?[a-z0-9]+)*|composer-(?:plugin|runtime)-api)$}iD';

    protected static $defaultName = 'publish';

    private function getMessage(string $ref): string
    {
        return $this->exec(['git', 'log', '--format=%B', '-n', '1', $ref]);
    }

    protected function configure(): void
    {
        $tags = new Process(['git', 'tag', '--sort=-creatordate']);
        $tags->run();
        $tags = $tags->isSuccessful() ? \explode("\n", \trim($tags->getOutput())) : [];
        $tag = null;
        foreach ($tags as $tag) {
            if (!\str_contains($this->getMessage($tag), 'Release transpiled using https://phabel.io')) {
                break;
            }
        }

        $this
            ->setDescription('Transpile a release.')
            ->setHelp('This command transpiles the specified (or the latest) git tag.')

            ->addOption("remote", 'r', InputOption::VALUE_OPTIONAL, 'Remote where to push tags', 'origin')
            ->addOption('dry', 'd', InputOption::VALUE_NEGATABLE, "Whether to skip pushing tags to any remote", false)
            ->addArgument('source', $tag ? InputArgument::OPTIONAL : InputArgument::REQUIRED, 'Source tag name', $tag);
    }

    private function prepare(string $src, string $dest, ?callable $cb = null): void
    {
        $this->exec(['git', 'checkout', $src]);
        $message = $this->getMessage($src);

        if (!\file_exists('composer.json')) {
            throw new Exception("composer.json doesn't exist!");
        }

        if ($cb) {
            $json = \json_decode(\file_get_contents('composer.json'), true);
            $json = $cb($json);
            \file_put_contents('composer.json', \json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
            $this->exec(['git', 'commit', '-am', $message."\nRelease transpiled using https://phabel.io, the PHP transpiler"], true);
        }

        $this->exec(['git', 'tag', '-d', $dest], true);
        $this->exec(['git', 'tag', $dest]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $src = $input->getArgument('source');
        $remote = $input->getOption('remote');
        $output->setFormatter(Formatter::getFormatter());

        \trim($this->exec(['git', 'stash']));
        $branch = \trim($this->exec(["git", 'rev-parse', '--abbrev-ref', 'HEAD']));
        $output->write("<phabel>Tagging transpiled release <bold>$src.9998</bold>...</phabel>".PHP_EOL);
        $this->prepare($src, "$src.9998", function (array $json): array {
            $version = 'php';
            unset($json['require']['php']);
            if (isset($json['require']['php-64bit'])) {
                unset($json['require']['php-64bit']);
                $version = 'php-64bit';
            }

            $requires = \array_filter($json['require'], fn (string $f) => !\preg_match(self::PLATFORM_PACKAGE, $f), ARRAY_FILTER_USE_KEY);
            $json['extra'] ??= [];
            $json['extra']['phabel'] ??= [];
            $json['extra']['phabel']['require'] = $requires;
            $json['require'] = \array_merge(
                ['phabel/phabel' => Version::VERSION, $version => '*'],
                \array_diff_key($json['require'], $requires)
            );
            \file_put_contents(ComposerSanitizer::FILE_NAME, ComposerSanitizer::getContents($json['name'] ?? 'phabel'));
            $this->exec(['git', 'add', ComposerSanitizer::FILE_NAME]);
            $json['autoload'] ??= [];
            $json['autoload']['files'] ??= [];
            $json['autoload']['files'] []= ComposerSanitizer::FILE_NAME;
            return $json;
        });

        $output->write("<phabel>Tagging original release as <bold>$src.9999</bold>...</phabel>".PHP_EOL);
        $this->prepare($src, "$src.9999");

        $this->exec(['git', 'checkout', $branch]);
        $this->exec(['git', 'stash', 'pop'], true);

        if (!$input->getOption('dry')) {
            $output->write("<phabel>Pushing <bold>$src.9998</bold>, <bold>$src.9999</bold> to <bold>$remote</bold>...</phabel>".PHP_EOL);
            $this->exec(['git', 'push', $remote, "$src.9998", "$src.9999"]);
        }

        $output->write("<phabel>Done!</phabel>
<phabel>Tell users to require <bold>^$src</bold> in their <bold>composer.json</bold> to automatically load the correct transpiled version!</phabel>

<bold>Tip</bold>: Add the following badge to your README to let users know about your minimum supported PHP version, as it won't be shown on packagist.
<phabel>[![phabel.io](https://phabel.io/badge)](https://phabel.io)</phabel>
");

        return Command::SUCCESS;
    }
}
