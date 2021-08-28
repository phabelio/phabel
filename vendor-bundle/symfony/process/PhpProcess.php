<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Process;

use Phabel\Symfony\Component\Process\Exception\LogicException;
use Phabel\Symfony\Component\Process\Exception\RuntimeException;
/**
 * PhpProcess runs a PHP script in an independent process.
 *
 *     $p = new PhpProcess('<?php echo "foo"; ?>');
 *     $p->run();
 *     print $p->getOutput()."\n";
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class PhpProcess extends Process
{
    /**
     * @param string      $script  The PHP script to run (as a string)
     * @param string|null $cwd     The working directory or null to use the working dir of the current PHP process
     * @param array|null  $env     The environment variables or null to use the same environment as the current PHP process
     * @param int         $timeout The timeout in seconds
     * @param array|null  $php     Path to the PHP binary to use with any additional arguments
     */
    public function __construct($script, $cwd = null, array $env = null, $timeout = 60, $php = null)
    {
        if (!(\is_array($php) || \is_null($php))) {
            throw new \TypeError(__METHOD__ . '(): Argument #5 ($php) must be of type ?array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($php) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($script)) {
            if (!(\is_string($script) || \is_object($script) && \method_exists($script, '__toString') || (\is_bool($script) || \is_numeric($script)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($script) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($script) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $script = (string) $script;
            }
        }
        if (!\is_null($cwd)) {
            if (!\is_string($cwd)) {
                if (!(\is_string($cwd) || \is_object($cwd) && \method_exists($cwd, '__toString') || (\is_bool($cwd) || \is_numeric($cwd)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($cwd) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($cwd) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $cwd = (string) $cwd;
                }
            }
        }
        if (!\is_int($timeout)) {
            if (!(\is_bool($timeout) || \is_numeric($timeout))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($timeout) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($timeout) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $timeout = (int) $timeout;
            }
        }
        if (null === $php) {
            $executableFinder = new PhpExecutableFinder();
            $php = $executableFinder->find(\false);
            $php = \false === $php ? null : \array_merge([$php], $executableFinder->findArguments());
        }
        if ('phpdbg' === \PHP_SAPI) {
            $file = \tempnam(\sys_get_temp_dir(), 'dbg');
            \file_put_contents($file, $script);
            \register_shutdown_function('unlink', $file);
            $php[] = $file;
            $script = null;
        }
        parent::__construct($php, $cwd, $env, $script, $timeout);
    }
    /**
     * {@inheritdoc}
     */
    public static function fromShellCommandline($command, $cwd = null, array $env = null, $input = null, $timeout = 60)
    {
        if (!\is_string($command)) {
            if (!(\is_string($command) || \is_object($command) && \method_exists($command, '__toString') || (\is_bool($command) || \is_numeric($command)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($command) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($command) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $command = (string) $command;
            }
        }
        if (!\is_null($cwd)) {
            if (!\is_string($cwd)) {
                if (!(\is_string($cwd) || \is_object($cwd) && \method_exists($cwd, '__toString') || (\is_bool($cwd) || \is_numeric($cwd)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($cwd) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($cwd) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $cwd = (string) $cwd;
                }
            }
        }
        if (!\is_null($timeout)) {
            if (!\is_float($timeout)) {
                if (!(\is_bool($timeout) || \is_numeric($timeout))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #5 ($timeout) must be of type ?float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($timeout) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $timeout = (double) $timeout;
                }
            }
        }
        throw new LogicException(\sprintf('The "%s()" method cannot be called when using "%s".', __METHOD__, self::class));
    }
    /**
     * {@inheritdoc}
     */
    public function start(callable $callback = null, array $env = [])
    {
        if (null === $this->getCommandLine()) {
            throw new RuntimeException('Unable to find the PHP executable.');
        }
        parent::start($callback, $env);
    }
}
