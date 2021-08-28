<?php

namespace Phabel\Amp\Parallel\Context;

use Phabel\Amp\Failure;
use Phabel\Amp\Loop;
use Phabel\Amp\Parallel\Sync\ChannelledSocket;
use Phabel\Amp\Parallel\Sync\ExitResult;
use Phabel\Amp\Parallel\Sync\SynchronizationError;
use Phabel\Amp\Promise;
use Phabel\Amp\Success;
use function Phabel\Amp\call;
/**
 * Implements an execution context using native multi-threading.
 *
 * The thread context is not itself threaded. A local instance of the context is
 * maintained both in the context that creates the thread and in the thread
 * itself.
 *
 * @deprecated ext-pthreads development has been halted, see https://github.com/krakjoe/pthreads/issues/929
 */
final class Thread implements Context
{
    const EXIT_CHECK_FREQUENCY = 250;
    /** @var int */
    private static $nextId = 1;
    /** @var Internal\Thread An internal thread instance. */
    private $thread;
    /** @var ChannelledSocket A channel for communicating with the thread. */
    private $channel;
    /** @var resource */
    private $socket;
    /** @var callable */
    private $function;
    /** @var mixed[] */
    private $args;
    /** @var int|null */
    private $id;
    /** @var int */
    private $oid = 0;
    /** @var string */
    private $watcher;
    /**
     * Checks if threading is enabled.
     *
     * @return bool True if threading is enabled, otherwise false.
     */
    public static function isSupported()
    {
        $phabelReturn = \extension_loaded('pthreads');
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
     * @param callable $function The callable to invoke in the thread. First argument is an instance of
     *     \Amp\Parallel\Sync\Channel.
     * @param mixed ...$args Additional arguments to pass to the given callable.
     *
     * @return Promise<Thread> The thread object that was spawned.
     */
    public static function run(callable $function, ...$args)
    {
        $thread = new self($function, ...$args);
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
     * Creates a new thread.
     *
     * @param callable $function The callable to invoke in the thread. First argument is an instance of
     *     \Amp\Parallel\Sync\Channel.
     * @param mixed ...$args Additional arguments to pass to the given callable.
     *
     * @throws \Error Thrown if the pthreads extension is not available.
     */
    public function __construct(callable $function, ...$args)
    {
        if (!self::isSupported()) {
            throw new \Error("The pthreads extension is required to create threads.");
        }
        $this->function = $function;
        $this->args = $args;
    }
    /**
     * Returns the thread to the condition before starting. The new thread can be started and run independently of the
     * first thread.
     */
    public function __clone()
    {
        $this->thread = null;
        $this->socket = null;
        $this->channel = null;
        $this->oid = 0;
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
        $sockets = @\stream_socket_pair(\stripos(\PHP_OS, "win") === 0 ? \STREAM_PF_INET : \STREAM_PF_UNIX, \STREAM_SOCK_STREAM, \STREAM_IPPROTO_IP);
        if ($sockets === \false) {
            $message = "Failed to create socket pair";
            if ($error = \error_get_last()) {
                $message .= \sprintf(" Errno: %d; %s", $error["type"], $error["message"]);
            }
            $phabelReturn = new Failure(new ContextException($message));
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        list($channel, $this->socket) = $sockets;
        $this->id = self::$nextId++;
        $thread = $this->thread = new Internal\Thread($this->id, $this->socket, $this->function, $this->args);
        if (!$this->thread->start(\PTHREADS_INHERIT_INI)) {
            $phabelReturn = new Failure(new ContextException('Failed to start the thread.'));
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $channel = $this->channel = new ChannelledSocket($channel, $channel);
        $this->watcher = Loop::repeat(self::EXIT_CHECK_FREQUENCY, static function ($watcher) use($thread, $channel) {
            if (!$thread->isRunning()) {
                // Delay closing to avoid race condition between thread exiting and data becoming available.
                Loop::delay(self::EXIT_CHECK_FREQUENCY, [$channel, "close"]);
                Loop::cancel($watcher);
            }
        });
        Loop::disable($this->watcher);
        $phabelReturn = new Success($this->id);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Immediately kills the context.
     *
     * @throws ContextException If killing the thread was unsuccessful.
     */
    public function kill()
    {
        if ($this->thread !== null) {
            try {
                if ($this->thread->isRunning() && !$this->thread->kill()) {
                    throw new ContextException('Could not kill thread.');
                }
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
        if ($this->channel !== null) {
            $this->channel->close();
        }
        $this->channel = null;
        Loop::cancel($this->watcher);
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
        if ($this->channel == null || $this->thread === null) {
            throw new StatusError('The thread has not been started or has already finished.');
        }
        $phabelReturn = call(function () {
            Loop::enable($this->watcher);
            try {
                $response = (yield $this->channel->receive());
            } catch (\Exception $exception) {
                $this->kill();
                throw new ContextException("Failed to receive result from thread", 0, $exception);
            } catch (\Error $exception) {
                $this->kill();
                throw new ContextException("Failed to receive result from thread", 0, $exception);
            } finally {
                Loop::disable($this->watcher);
                $this->close();
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
            throw new StatusError('The process has not been started.');
        }
        $phabelReturn = call(function () {
            Loop::enable($this->watcher);
            try {
                $data = (yield $this->channel->receive());
            } finally {
                Loop::disable($this->watcher);
            }
            if ($data instanceof ExitResult) {
                $data = $data->getResult();
                throw new SynchronizationError(\sprintf('Thread process unexpectedly exited with result of type: %s', \is_object($data) ? \get_class($data) : \gettype($data)));
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
            Loop::enable($this->watcher);
            try {
                $result = (yield $this->channel->send($data));
            } finally {
                Loop::disable($this->watcher);
            }
            return $result;
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
