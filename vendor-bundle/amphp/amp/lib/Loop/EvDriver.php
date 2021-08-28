<?php

/** @noinspection PhpComposerExtensionStubsInspection */
namespace Phabel\Amp\Loop;

use Phabel\Amp\Coroutine;
use Phabel\Amp\Promise;
use Phabel\React\Promise\PromiseInterface as ReactPromise;
use function Phabel\Amp\Internal\getCurrentTime;
use function Phabel\Amp\Promise\rethrow;
class EvDriver extends Driver
{
    /** @var \EvSignal[]|null */
    private static $activeSignals;
    public static function isSupported()
    {
        $phabelReturn = \extension_loaded("ev");
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /** @var \EvLoop */
    private $handle;
    /** @var \EvWatcher[] */
    private $events = [];
    /** @var callable */
    private $ioCallback;
    /** @var callable */
    private $timerCallback;
    /** @var callable */
    private $signalCallback;
    /** @var \EvSignal[] */
    private $signals = [];
    /** @var int Internal timestamp for now. */
    private $now;
    /** @var int Loop time offset */
    private $nowOffset;
    public function __construct()
    {
        $this->handle = new \EvLoop();
        $this->nowOffset = getCurrentTime();
        $this->now = \random_int(0, $this->nowOffset);
        $this->nowOffset -= $this->now;
        if (self::$activeSignals === null) {
            self::$activeSignals =& $this->signals;
        }
        /**
         * @param \EvIO $event
         *
         * @return void
         */
        $this->ioCallback = function (\Phabel\EvIO $event) {
            /** @var Watcher $watcher */
            $watcher = $event->data;
            try {
                $phabel_67d4713590c776b8 = $watcher->callback;
                $result = $phabel_67d4713590c776b8($watcher->id, $watcher->value, $watcher->data);
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
         * @param \EvTimer $event
         *
         * @return void
         */
        $this->timerCallback = function (\EvTimer $event) {
            /** @var Watcher $watcher */
            $watcher = $event->data;
            if ($watcher->type & Watcher::DELAY) {
                $this->cancel($watcher->id);
            } elseif ($watcher->value === 0) {
                // Disable and re-enable so it's not executed repeatedly in the same tick
                // See https://github.com/amphp/amp/issues/131
                $this->disable($watcher->id);
                $this->enable($watcher->id);
            }
            try {
                $phabel_db4cea38c7f7e4d4 = $watcher->callback;
                $result = $phabel_db4cea38c7f7e4d4($watcher->id, $watcher->data);
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
         * @param \EvSignal $event
         *
         * @return void
         */
        $this->signalCallback = function (\EvSignal $event) {
            /** @var Watcher $watcher */
            $watcher = $event->data;
            try {
                $phabel_b61c41162565f9e0 = $watcher->callback;
                $result = $phabel_b61c41162565f9e0($watcher->id, $watcher->value, $watcher->data);
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
        unset($this->events[$watcherId]);
    }
    public function __destruct()
    {
        foreach ($this->events as $event) {
            /** @psalm-suppress all */
            if ($event !== null) {
                // Events may have been nulled in extension depending on destruct order.
                $event->stop();
            }
        }
        // We need to clear all references to events manually, see
        // https://bitbucket.org/osmanov/pecl-ev/issues/31/segfault-in-ev_timer_stop
        $this->events = [];
    }
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $active = self::$activeSignals;
        \assert($active !== null);
        foreach ($active as $event) {
            $event->stop();
        }
        self::$activeSignals =& $this->signals;
        foreach ($this->signals as $event) {
            $event->start();
        }
        try {
            parent::run();
        } finally {
            foreach ($this->signals as $event) {
                $event->stop();
            }
            self::$activeSignals =& $active;
            foreach ($active as $event) {
                $event->start();
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public function stop()
    {
        $this->handle->stop();
        parent::stop();
    }
    /**
     * {@inheritdoc}
     */
    public function now()
    {
        $this->now = getCurrentTime() - $this->nowOffset;
        $phabelReturn = $this->now;
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
     * {@inheritdoc}
     */
    public function getHandle()
    {
        $phabelReturn = $this->handle;
        if (!$phabelReturn instanceof \EvLoop) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type EvLoop, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
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
        $this->handle->run($blocking ? \Ev::RUN_ONCE : \Ev::RUN_ONCE | \Ev::RUN_NOWAIT);
    }
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    protected function activate(array $watchers)
    {
        $this->handle->nowUpdate();
        $now = $this->now();
        foreach ($watchers as $watcher) {
            if (!isset($this->events[$id = $watcher->id])) {
                switch ($watcher->type) {
                    case Watcher::READABLE:
                        \assert(\is_resource($watcher->value));
                        $this->events[$id] = $this->handle->io($watcher->value, \Ev::READ, $this->ioCallback, $watcher);
                        break;
                    case Watcher::WRITABLE:
                        \assert(\is_resource($watcher->value));
                        $this->events[$id] = $this->handle->io($watcher->value, \Ev::WRITE, $this->ioCallback, $watcher);
                        break;
                    case Watcher::DELAY:
                    case Watcher::REPEAT:
                        \assert(\is_int($watcher->value));
                        $interval = $watcher->value / self::MILLISEC_PER_SEC;
                        $this->events[$id] = $this->handle->timer(\max(0, ($watcher->expiration - $now) / self::MILLISEC_PER_SEC), $watcher->type & Watcher::REPEAT ? $interval : 0, $this->timerCallback, $watcher);
                        break;
                    case Watcher::SIGNAL:
                        \assert(\is_int($watcher->value));
                        $this->events[$id] = $this->handle->signal($watcher->value, $this->signalCallback, $watcher);
                        break;
                    default:
                        // @codeCoverageIgnoreStart
                        throw new \Error("Unknown watcher type");
                }
            } else {
                $this->events[$id]->start();
            }
            if ($watcher->type === Watcher::SIGNAL) {
                /** @psalm-suppress PropertyTypeCoercion */
                $this->signals[$id] = $this->events[$id];
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
        if (isset($this->events[$id = $watcher->id])) {
            $this->events[$id]->stop();
            if ($watcher->type === Watcher::SIGNAL) {
                unset($this->signals[$id]);
            }
        }
    }
}
