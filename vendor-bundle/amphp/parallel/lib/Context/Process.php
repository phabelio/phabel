<?php

namespace Phabel\Amp\Parallel\Context;

use Phabel\Amp\Loop;
use Phabel\Amp\Parallel\Sync\ChannelException;
use Phabel\Amp\Parallel\Sync\ChannelledSocket;
use Phabel\Amp\Parallel\Sync\ExitResult;
use Phabel\Amp\Parallel\Sync\SynchronizationError;
use Phabel\Amp\Process\Process as BaseProcess;
use Phabel\Amp\Process\ProcessInputStream;
use Phabel\Amp\Process\ProcessOutputStream;
use Phabel\Amp\Promise;
use Phabel\Amp\TimeoutException;
use function Phabel\Amp\call;
final class Process implements Context
{
    const SCRIPT_PATH = __DIR__ . "/Internal/process-runner.php";
    const KEY_LENGTH = 32;
    /** @var string|null External version of SCRIPT_PATH if inside a PHAR. */
    private static $pharScriptPath;
    /** @var string|null PHAR path with a '.phar' extension. */
    private static $pharCopy;
    /** @var string|null Cached path to located PHP binary. */
    private static $binaryPath;
    /** @var Internal\ProcessHub */
    private $hub;
    /** @var BaseProcess */
    private $process;
    /** @var ChannelledSocket */
    private $channel;
    /**
     * Creates and starts the process at the given path using the optional PHP binary path.
     *
     * @param string|array $script Path to PHP script or array with first element as path and following elements options
     *     to the PHP script (e.g.: ['bin/worker', 'Option1Value', 'Option2Value'].
     * @param string|null  $cwd Working directory.
     * @param mixed[]      $env Array of environment variables.
     * @param string       $binary Path to PHP binary. Null will attempt to automatically locate the binary.
     *
     * @return Promise<Process>
     */
    public static function run($script, $cwd = null, array $env = [], $binary = null)
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
        if (!\is_null($binary)) {
            if (!\is_string($binary)) {
                if (!(\is_string($binary) || \is_object($binary) && \method_exists($binary, '__toString') || (\is_bool($binary) || \is_numeric($binary)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($binary) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($binary) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $binary = (string) $binary;
                }
            }
        }
        $process = new self($script, $cwd, $env, $binary);
        $phabelReturn = call(function () use($process) {
            (yield $process->start());
            return $process;
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param string|array $script Path to PHP script or array with first element as path and following elements options
     *     to the PHP script (e.g.: ['bin/worker', 'Option1Value', 'Option2Value'].
     * @param string|null  $cwd Working directory.
     * @param mixed[]      $env Array of environment variables.
     * @param string       $binary Path to PHP binary. Null will attempt to automatically locate the binary.
     *
     * @throws \Error If the PHP binary path given cannot be found or is not executable.
     */
    public function __construct($script, $cwd = null, array $env = [], $binary = null)
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
        if (!\is_null($binary)) {
            if (!\is_string($binary)) {
                if (!(\is_string($binary) || \is_object($binary) && \method_exists($binary, '__toString') || (\is_bool($binary) || \is_numeric($binary)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($binary) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($binary) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $binary = (string) $binary;
                }
            }
        }
        $this->hub = Loop::getState(self::class);
        if (!$this->hub instanceof Internal\ProcessHub) {
            $this->hub = new Internal\ProcessHub();
            Loop::setState(self::class, $this->hub);
        }
        $options = ["html_errors" => "0", "display_errors" => "0", "log_errors" => "1"];
        if ($binary === null) {
            if (\PHP_SAPI === "cli") {
                $binary = \PHP_BINARY;
            } else {
                $binary = isset(self::$binaryPath) ? self::$binaryPath : self::locateBinary();
            }
        } elseif (!\is_executable($binary)) {
            throw new \Error(\sprintf("The PHP binary path '%s' was not found or is not executable", $binary));
        }
        // Write process runner to external file if inside a PHAR,
        // because PHP can't open files inside a PHAR directly except for the stub.
        if (\strpos(self::SCRIPT_PATH, "phar://") === 0) {
            if (self::$pharScriptPath) {
                $scriptPath = self::$pharScriptPath;
            } else {
                $path = \dirname(self::SCRIPT_PATH);
                if (\substr(\Phar::running(\false), -5) !== ".phar") {
                    self::$pharCopy = \sys_get_temp_dir() . "/phar-" . \bin2hex(\random_bytes(10)) . ".phar";
                    \copy(\Phar::running(\false), self::$pharCopy);
                    \register_shutdown_function(static function () {
                        @\unlink(self::$pharCopy);
                    });
                    $path = "phar://" . self::$pharCopy . "/" . \substr($path, \strlen(\Phar::running(\true)));
                }
                $contents = \file_get_contents(self::SCRIPT_PATH);
                $contents = \str_replace("__DIR__", \var_export($path, \true), $contents);
                $suffix = \bin2hex(\random_bytes(10));
                self::$pharScriptPath = $scriptPath = \sys_get_temp_dir() . "/amp-process-runner-" . $suffix . ".php";
                \file_put_contents($scriptPath, $contents);
                \register_shutdown_function(static function () {
                    @\unlink(self::$pharScriptPath);
                });
            }
            // Monkey-patch the script path in the same way, only supported if the command is given as array.
            if (isset(self::$pharCopy) && \is_array($script) && isset($script[0])) {
                $script[0] = "phar://" . self::$pharCopy . \substr($script[0], \strlen(\Phar::running(\true)));
            }
        } else {
            $scriptPath = self::SCRIPT_PATH;
        }
        if (\is_array($script)) {
            $script = \implode(" ", \array_map("escapeshellarg", $script));
        } else {
            $script = \escapeshellarg($script);
        }
        $command = \implode(" ", [\escapeshellarg($binary), $this->formatOptions($options), \escapeshellarg($scriptPath), $this->hub->getUri(), $script]);
        $this->process = new BaseProcess($command, $cwd, $env);
    }
    private static function locateBinary()
    {
        $executable = \strncasecmp(\PHP_OS, "WIN", 3) === 0 ? "php.exe" : "php";
        $paths = \array_filter(\explode(\PATH_SEPARATOR, \getenv("PATH")));
        $paths[] = \PHP_BINDIR;
        $paths = \array_unique($paths);
        foreach ($paths as $path) {
            $path .= \DIRECTORY_SEPARATOR . $executable;
            if (\is_executable($path)) {
                $phabelReturn = self::$binaryPath = $path;
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
        throw new \Error("Could not locate PHP executable binary");
        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    private function formatOptions(array $options)
    {
        $result = [];
        foreach ($options as $option => $value) {
            $result[] = \sprintf("-d%s=%s", $option, $value);
        }
        $phabelReturn = \implode(" ", $result);
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
     * Private method to prevent cloning.
     */
    private function __clone()
    {
    }
    /**
     * {@inheritdoc}
     */
    public function start()
    {
        $phabelReturn = call(function () {
            try {
                $pid = (yield $this->process->start());
                (yield $this->process->getStdin()->write($this->hub->generateKey($pid, self::KEY_LENGTH)));
                $this->channel = (yield $this->hub->accept($pid));
                return $pid;
            } catch (\Exception $exception) {
                if ($this->isRunning()) {
                    $this->kill();
                }
                throw new ContextException("Starting the process failed", 0, $exception);
            } catch (\Error $exception) {
                if ($this->isRunning()) {
                    $this->kill();
                }
                throw new ContextException("Starting the process failed", 0, $exception);
            }
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function isRunning()
    {
        $phabelReturn = $this->process->isRunning();
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
     * {@inheritdoc}
     */
    public function receive()
    {
        if ($this->channel === null) {
            throw new StatusError("The process has not been started");
        }
        $phabelReturn = call(function () {
            try {
                $data = (yield $this->channel->receive());
            } catch (ChannelException $e) {
                throw new ContextException("The process stopped responding, potentially due to a fatal error or calling exit", 0, $e);
            }
            if ($data instanceof ExitResult) {
                $data = $data->getResult();
                throw new SynchronizationError(\sprintf('Process unexpectedly exited with result of type: %s', \is_object($data) ? \get_class($data) : \gettype($data)));
            }
            return $data;
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function send($data)
    {
        if ($this->channel === null) {
            throw new StatusError("The process has not been started");
        }
        if ($data instanceof ExitResult) {
            throw new \Error("Cannot send exit result objects");
        }
        $phabelReturn = call(function () use($data) {
            try {
                return (yield $this->channel->send($data));
            } catch (ChannelException $e) {
                if ($this->channel === null) {
                    throw new ContextException("The process stopped responding, potentially due to a fatal error or calling exit", 0, $e);
                }
                try {
                    $data = (yield Promise\timeout($this->join(), 100));
                } catch (ContextException $ex) {
                    if ($this->isRunning()) {
                        $this->kill();
                    }
                    throw new ContextException("The process stopped responding, potentially due to a fatal error or calling exit", 0, $e);
                } catch (ChannelException $ex) {
                    if ($this->isRunning()) {
                        $this->kill();
                    }
                    throw new ContextException("The process stopped responding, potentially due to a fatal error or calling exit", 0, $e);
                } catch (TimeoutException $ex) {
                    if ($this->isRunning()) {
                        $this->kill();
                    }
                    throw new ContextException("The process stopped responding, potentially due to a fatal error or calling exit", 0, $e);
                }
                throw new SynchronizationError(\sprintf('Process unexpectedly exited with result of type: %s', \is_object($data) ? \get_class($data) : \gettype($data)), 0, $e);
            }
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function join()
    {
        if ($this->channel === null) {
            throw new StatusError("The process has not been started");
        }
        $phabelReturn = call(function () {
            try {
                $data = (yield $this->channel->receive());
            } catch (\Exception $exception) {
                if ($this->isRunning()) {
                    $this->kill();
                }
                throw new ContextException("Failed to receive result from process", 0, $exception);
            } catch (\Error $exception) {
                if ($this->isRunning()) {
                    $this->kill();
                }
                throw new ContextException("Failed to receive result from process", 0, $exception);
            }
            if (!$data instanceof ExitResult) {
                if ($this->isRunning()) {
                    $this->kill();
                }
                throw new SynchronizationError("Did not receive an exit result from process");
            }
            $this->channel->close();
            $code = (yield $this->process->join());
            if ($code !== 0) {
                throw new ContextException(\sprintf("Process exited with code %d", $code));
            }
            return $data->getResult();
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Send a signal to the process.
     *
     * @see \Amp\Process\Process::signal()
     *
     * @param int $signo
     *
     * @throws \Amp\Process\ProcessException
     * @throws \Amp\Process\StatusError
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
        $this->process->signal($signo);
    }
    /**
     * Returns the PID of the process.
     *
     * @see \Amp\Process\Process::getPid()
     *
     * @return int
     *
     * @throws \Amp\Process\StatusError
     */
    public function getPid()
    {
        $phabelReturn = $this->process->getPid();
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
     * Returns the STDIN stream of the process.
     *
     * @see \Amp\Process\Process::getStdin()
     *
     * @return ProcessOutputStream
     *
     * @throws \Amp\Process\StatusError
     */
    public function getStdin()
    {
        $phabelReturn = $this->process->getStdin();
        if (!$phabelReturn instanceof ProcessOutputStream) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ProcessOutputStream, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Returns the STDOUT stream of the process.
     *
     * @see \Amp\Process\Process::getStdout()
     *
     * @return ProcessInputStream
     *
     * @throws \Amp\Process\StatusError
     */
    public function getStdout()
    {
        $phabelReturn = $this->process->getStdout();
        if (!$phabelReturn instanceof ProcessInputStream) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ProcessInputStream, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Returns the STDOUT stream of the process.
     *
     * @see \Amp\Process\Process::getStderr()
     *
     * @return ProcessInputStream
     *
     * @throws \Amp\Process\StatusError
     */
    public function getStderr()
    {
        $phabelReturn = $this->process->getStderr();
        if (!$phabelReturn instanceof ProcessInputStream) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ProcessInputStream, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function kill()
    {
        $this->process->kill();
        if ($this->channel !== null) {
            $this->channel->close();
        }
    }
}
