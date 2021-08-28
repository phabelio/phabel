<?php

namespace Phabel\Amp\Process;

use Phabel\Amp\Loop;
use Phabel\Amp\Process\Internal\Posix\Runner as PosixProcessRunner;
use Phabel\Amp\Process\Internal\ProcessHandle;
use Phabel\Amp\Process\Internal\ProcessRunner;
use Phabel\Amp\Process\Internal\ProcessStatus;
use Phabel\Amp\Process\Internal\Windows\Runner as WindowsProcessRunner;
use Phabel\Amp\Promise;
use function Phabel\Amp\call;
final class Process
{
    /** @var ProcessRunner */
    private $processRunner;
    /** @var string */
    private $command;
    /** @var string */
    private $cwd = "";
    /** @var array */
    private $env = [];
    /** @var array */
    private $options;
    /** @var ProcessHandle */
    private $handle;
    /** @var int|null */
    private $pid;
    /**
     * @param   string|string[] $command Command to run.
     * @param   string|null     $cwd Working directory or use an empty string to use the working directory of the
     *     parent.
     * @param   mixed[]         $env Environment variables or use an empty array to inherit from the parent.
     * @param   mixed[]         $options Options for `proc_open()`.
     *
     * @throws \Error If the arguments are invalid.
     */
    public function __construct($command, $cwd = null, array $env = [], array $options = [])
    {
        if (!\is_null($cwd)) {
            if (!\is_string($cwd)) {
                if (!(\is_string($cwd) || \is_object($cwd) && \method_exists($cwd, '__toString') || (\is_bool($cwd) || \is_numeric($cwd)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($cwd) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($cwd) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $cwd = (string) $cwd;
                }
            }
        }
        $command = \is_array($command) ? \implode(" ", \array_map(__NAMESPACE__ . "\\escapeArguments", $command)) : (string) $command;
        $cwd = isset($cwd) ? $cwd : "";
        $envVars = [];
        foreach ($env as $key => $value) {
            if (\is_array($value)) {
                throw new \Error("\$env cannot accept array values");
            }
            $envVars[(string) $key] = (string) $value;
        }
        $this->command = $command;
        $this->cwd = $cwd;
        $this->env = $envVars;
        $this->options = $options;
        $this->processRunner = Loop::getState(self::class);
        if ($this->processRunner === null) {
            $this->processRunner = IS_WINDOWS ? new WindowsProcessRunner() : new PosixProcessRunner();
            Loop::setState(self::class, $this->processRunner);
        }
    }
    /**
     * Stops the process if it is still running.
     */
    public function __destruct()
    {
        if ($this->handle !== null) {
            $this->processRunner->destroy($this->handle);
        }
    }
    public function __clone()
    {
        throw new \Error("Cloning is not allowed!");
    }
    /**
     * Start the process.
     *
     * @return Promise<int> Resolves with the PID.
     *
     * @throws StatusError If the process has already been started.
     */
    public function start()
    {
        if ($this->handle) {
            throw new StatusError("Process has already been started.");
        }
        $phabelReturn = call(function () {
            $this->handle = $this->processRunner->start($this->command, $this->cwd, $this->env, $this->options);
            return $this->pid = (yield $this->handle->pidDeferred->promise());
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Wait for the process to end.
     *
     * @return Promise <int> Succeeds with process exit code or fails with a ProcessException if the process is killed.
     *
     * @throws StatusError If the process has already been started.
     */
    public function join()
    {
        if (!$this->handle) {
            throw new StatusError("Process has not been started.");
        }
        $phabelReturn = $this->processRunner->join($this->handle);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Forcibly end the process.
     *
     * @throws StatusError If the process is not running.
     * @throws ProcessException If terminating the process fails.
     */
    public function kill()
    {
        if (!$this->isRunning()) {
            throw new StatusError("Process is not running.");
        }
        $this->processRunner->kill($this->handle);
    }
    /**
     * Send a signal signal to the process.
     *
     * @param int $signo Signal number to send to process.
     *
     * @throws StatusError If the process is not running.
     * @throws ProcessException If sending the signal fails.
     */
    public function signal($signo)
    {
        if (!\is_int($signo)) {
            if (!(\is_bool($signo) || \is_numeric($signo))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($signo) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($signo) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $signo = (int) $signo;
            }
        }
        if (!$this->isRunning()) {
            throw new StatusError("Process is not running.");
        }
        $this->processRunner->signal($this->handle, $signo);
    }
    /**
     * Returns the PID of the child process.
     *
     * @return int
     *
     * @throws StatusError If the process has not started or has not completed starting.
     */
    public function getPid()
    {
        if (!$this->pid) {
            throw new StatusError("Process has not been started or has not completed starting.");
        }
        $phabelReturn = $this->pid;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Returns the command to execute.
     *
     * @return string The command to execute.
     */
    public function getCommand()
    {
        $phabelReturn = $this->command;
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
     * Gets the current working directory.
     *
     * @return string The current working directory an empty string if inherited from the current PHP process.
     */
    public function getWorkingDirectory()
    {
        if ($this->cwd === "") {
            $phabelReturn = \getcwd() ?: "";
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = $this->cwd;
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
     * Gets the environment variables array.
     *
     * @return string[] Array of environment variables.
     */
    public function getEnv()
    {
        $phabelReturn = $this->env;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Gets the options to pass to proc_open().
     *
     * @return mixed[] Array of options.
     */
    public function getOptions()
    {
        $phabelReturn = $this->options;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Determines if the process is still running.
     *
     * @return bool
     */
    public function isRunning()
    {
        $phabelReturn = $this->handle && $this->handle->status !== ProcessStatus::ENDED;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Gets the process input stream (STDIN).
     *
     * @return ProcessOutputStream
     */
    public function getStdin()
    {
        if (!$this->handle || $this->handle->status === ProcessStatus::STARTING) {
            throw new StatusError("Process has not been started or has not completed starting.");
        }
        $phabelReturn = $this->handle->stdin;
        if (!$phabelReturn instanceof ProcessOutputStream) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ProcessOutputStream, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Gets the process output stream (STDOUT).
     *
     * @return ProcessInputStream
     */
    public function getStdout()
    {
        if (!$this->handle || $this->handle->status === ProcessStatus::STARTING) {
            throw new StatusError("Process has not been started or has not completed starting.");
        }
        $phabelReturn = $this->handle->stdout;
        if (!$phabelReturn instanceof ProcessInputStream) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ProcessInputStream, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Gets the process error stream (STDERR).
     *
     * @return ProcessInputStream
     */
    public function getStderr()
    {
        if (!$this->handle || $this->handle->status === ProcessStatus::STARTING) {
            throw new StatusError("Process has not been started or has not completed starting.");
        }
        $phabelReturn = $this->handle->stderr;
        if (!$phabelReturn instanceof ProcessInputStream) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ProcessInputStream, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function __debugInfo()
    {
        $phabelReturn = ['command' => $this->getCommand(), 'cwd' => $this->getWorkingDirectory(), 'env' => $this->getEnv(), 'options' => $this->getOptions(), 'pid' => $this->pid, 'status' => $this->handle ? $this->handle->status : -1];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
