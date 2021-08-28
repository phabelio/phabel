<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Helper;

use Phabel\Symfony\Component\Console\Output\ConsoleOutputInterface;
use Phabel\Symfony\Component\Console\Output\OutputInterface;
use Phabel\Symfony\Component\Process\Exception\ProcessFailedException;
use Phabel\Symfony\Component\Process\Process;
/**
 * The ProcessHelper class provides helpers to run external processes.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @final
 */
class ProcessHelper extends Helper
{
    /**
     * Runs an external process.
     *
     * @param array|Process $cmd      An instance of Process or an array of the command and arguments
     * @param callable|null $callback A PHP callback to run whenever there is some
     *                                output available on STDOUT or STDERR
     *
     * @return Process The process that ran
     */
    public function run(OutputInterface $output, $cmd, $error = null, callable $callback = null, $verbosity = OutputInterface::VERBOSITY_VERY_VERBOSE)
    {
        if (!\is_null($error)) {
            if (!\is_string($error)) {
                if (!(\is_string($error) || \is_object($error) && \method_exists($error, '__toString') || (\is_bool($error) || \is_numeric($error)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($error) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($error) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $error = (string) $error;
                }
            }
        }
        if (!\is_int($verbosity)) {
            if (!(\is_bool($verbosity) || \is_numeric($verbosity))) {
                throw new \TypeError(__METHOD__ . '(): Argument #5 ($verbosity) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($verbosity) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $verbosity = (int) $verbosity;
            }
        }
        if (!\class_exists(Process::class)) {
            throw new \LogicException('The ProcessHelper cannot be run as the Process component is not installed. Try running "compose require symfony/process".');
        }
        if ($output instanceof ConsoleOutputInterface) {
            $output = $output->getErrorOutput();
        }
        $formatter = $this->getHelperSet()->get('debug_formatter');
        if ($cmd instanceof Process) {
            $cmd = [$cmd];
        }
        if (!\is_array($cmd)) {
            throw new \TypeError(\sprintf('The "command" argument of "%s()" must be an array or a "%s" instance, "%s" given.', __METHOD__, Process::class, \get_debug_type($cmd)));
        }
        if (\is_string(isset($cmd[0]) ? $cmd[0] : null)) {
            $process = new Process($cmd);
            $cmd = [];
        } elseif ((isset($cmd[0]) ? $cmd[0] : null) instanceof Process) {
            $process = $cmd[0];
            unset($cmd[0]);
        } else {
            throw new \InvalidArgumentException(\sprintf('Invalid command provided to "%s()": the command should be an array whose first element is either the path to the binary to run or a "Process" object.', __METHOD__));
        }
        if ($verbosity <= $output->getVerbosity()) {
            $output->write($formatter->start(\spl_object_hash($process), $this->escapeString($process->getCommandLine())));
        }
        if ($output->isDebug()) {
            $callback = $this->wrapCallback($output, $process, $callback);
        }
        $process->run($callback, $cmd);
        if ($verbosity <= $output->getVerbosity()) {
            $message = $process->isSuccessful() ? 'Command ran successfully' : \sprintf('%s Command did not run successfully', $process->getExitCode());
            $output->write($formatter->stop(\spl_object_hash($process), $message, $process->isSuccessful()));
        }
        if (!$process->isSuccessful() && null !== $error) {
            $output->writeln(\sprintf('<error>%s</error>', $this->escapeString($error)));
        }
        $phabelReturn = $process;
        if (!$phabelReturn instanceof Process) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Process, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Runs the process.
     *
     * This is identical to run() except that an exception is thrown if the process
     * exits with a non-zero exit code.
     *
     * @param string|Process $cmd      An instance of Process or a command to run
     * @param callable|null  $callback A PHP callback to run whenever there is some
     *                                 output available on STDOUT or STDERR
     *
     * @return Process The process that ran
     *
     * @throws ProcessFailedException
     *
     * @see run()
     */
    public function mustRun(OutputInterface $output, $cmd, $error = null, callable $callback = null)
    {
        if (!\is_null($error)) {
            if (!\is_string($error)) {
                if (!(\is_string($error) || \is_object($error) && \method_exists($error, '__toString') || (\is_bool($error) || \is_numeric($error)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($error) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($error) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $error = (string) $error;
                }
            }
        }
        $process = $this->run($output, $cmd, $error, $callback);
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $phabelReturn = $process;
        if (!$phabelReturn instanceof Process) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Process, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Wraps a Process callback to add debugging output.
     */
    public function wrapCallback(OutputInterface $output, Process $process, callable $callback = null)
    {
        if ($output instanceof ConsoleOutputInterface) {
            $output = $output->getErrorOutput();
        }
        $formatter = $this->getHelperSet()->get('debug_formatter');
        $phabelReturn = function ($type, $buffer) use($output, $process, $callback, $formatter) {
            $output->write($formatter->progress(\spl_object_hash($process), $this->escapeString($buffer), Process::ERR === $type));
            if (null !== $callback) {
                $callback($type, $buffer);
            }
        };
        if (!\is_callable($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function escapeString($str)
    {
        if (!\is_string($str)) {
            if (!(\is_string($str) || \is_object($str) && \method_exists($str, '__toString') || (\is_bool($str) || \is_numeric($str)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($str) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($str) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $str = (string) $str;
            }
        }
        $phabelReturn = \str_replace('<', '\\<', $str);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $phabelReturn = 'process';
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
