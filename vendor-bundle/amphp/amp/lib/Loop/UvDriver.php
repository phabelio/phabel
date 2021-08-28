<?php

namespace Phabel\Amp\Loop;

use Phabel\Amp\Coroutine;
use Phabel\Amp\Promise;
use Phabel\React\Promise\PromiseInterface as ReactPromise;
use function Phabel\Amp\Promise\rethrow;
class UvDriver extends Driver
{
    /** @var resource A uv_loop resource created with uv_loop_new() */
    private $handle;
    /** @var resource[] */
    private $events = [];
    /** @var Watcher[][] */
    private $watchers = [];
    /** @var resource[] */
    private $streams = [];
    /** @var callable */
    private $ioCallback;
    /** @var callable */
    private $timerCallback;
    /** @var callable */
    private $signalCallback;
    public function __construct()
    {
        $this->handle = \uv_loop_new();
        /**
         * @param $event
         * @param $status
         * @param $events
         * @param $resource
         *
         * @return void
         */
        $this->ioCallback = function ($event, $status, $events, $resource) {
            $watchers = $this->watchers[(int) $event];
            switch ($status) {
                case 0:
                    // OK
                    break;
                default:
                    // Invoke the callback on errors, as this matches behavior with other loop back-ends.
                    // Re-enable watcher as libuv disables the watcher on non-zero status.
                    $flags = 0;
                    foreach ($watchers as $watcher) {
                        $flags |= $watcher->enabled ? $watcher->type : 0;
                    }
                    \uv_poll_start($event, $flags, $this->ioCallback);
                    break;
            }
            foreach ($watchers as $watcher) {
                // $events is OR'ed with 4 to trigger watcher if no events are indicated (0) or on UV_DISCONNECT (4).
                // http://docs.libuv.org/en/v1.x/poll.html
                if (!($watcher->enabled && ($watcher->type & $events || ($events | 4) === 4))) {
                    continue;
                }
                try {
                    $phabel_2e845441a9ab246b = $watcher->callback;
                    $result = $phabel_2e845441a9ab246b($watcher->id, $resource, $watcher->data);
                    if ($result === null) {
                        continue;
                    }
                    if ($result instanceof \Generator) {
                        $result = new Coroutine($result);
                    }
                    if ($result instanceof Promise || $result instanceof ReactPromise) {
                        rethrow($result);
                    }
                } catch (\Exception $exception) {
                    $this->error($exception);
                } catch (\Error $exception) {
                    $this->error($exception);
                }
            }
        };
        /**
         * @param $event
         *
         * @return void
         */
        $this->timerCallback = function ($event) {
            $watcher = $this->watchers[(int) $event][0];
            if ($watcher->type & Watcher::DELAY) {
                unset($this->events[$watcher->id], $this->watchers[(int) $event]);
                // Avoid call to uv_is_active().
                $this->cancel($watcher->id);
                // Remove reference to watcher in parent.
            } elseif ($watcher->value === 0) {
                // Disable and re-enable so it's not executed repeatedly in the same tick
                // See https://github.com/amphp/amp/issues/131
                $this->disable($watcher->id);
                $this->enable($watcher->id);
            }
            try {
                $phabel_db5f43921a822918 = $watcher->callback;
                $result = $phabel_db5f43921a822918($watcher->id, $watcher->data);
                if ($result === null) {
                    return;
                }
                if ($result instanceof \Generator) {
                    $result = new Coroutine($result);
                }
                if ($result instanceof Promise || $result instanceof ReactPromise) {
                    rethrow($result);
                }
            } catch (\Exception $exception) {
                $this->error($exception);
            } catch (\Error $exception) {
                $this->error($exception);
            }
        };
        /**
         * @param $event
         * @param $signo
         *
         * @return void
         */
        $this->signalCallback = function ($event, $signo) {
            $watcher = $this->watchers[(int) $event][0];
            try {
                $phabel_825278b93725b9b6 = $watcher->callback;
                $result = $phabel_825278b93725b9b6($watcher->id, $signo, $watcher->data);
                if ($result === null) {
                    return;
                }
                if ($result instanceof \Generator) {
                    $result = new Coroutine($result);
                }
                if ($result instanceof Promise || $result instanceof ReactPromise) {
                    rethrow($result);
                }
            } catch (\Exception $exception) {
                $this->error($exception);
            } catch (\Error $exception) {
                $this->error($exception);
            }
        };
    }
    /**
     * {@inheritdoc}
     */
    public function cancel($watcherId)
    {
        if (!\is_string($watcherId)) {
            if (!(\is_string($watcherId) || \is_object($watcherId) && \method_exists($watcherId, '__toString') || (\is_bool($watcherId) || \is_numeric($watcherId)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcherId) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcherId) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $watcherId = (string) $watcherId;
            }
        }
        parent::cancel($watcherId);
        if (!isset($this->events[$watcherId])) {
            return;
        }
        $event = $this->events[$watcherId];
        $eventId = (int) $event;
        if (isset($this->watchers[$eventId][0])) {
            // All except IO watchers.
            unset($this->watchers[$eventId]);
        } elseif (isset($this->watchers[$eventId][$watcherId])) {
            $watcher = $this->watchers[$eventId][$watcherId];
            unset($this->watchers[$eventId][$watcherId]);
            if (empty($this->watchers[$eventId])) {
                unset($this->watchers[$eventId], $this->streams[(int) $watcher->value]);
            }
        }
        unset($this->events[$watcherId]);
    }
    public static function isSupported()
    {
        $phabelReturn = \extension_loaded("uv");
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
    public function now()
    {
        \uv_update_time($this->handle);
        $phabelReturn = \uv_now($this->handle);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        /** @psalm-suppress TooManyArguments */
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function getHandle()
    {
        return $this->handle;
    }
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    protected function dispatch($blocking)
    {
        if (!\is_bool($blocking)) {
            if (!(\is_bool($blocking) || \is_numeric($blocking) || \is_string($blocking))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($blocking) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($blocking) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $blocking = (bool) $blocking;
            }
        }
        /** @psalm-suppress TooManyArguments */
        \uv_run($this->handle, $blocking ? \UV::RUN_ONCE : \UV::RUN_NOWAIT);
    }
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    protected function activate(array $watchers)
    {
        $now = $this->now();
        foreach ($watchers as $watcher) {
            $id = $watcher->id;
            switch ($watcher->type) {
                case Watcher::READABLE:
                case Watcher::WRITABLE:
                    \assert(\is_resource($watcher->value));
                    $streamId = (int) $watcher->value;
                    if (isset($this->streams[$streamId])) {
                        $event = $this->streams[$streamId];
                    } elseif (isset($this->events[$id])) {
                        $event = $this->streams[$streamId] = $this->events[$id];
                    } else {
                        /** @psalm-suppress UndefinedFunction */
                        $event = $this->streams[$streamId] = \Phabel\uv_poll_init_socket($this->handle, $watcher->value);
                    }
                    $eventId = (int) $event;
                    $this->events[$id] = $event;
                    $this->watchers[$eventId][$id] = $watcher;
                    $flags = 0;
                    foreach ($this->watchers[$eventId] as $w) {
                        $flags |= $w->enabled ? $w->type : 0;
                    }
                    \uv_poll_start($event, $flags, $this->ioCallback);
                    break;
                case Watcher::DELAY:
                case Watcher::REPEAT:
                    \assert(\is_int($watcher->value));
                    if (isset($this->events[$id])) {
                        $event = $this->events[$id];
                    } else {
                        $event = $this->events[$id] = \uv_timer_init($this->handle);
                    }
                    $this->watchers[(int) $event] = [$watcher];
                    \uv_timer_start($event, \max(0, $watcher->expiration - $now), $watcher->type & Watcher::REPEAT ? $watcher->value : 0, $this->timerCallback);
                    break;
                case Watcher::SIGNAL:
                    \assert(\is_int($watcher->value));
                    if (isset($this->events[$id])) {
                        $event = $this->events[$id];
                    } else {
                        /** @psalm-suppress UndefinedFunction */
                        $event = $this->events[$id] = \Phabel\uv_signal_init($this->handle);
                    }
                    $this->watchers[(int) $event] = [$watcher];
                    /** @psalm-suppress UndefinedFunction */
                    \Phabel\uv_signal_start($event, $this->signalCallback, $watcher->value);
                    break;
                default:
                    // @codeCoverageIgnoreStart
                    throw new \Error("Unknown watcher type");
            }
        }
    }
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    protected function deactivate(Watcher $watcher)
    {
        $id = $watcher->id;
        if (!isset($this->events[$id])) {
            return;
        }
        $event = $this->events[$id];
        if (!\uv_is_active($event)) {
            return;
        }
        switch ($watcher->type) {
            case Watcher::READABLE:
            case Watcher::WRITABLE:
                $flags = 0;
                foreach ($this->watchers[(int) $event] as $w) {
                    $flags |= $w->enabled ? $w->type : 0;
                }
                if ($flags) {
                    \uv_poll_start($event, $flags, $this->ioCallback);
                } else {
                    \uv_poll_stop($event);
                }
                break;
            case Watcher::DELAY:
            case Watcher::REPEAT:
                \uv_timer_stop($event);
                break;
            case Watcher::SIGNAL:
                \uv_signal_stop($event);
                break;
            default:
                // @codeCoverageIgnoreStart
                throw new \Error("Unknown watcher type");
        }
    }
}
