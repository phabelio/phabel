<?php

namespace Phabel\Commands;

use Exception;
use Phabel\Cli\Formatter;
use Phabel\Plugin\ComposerSanitizer;
use Phabel\Version;
use Phabel\Symfony\Component\Console\Command\Command;
use Phabel\Symfony\Component\Console\Input\InputArgument;
use Phabel\Symfony\Component\Console\Input\InputInterface;
use Phabel\Symfony\Component\Console\Input\InputOption;
use Phabel\Symfony\Component\Console\Output\OutputInterface;
use Phabel\Symfony\Component\Process\Exception\ProcessFailedException;
use Phabel\Symfony\Component\Process\Process;
class Publish extends Command
{
    protected static $defaultName = 'publish';
    private function exec(array $command, $ignoreResult = \false)
    {
        if (!\is_bool($ignoreResult)) {
            if (!(\is_bool($ignoreResult) || \is_numeric($ignoreResult) || \is_string($ignoreResult))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($ignoreResult) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($ignoreResult) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $ignoreResult = (bool) $ignoreResult;
        }
        $proc = new Process($command);
        $proc->run();
        if (!$proc->isSuccessful() && !$ignoreResult) {
            throw new ProcessFailedException($proc);
        }
        $phabelReturn = $proc->getOutput();
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
        }
        return $phabelReturn;
    }
    private function getMessage($ref)
    {
        if (!\is_string($ref)) {
            if (!(\is_string($ref) || \is_object($ref) && \method_exists($ref, '__toString') || (\is_bool($ref) || \is_numeric($ref)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($ref) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($ref) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $ref = (string) $ref;
        }
        $phabelReturn = $this->exec(['git', 'log', '--format=%B', '-n', '1', $ref]);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
        }
        return $phabelReturn;
    }
    protected function configure()
    {
        $tags = new Process(['git', 'tag']);
        $tags->run();
        $tags = $tags->isSuccessful() ? \array_reverse(\explode("\n", \trim($tags->getOutput()))) : [];
        $tag = null;
        foreach ($tags as $tag) {
            if (!\str_contains($this->getMessage($tag), 'Release transpiled using https://phabel.io')) {
                break;
            }
        }
        $this->setDescription('Transpile a release.')->setHelp('This command transpiles the specified (or the latest) git tag.')->addOption("remote", 'r', InputOption::VALUE_OPTIONAL, 'Remote where to push tags', 'origin')->addArgument('source', $tag ? InputArgument::OPTIONAL : InputArgument::REQUIRED, 'Source tag name', $tag);
    }
    private function prepare($src, $dest, callable $cb)
    {
        if (!\is_string($src)) {
            if (!(\is_string($src) || \is_object($src) && \method_exists($src, '__toString') || (\is_bool($src) || \is_numeric($src)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($src) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($src) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $src = (string) $src;
        }
        if (!\is_string($dest)) {
            if (!(\is_string($dest) || \is_object($dest) && \method_exists($dest, '__toString') || (\is_bool($dest) || \is_numeric($dest)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($dest) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($dest) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $dest = (string) $dest;
        }
        $this->exec(['git', 'checkout', $src]);
        $message = $this->getMessage($src);
        if (!\file_exists('composer.json')) {
            throw new Exception("composer.json doesn't exist!");
        }
        $json = \json_decode(\file_get_contents('composer.json'), \true);
        $json = $cb($json);
        \file_put_contents('composer.json', \json_encode($json, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES));
        $this->exec(['git', 'commit', '-am', $message . "\nRelease transpiled using https://phabel.io, the PHP transpiler"]);
        $this->exec(['git', 'tag', '-d', $dest], \true);
        $this->exec(['git', 'tag', $dest]);
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $src = $input->getArgument('source');
        $remote = $input->getOption('remote');
        $output->setFormatter(Formatter::getFormatter());
        $branch = \trim($this->exec(["git", 'rev-parse', '--abbrev-ref', 'HEAD']));
        $stashed = \trim($this->exec(['git', 'stash'])) !== 'No local changes to save';
        $output->write("<phabel>Tagging transpiled release <bold>{$src}.9998</bold>...</phabel>" . \PHP_EOL);
        $this->prepare($src, "{$src}.9998", function (array $json) {
            $json['phabel'] = isset($json['phabel']) ? $json['phabel'] : [];
            $json['phabel']['extra'] = isset($json['phabel']['extra']) ? $json['phabel']['extra'] : [];
            $json['phabel']['extra']['require'] = $json['require'];
            $json['require'] = ['phabel/phabel' => Version::VERSION, 'php' => $json['require']['php']];
            \file_put_contents(ComposerSanitizer::FILE_NAME, ComposerSanitizer::getContents(isset($json['name']) ? $json['name'] : 'phabel'));
            $this->exec(['git', 'add', ComposerSanitizer::FILE_NAME]);
            $json['autoload'] = isset($json['autoload']) ? $json['autoload'] : [];
            $json['autoload']['files'] = isset($json['autoload']['files']) ? $json['autoload']['files'] : [];
            $json['autoload']['files'][] = ComposerSanitizer::FILE_NAME;
            $phabelReturn = $json;
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        });
        $output->write("<phabel>Tagging original release as <bold>{$src}.9999</bold>...</phabel>" . \PHP_EOL);
        $this->prepare($src, "{$src}.9999", function (array $json) {
            $phabelReturn = $json;
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        });
        $this->exec(['git', 'checkout', $branch]);
        if ($stashed) {
            $this->exec(['git', 'stash', 'pop']);
        }
        $output->write("<phabel>Pushing <bold>{$src}.9998</bold>, <bold>{$src}.9999</bold> to <bold>{$remote}</bold>...</phabel>" . \PHP_EOL);
        $this->exec(['git', 'push', $remote, "{$src}.9998", "{$src}.9999"]);
        $output->write("<phabel>Done!</phabel>\n<phabel>Tell users to require <bold>^{$src}</bold> in their <bold>composer.json</bold> to automatically load the correct transpiled version!</phabel>\n\n<bold>Tip</bold>: Add the following badge to your README to let users know about your minimum supported PHP version, as it won't be shown on packagist.\n<phabel>![phabel.io](https://phabel.io/badge/5.6)</phabel>\n");
        return Command::SUCCESS;
    }
}
