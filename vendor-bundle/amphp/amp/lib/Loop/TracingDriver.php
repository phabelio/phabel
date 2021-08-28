<?php

namespace Phabel\Amp\Loop;

use function Phabel\Amp\Internal\formatStacktrace;
final class TracingDriver extends Driver
{
    /** @var Driver */
    private $driver;
    /** @var true[] */
    private $enabledWatchers = [];
    /** @var true[] */
    private $unreferencedWatchers = [];
    /** @var string[] */
    private $creationTraces = [];
    /** @var string[] */
    private $cancelTraces = [];
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }
    public function run()
    {
        $this->driver->run();
    }
    public function stop()
    {
        $this->driver->stop();
    }
    public function defer(callable $callback, $data = null)
    {
        $id = $this->driver->defer(function (...$args) use($callback) {
            $this->cancel($args[0]);
            return $callback(...$args);
        }, $data);
        $this->creationTraces[$id] = formatStacktrace(\debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS));
        $this->enabledWatchers[$id] = \true;
        $phabelReturn = $id;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function delay($delay, callable $callback, $data = null)
    {
        if (!\is_int($delay)) {
            if (!(\is_bool($delay) || \is_numeric($delay))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($delay) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($delay) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $delay = (int) $delay;
            }
        }
        $id = $this->driver->delay($delay, function (...$args) use($callback) {
            $this->cancel($args[0]);
            return $callback(...$args);
        }, $data);
        $this->creationTraces[$id] = formatStacktrace(\debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS));
        $this->enabledWatchers[$id] = \true;
        $phabelReturn = $id;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function repeat($interval, callable $callback, $data = null)
    {
        if (!\is_int($interval)) {
            if (!(\is_bool($interval) || \is_numeric($interval))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($interval) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($interval) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $interval = (int) $interval;
            }
        }
        $id = $this->driver->repeat($interval, $callback, $data);
        $this->creationTraces[$id] = formatStacktrace(\debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS));
        $this->enabledWatchers[$id] = \true;
        $phabelReturn = $id;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function onReadable($stream, callable $callback, $data = null)
    {
        $id = $this->driver->onReadable($stream, $callback, $data);
        $this->creationTraces[$id] = formatStacktrace(\debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS));
        $this->enabledWatchers[$id] = \true;
        $phabelReturn = $id;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function onWritable($stream, callable $callback, $data = null)
    {
        $id = $this->driver->onWritable($stream, $callback, $data);
        $this->creationTraces[$id] = formatStacktrace(\debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS));
        $this->enabledWatchers[$id] = \true;
        $phabelReturn = $id;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function onSignal($signo, callable $callback, $data = null)
    {
        if (!\is_int($signo)) {
            if (!(\is_bool($signo) || \is_numeric($signo))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($signo) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($signo) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $signo = (int) $signo;
            }
        }
        $id = $this->driver->onSignal($signo, $callback, $data);
        $this->creationTraces[$id] = formatStacktrace(\debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS));
        $this->enabledWatchers[$id] = \true;
        $phabelReturn = $id;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function enable($watcherId)
    {
        if (!\is_string($watcherId)) {
            if (!(\is_string($watcherId) || \is_object($watcherId) && \method_exists($watcherId, '__toString') || (\is_bool($watcherId) || \is_numeric($watcherId)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcherId) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcherId) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $watcherId = (string) $watcherId;
            }
        }
        try {
            $this->driver->enable($watcherId);
            $this->enabledWatchers[$watcherId] = \true;
        } catch (InvalidWatcherError $e) {
            throw new InvalidWatcherError($watcherId, $e->getMessage() . "\r\n\r\n" . $this->getTraces($watcherId));
        }
    }
    public function cancel($watcherId)
    {
        if (!\is_string($watcherId)) {
            if (!(\is_string($watcherId) || \is_object($watcherId) && \method_exists($watcherId, '__toString') || (\is_bool($watcherId) || \is_numeric($watcherId)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcherId) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcherId) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $watcherId = (string) $watcherId;
            }
        }
        $this->driver->cancel($watcherId);
        if (!isset($this->cancelTraces[$watcherId])) {
            $this->cancelTraces[$watcherId] = formatStacktrace(\debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS));
        }
        unset($this->enabledWatchers[$watcherId], $this->unreferencedWatchers[$watcherId]);
    }
    public function disable($watcherId)
    {
        if (!\is_string($watcherId)) {
            if (!(\is_string($watcherId) || \is_object($watcherId) && \method_exists($watcherId, '__toString') || (\is_bool($watcherId) || \is_numeric($watcherId)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcherId) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcherId) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $watcherId = (string) $watcherId;
            }
        }
        $this->driver->disable($watcherId);
        unset($this->enabledWatchers[$watcherId]);
    }
    public function reference($watcherId)
    {
        if (!\is_string($watcherId)) {
            if (!(\is_string($watcherId) || \is_object($watcherId) && \method_exists($watcherId, '__toString') || (\is_bool($watcherId) || \is_numeric($watcherId)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcherId) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcherId) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $watcherId = (string) $watcherId;
            }
        }
        try {
            $this->driver->reference($watcherId);
            unset($this->unreferencedWatchers[$watcherId]);
        } catch (InvalidWatcherError $e) {
            throw new InvalidWatcherError($watcherId, $e->getMessage() . "\r\n\r\n" . $this->getTraces($watcherId));
        }
    }
    public function unreference($watcherId)
    {
        if (!\is_string($watcherId)) {
            if (!(\is_string($watcherId) || \is_object($watcherId) && \method_exists($watcherId, '__toString') || (\is_bool($watcherId) || \is_numeric($watcherId)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcherId) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcherId) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $watcherId = (string) $watcherId;
            }
        }
        $this->driver->unreference($watcherId);
        $this->unreferencedWatchers[$watcherId] = \true;
    }
    public function setErrorHandler(callable $callback = null)
    {
        return $this->driver->setErrorHandler($callback);
    }
    /** @inheritdoc */
    public function getHandle()
    {
        $this->driver->getHandle();
    }
    public function dump()
    {
        $dump = "Enabled, referenced watchers keeping the loop running: ";
        foreach ($this->enabledWatchers as $watcher => $_) {
            if (isset($this->unreferencedWatchers[$watcher])) {
                continue;
            }
            $dump .= "Watcher ID: " . $watcher . "\r\n";
            $dump .= $this->getCreationTrace($watcher);
            $dump .= "\r\n\r\n";
        }
        $phabelReturn = \rtrim($dump);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function getInfo()
    {
        $phabelReturn = $this->driver->getInfo();
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function __debugInfo()
    {
        return $this->driver->__debugInfo();
    }
    public function now()
    {
        $phabelReturn = $this->driver->now();
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    protected function error(\Throwable $exception)
    {
        $this->driver->error($exception);
    }
    /**
     * @inheritdoc
     *
     * @return void
     */
    protected function activate(array $watchers)
    {
        // nothing to do in a decorator
    }
    /**
     * @inheritdoc
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
        // nothing to do in a decorator
    }
    /**
     * @inheritdoc
     *
     * @return void
     */
    protected function deactivate(Watcher $watcher)
    {
        // nothing to do in a decorator
    }
    private function getTraces($watcherId)
    {
        if (!\is_string($watcherId)) {
            if (!(\is_string($watcherId) || \is_object($watcherId) && \method_exists($watcherId, '__toString') || (\is_bool($watcherId) || \is_numeric($watcherId)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcherId) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcherId) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $watcherId = (string) $watcherId;
            }
        }
        $phabelReturn = "Creation Trace:\r\n" . $this->getCreationTrace($watcherId) . '

Cancellation Trace:
' . $this->getCancelTrace($watcherId);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    private function getCreationTrace($watcher)
    {
        if (!\is_string($watcher)) {
            if (!(\is_string($watcher) || \is_object($watcher) && \method_exists($watcher, '__toString') || (\is_bool($watcher) || \is_numeric($watcher)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcher) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcher) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $watcher = (string) $watcher;
            }
        }
        if (!isset($this->creationTraces[$watcher])) {
            $phabelReturn = 'No creation trace, yet.';
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = $this->creationTraces[$watcher];
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    private function getCancelTrace($watcher)
    {
        if (!\is_string($watcher)) {
            if (!(\is_string($watcher) || \is_object($watcher) && \method_exists($watcher, '__toString') || (\is_bool($watcher) || \is_numeric($watcher)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcher) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcher) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $watcher = (string) $watcher;
            }
        }
        if (!isset($this->cancelTraces[$watcher])) {
            $phabelReturn = 'No cancellation trace, yet.';
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = $this->cancelTraces[$watcher];
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
