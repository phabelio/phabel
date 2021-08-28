<?php

namespace Phabel\Amp\Parallel\Context\Internal;

use Phabel\Amp\Deferred;
use Phabel\Amp\Loop;
use Phabel\Amp\Parallel\Context\ContextException;
use Phabel\Amp\Parallel\Sync\ChannelledSocket;
use Phabel\Amp\Promise;
use Phabel\Amp\TimeoutException;
use function Phabel\Amp\asyncCall;
use function Phabel\Amp\call;
class ProcessHub
{
    const PROCESS_START_TIMEOUT = 5000;
    const KEY_RECEIVE_TIMEOUT = 1000;
    /** @var resource|null */
    private $server;
    /** @var string|null */
    private $uri;
    /** @var int[] */
    private $keys;
    /** @var string|null */
    private $watcher;
    /** @var Deferred[] */
    private $acceptor = [];
    /** @var string|null */
    private $toUnlink;
    public function __construct()
    {
        $isWindows = \strncasecmp(\PHP_OS, "WIN", 3) === 0;
        if ($isWindows) {
            $this->uri = "tcp://127.0.0.1:0";
        } else {
            $suffix = \bin2hex(\random_bytes(10));
            $path = \sys_get_temp_dir() . "/amp-parallel-ipc-" . $suffix . ".sock";
            $this->uri = "unix://" . $path;
            $this->toUnlink = $path;
        }
        $context = \stream_context_create(['socket' => ['backlog' => 128]]);
        $this->server = \stream_socket_server($this->uri, $errno, $errstr, \STREAM_SERVER_BIND | \STREAM_SERVER_LISTEN, $context);
        if (!$this->server) {
            throw new \RuntimeException(\sprintf("Could not create IPC server: (Errno: %d) %s", $errno, $errstr));
        }
        if ($isWindows) {
            $name = \stream_socket_get_name($this->server, \false);
            $port = \substr($name, \strrpos($name, ":") + 1);
            $this->uri = "tcp://127.0.0.1:" . $port;
        }
        $keys =& $this->keys;
        $acceptor =& $this->acceptor;
        $this->watcher = Loop::onReadable($this->server, static function ($watcher, $server) use(&$keys, &$acceptor) {
            if (!\is_string($watcher)) {
                if (!(\is_string($watcher) || \is_object($watcher) && \method_exists($watcher, '__toString') || (\is_bool($watcher) || \is_numeric($watcher)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcher) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcher) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $watcher = (string) $watcher;
                }
            }
            // Error reporting suppressed since stream_socket_accept() emits E_WARNING on client accept failure.
            while ($client = @\stream_socket_accept($server, 0)) {
                // Timeout of 0 to be non-blocking.
                asyncCall(static function () use($client, &$keys, &$acceptor) {
                    $channel = new ChannelledSocket($client, $client);
                    try {
                        $received = (yield Promise\timeout($channel->receive(), self::KEY_RECEIVE_TIMEOUT));
                    } catch (\Exception $exception) {
                        $channel->close();
                        return;
                        // Ignore possible foreign connection attempt.
                    } catch (\Error $exception) {
                        $channel->close();
                        return;
                        // Ignore possible foreign connection attempt.
                    }
                    if (!\is_string($received) || !isset($keys[$received])) {
                        $channel->close();
                        return;
                        // Ignore possible foreign connection attempt.
                    }
                    $pid = $keys[$received];
                    $deferred = $acceptor[$pid];
                    unset($acceptor[$pid], $keys[$received]);
                    $deferred->resolve($channel);
                });
            }
        });
        Loop::disable($this->watcher);
    }
    public function __destruct()
    {
        Loop::cancel($this->watcher);
        \fclose($this->server);
        if ($this->toUnlink !== null) {
            @\unlink($this->toUnlink);
        }
    }
    public function getUri()
    {
        $phabelReturn = $this->uri;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function generateKey($pid, $length)
    {
        if (!\is_int($pid)) {
            if (!(\is_bool($pid) || \is_numeric($pid))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($pid) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($pid) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $pid = (int) $pid;
            }
        }
        if (!\is_int($length)) {
            if (!(\is_bool($length) || \is_numeric($length))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($length) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $length = (int) $length;
            }
        }
        $key = \random_bytes($length);
        $this->keys[$key] = $pid;
        $phabelReturn = $key;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function accept($pid)
    {
        if (!\is_int($pid)) {
            if (!(\is_bool($pid) || \is_numeric($pid))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($pid) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($pid) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $pid = (int) $pid;
            }
        }
        $phabelReturn = call(function () use($pid) {
            $this->acceptor[$pid] = new Deferred();
            Loop::enable($this->watcher);
            try {
                $channel = (yield Promise\timeout($this->acceptor[$pid]->promise(), self::PROCESS_START_TIMEOUT));
            } catch (TimeoutException $exception) {
                $key = \array_search($pid, $this->keys, \true);
                \assert(\is_string($key), "Key for {$pid} not found");
                unset($this->acceptor[$pid], $this->keys[$key]);
                throw new ContextException("Starting the process timed out", 0, $exception);
            } finally {
                if (empty($this->acceptor)) {
                    Loop::disable($this->watcher);
                }
            }
            return $channel;
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
