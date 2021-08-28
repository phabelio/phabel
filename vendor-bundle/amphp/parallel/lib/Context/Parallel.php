<?php

namespace Phabel\Amp\Parallel\Context;

use Phabel\Amp\Loop;
use Phabel\Amp\Parallel\Sync\ChannelException;
use Phabel\Amp\Parallel\Sync\ChannelledSocket;
use Phabel\Amp\Parallel\Sync\ExitFailure;
use Phabel\Amp\Parallel\Sync\ExitResult;
use Phabel\Amp\Parallel\Sync\ExitSuccess;
use Phabel\Amp\Parallel\Sync\SerializationException;
use Phabel\Amp\Parallel\Sync\SynchronizationError;
use Phabel\Amp\Promise;
use Phabel\Amp\TimeoutException;
use parallel\Runtime;
use function Phabel\Amp\call;
/**
 * Implements an execution context using native threads provided by the parallel extension.
 */
final class Parallel implements Context
{
    const EXIT_CHECK_FREQUENCY = 250;
    const KEY_LENGTH = 32;
    /** @var string|null */
    private static $autoloadPath;
    /** @var int Next thread ID. */
    private static $nextId = 1;
    /** @var Internal\ProcessHub */
    private $hub;
    /** @var int|null */
    private $id;
    /** @var Runtime|null */
    private $runtime;
    /** @var ChannelledSocket|null A channel for communicating with the parallel thread. */
    private $channel;
    /** @var string Script path. */
    private $script;
    /** @var string[] */
    private $args = [];
    /** @var int */
    private $oid = 0;
    /** @var bool */
    private $killed = \false;
    /**
     * Checks if threading is enabled.
     *
     * @return bool True if threading is enabled, otherwise false.
     */
    public static function isSupported()
    {
        $phabelReturn = \extension_loaded('parallel');
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
     * Creates and starts a new thread.
     *
     * @param string|array $script Path to PHP script or array with first element as path and following elements options
     *     to the PHP script (e.g.: ['bin/worker', 'Option1Value', 'Option2Value'].
     *
     * @return Promise<Thread> The thread object that was spawned.
     */
    public static function run($script)
    {
        $thread = new self($script);
        $phabelReturn = call(function () use($thread) {
            (yield $thread->start());
            return $thread;
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param string|array $script Path to PHP script or array with first element as path and following elements options
     *     to the PHP script (e.g.: ['bin/worker', 'Option1Value', 'Option2Value'].
     *
     * @throws \Error Thrown if the pthreads extension is not available.
     */
    public function __construct($script)
    {
        if (!self::isSupported()) {
            throw new \Error("The parallel extension is required to create parallel threads.");
        }
        $this->hub = Loop::getState(self::class);
        if (!$this->hub instanceof Internal\ParallelHub) {
            $this->hub = new Internal\ParallelHub();
            Loop::setState(self::class, $this->hub);
        }
        if (\is_array($script)) {
            $this->script = (string) \array_shift($script);
            $this->args = \array_values(\array_map("strval", $script));
        } else {
            $this->script = (string) $script;
        }
        if (self::$autoloadPath === null) {
            $paths = [\dirname(__DIR__, 2) . \DIRECTORY_SEPARATOR . "vendor" . \DIRECTORY_SEPARATOR . "autoload.php", \dirname(__DIR__, 4) . \DIRECTORY_SEPARATOR . "autoload.php"];
            foreach ($paths as $path) {
                if (\file_exists($path)) {
                    self::$autoloadPath = $path;
                    break;
                }
            }
            if (self::$autoloadPath === null) {
                throw new \Error("Could not locate autoload.php");
            }
        }
    }
    /**
     * Returns the thread to the condition before starting. The new thread can be started and run independently of the
     * first thread.
     */
    public function __clone()
    {
        $this->runtime = null;
        $this->channel = null;
        $this->id = null;
        $this->oid = 0;
        $this->killed = \false;
    }
    /**
     * Kills the thread if it is still running.
     *
     * @throws \Amp\Parallel\Context\ContextException
     */
    public function __destruct()
    {
        if (\getmypid() === $this->oid) {
            $this->kill();
        }
    }
    /**
     * Checks if the context is running.
     *
     * @return bool True if the context is running, otherwise false.
     */
    public function isRunning()
    {
        $phabelReturn = $this->channel !== null;
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
     * Spawns the thread and begins the thread's execution.
     *
     * @return Promise<int> Resolved once the thread has started.
     *
     * @throws \Amp\Parallel\Context\StatusError If the thread has already been started.
     * @throws \Amp\Parallel\Context\ContextException If starting the thread was unsuccessful.
     */
    public function start()
    {
        if ($this->oid !== 0) {
            throw new StatusError('The thread has already been started.');
        }
        $this->oid = \getmypid();
        $this->runtime = new Runtime(self::$autoloadPath);
        $this->id = self::$nextId++;
        $future = $this->runtime->run(static function ($id, $uri, $key, $path, array $argv) {
            if (!\is_int($id)) {
                if (!(\is_bool($id) || \is_numeric($id))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $id = (int) $id;
                }
            }
            if (!\is_string($uri)) {
                if (!(\is_string($uri) || \is_object($uri) && \method_exists($uri, '__toString') || (\is_bool($uri) || \is_numeric($uri)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($uri) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($uri) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $uri = (string) $uri;
                }
            }
            if (!\is_string($key)) {
                if (!(\is_string($key) || \is_object($key) && \method_exists($key, '__toString') || (\is_bool($key) || \is_numeric($key)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($key) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($key) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $key = (string) $key;
                }
            }
            if (!\is_string($path)) {
                if (!(\is_string($path) || \is_object($path) && \method_exists($path, '__toString') || (\is_bool($path) || \is_numeric($path)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($path) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($path) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $path = (string) $path;
                }
            }
            // @codeCoverageIgnoreStart
            // Only executed in thread.
            \define("AMP_CONTEXT", "parallel");
            \define("AMP_CONTEXT_ID", $id);
            if (!($socket = \stream_socket_client($uri, $errno, $errstr, 5, \STREAM_CLIENT_CONNECT))) {
                \trigger_error("Could not connect to IPC socket", \E_USER_ERROR);
                $phabelReturn = 1;
                if (!\is_int($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (int) $phabelReturn;
                    }
                }
                return $phabelReturn;
            }
            $channel = new ChannelledSocket($socket, $socket);
            try {
                Promise\wait($channel->send($key));
            } catch (\Exception $exception) {
                \trigger_error("Could not send key to parent", \E_USER_ERROR);
                $phabelReturn = 1;
                if (!\is_int($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (int) $phabelReturn;
                    }
                }
                return $phabelReturn;
            } catch (\Error $exception) {
                \trigger_error("Could not send key to parent", \E_USER_ERROR);
                $phabelReturn = 1;
                if (!\is_int($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (int) $phabelReturn;
                    }
                }
                return $phabelReturn;
            }
            try {
                Loop::unreference(Loop::repeat(self::EXIT_CHECK_FREQUENCY, function () {
                    // Timer to give the chance for the PHP VM to be interrupted by Runtime::kill(), since system calls such as
                    // select() will not be interrupted.
                }));
                try {
                    if (!\is_file($path)) {
                        throw new \Error(\sprintf("No script found at '%s' (be sure to provide the full path to the script)", $path));
                    }
                    $argc = \array_unshift($argv, $path);
                    try {
                        $phabel_b287bb527139f221 = \Phabel\Plugin\NestedExpressionFixer::returnMe(function () use($argc, $argv) {
                            $phabelReturn = (require $argv[0]);
                            if (!\is_callable($phabelReturn)) {
                                throw new \TypeError(__METHOD__ . '(): Return value must be of type callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                            }
                            // Using $argc so it is available to the required script.
                            return $phabelReturn;
                        })->bindTo(null, null);
                        // Protect current scope by requiring script within another function.
                        $callable = $phabel_b287bb527139f221();
                    } catch (\TypeError $exception) {
                        throw new \Error(\sprintf("Script '%s' did not return a callable function", $path), 0, $exception);
                    } catch (\ParseError $exception) {
                        throw new \Error(\sprintf("Script '%s' contains a parse error", $path), 0, $exception);
                    }
                    $result = new ExitSuccess(Promise\wait(call($callable, $channel)));
                } catch (\Exception $exception) {
                    $result = new ExitFailure($exception);
                } catch (\Error $exception) {
                    $result = new ExitFailure($exception);
                }
                Promise\wait(call(function () use($channel, $result) {
                    try {
                        (yield $channel->send($result));
                    } catch (SerializationException $exception) {
                        // Serializing the result failed. Send the reason why.
                        (yield $channel->send(new ExitFailure($exception)));
                    }
                }));
            } catch (\Exception $exception) {
                \trigger_error("Could not send result to parent; be sure to shutdown the child before ending the parent", \E_USER_ERROR);
                $phabelReturn = 1;
                if (!\is_int($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (int) $phabelReturn;
                    }
                }
                return $phabelReturn;
            } catch (\Error $exception) {
                \trigger_error("Could not send result to parent; be sure to shutdown the child before ending the parent", \E_USER_ERROR);
                $phabelReturn = 1;
                if (!\is_int($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (int) $phabelReturn;
                    }
                }
                return $phabelReturn;
            } finally {
                $channel->close();
            }
            $phabelReturn = 0;
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
            return $phabelReturn;
            // @codeCoverageIgnoreEnd
            throw new \TypeError(__METHOD__ . '(): Return value must be of type int, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }, [$this->id, $this->hub->getUri(), $this->hub->generateKey($this->id, self::KEY_LENGTH), $this->script, $this->args]);
        $phabelReturn = call(function () use($future) {
            try {
                $this->channel = (yield $this->hub->accept($this->id));
                $this->hub->add($this->id, $this->channel, $future);
            } catch (\Exception $exception) {
                $this->kill();
                throw new ContextException("Starting the parallel runtime failed", 0, $exception);
            } catch (\Error $exception) {
                $this->kill();
                throw new ContextException("Starting the parallel runtime failed", 0, $exception);
            }
            if ($this->killed) {
                $this->kill();
            }
            return $this->id;
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Immediately kills the context.
     */
    public function kill()
    {
        $this->killed = \true;
        if ($this->runtime !== null) {
            try {
                $this->runtime->kill();
            } finally {
                $this->close();
            }
        }
    }
    /**
     * Closes channel and socket if still open.
     */
    private function close()
    {
        $this->runtime = null;
        if ($this->channel !== null) {
            $this->channel->close();
        }
        $this->channel = null;
        $this->hub->remove($this->id);
    }
    /**
     * Gets a promise that resolves when the context ends and joins with the
     * parent context.
     *
     * @return \Amp\Promise<mixed>
     *
     * @throws StatusError Thrown if the context has not been started.
     * @throws SynchronizationError Thrown if an exit status object is not received.
     * @throws ContextException If the context stops responding.
     */
    public function join()
    {
        if ($this->channel === null) {
            throw new StatusError('The thread has not been started or has already finished.');
        }
        $phabelReturn = call(function () {
            try {
                $response = (yield $this->channel->receive());
                $this->close();
            } catch (\Exception $exception) {
                $this->kill();
                throw new ContextException("Failed to receive result from thread", 0, $exception);
            } catch (\Error $exception) {
                $this->kill();
                throw new ContextException("Failed to receive result from thread", 0, $exception);
            }
            if (!$response instanceof ExitResult) {
                $this->kill();
                throw new SynchronizationError('Did not receive an exit result from thread.');
            }
            return $response->getResult();
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function receive()
    {
        if ($this->channel === null) {
            throw new StatusError('The thread has not been started.');
        }
        $phabelReturn = call(function () {
            try {
                $data = (yield $this->channel->receive());
            } catch (ChannelException $e) {
                throw new ContextException("The thread stopped responding, potentially due to a fatal error or calling exit", 0, $e);
            }
            if ($data instanceof ExitResult) {
                $data = $data->getResult();
                throw new SynchronizationError(\sprintf('Thread unexpectedly exited with result of type: %s', \is_object($data) ? \get_class($data) : \gettype($data)));
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
            throw new StatusError('The thread has not been started or has already finished.');
        }
        if ($data instanceof ExitResult) {
            throw new \Error('Cannot send exit result objects.');
        }
        $phabelReturn = call(function () use($data) {
            try {
                return (yield $this->channel->send($data));
            } catch (ChannelException $e) {
                if ($this->channel === null) {
                    throw new ContextException("The thread stopped responding, potentially due to a fatal error or calling exit", 0, $e);
                }
                try {
                    $data = (yield Promise\timeout($this->join(), 100));
                } catch (ContextException $ex) {
                    $this->kill();
                    throw new ContextException("The thread stopped responding, potentially due to a fatal error or calling exit", 0, $e);
                } catch (ChannelException $ex) {
                    $this->kill();
                    throw new ContextException("The thread stopped responding, potentially due to a fatal error or calling exit", 0, $e);
                } catch (TimeoutException $ex) {
                    $this->kill();
                    throw new ContextException("The thread stopped responding, potentially due to a fatal error or calling exit", 0, $e);
                }
                throw new SynchronizationError(\sprintf('Thread unexpectedly exited with result of type: %s', \is_object($data) ? \get_class($data) : \gettype($data)), 0, $e);
            }
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Returns the ID of the thread. This ID will be unique to this process.
     *
     * @return int
     *
     * @throws \Amp\Process\StatusError
     */
    public function getId()
    {
        if ($this->id === null) {
            throw new StatusError('The thread has not been started');
        }
        $phabelReturn = $this->id;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
