<?php

namespace Phabel\Amp;

use Phabel\Amp\Loop\Driver;
use Phabel\Amp\Loop\DriverFactory;
use Phabel\Amp\Loop\InvalidWatcherError;
use Phabel\Amp\Loop\UnsupportedFeatureException;
use Phabel\Amp\Loop\Watcher;
/**
 * Accessor to allow global access to the event loop.
 *
 * @see \Amp\Loop\Driver
 */
final class Loop
{
    /**
     * @var Driver
     */
    private static $driver;
    /**
     * Disable construction as this is a static class.
     */
    private function __construct()
    {
        // intentionally left blank
    }
    /**
     * Sets the driver to be used for `Loop::run()`.
     *
     * @param Driver $driver
     *
     * @return void
     */
    public static function set(Driver $driver)
    {
        try {
            self::$driver = new PhabelAnonymousClassf66c86d93faee87e6cff006ff0599931355803a1ef8fc34109e457bffbf5fc844();
            \gc_collect_cycles();
        } finally {
            self::$driver = $driver;
        }
    }
    /**
     * Run the event loop and optionally execute a callback within the scope of it.
     *
     * The loop MUST continue to run until it is either stopped explicitly, no referenced watchers exist anymore, or an
     * exception is thrown that cannot be handled. Exceptions that cannot be handled are exceptions thrown from an
     * error handler or exceptions that would be passed to an error handler but none exists to handle them.
     *
     * @param callable|null $callback The callback to execute.
     *
     * @return void
     */
    public static function run(callable $callback = null)
    {
        if ($callback) {
            self::$driver->defer($callback);
        }
        self::$driver->run();
    }
    /**
     * Stop the event loop.
     *
     * When an event loop is stopped, it continues with its current tick and exits the loop afterwards. Multiple calls
     * to stop MUST be ignored and MUST NOT raise an exception.
     *
     * @return void
     */
    public static function stop()
    {
        self::$driver->stop();
    }
    /**
     * Defer the execution of a callback.
     *
     * The deferred callable MUST be executed before any other type of watcher in a tick. Order of enabling MUST be
     * preserved when executing the callbacks.
     *
     * The created watcher MUST immediately be marked as enabled, but only be activated (i.e. callback can be called)
     * right before the next tick. Callbacks of watchers MUST NOT be called in the tick they were enabled.
     *
     * @param callable(string $watcherId, mixed $data) $callback The callback to defer. The `$watcherId` will be
     *     invalidated before the callback call.
     * @param mixed $data Arbitrary data given to the callback function as the `$data` parameter.
     *
     * @return string An unique identifier that can be used to cancel, enable or disable the watcher.
     */
    public static function defer(callable $callback, $data = null)
    {
        $phabelReturn = self::$driver->defer($callback, $data);
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
     * Delay the execution of a callback.
     *
     * The delay is a minimum and approximate, accuracy is not guaranteed. Order of calls MUST be determined by which
     * timers expire first, but timers with the same expiration time MAY be executed in any order.
     *
     * The created watcher MUST immediately be marked as enabled, but only be activated (i.e. callback can be called)
     * right before the next tick. Callbacks of watchers MUST NOT be called in the tick they were enabled.
     *
     * @param int   $delay The amount of time, in milliseconds, to delay the execution for.
     * @param callable(string $watcherId, mixed $data) $callback The callback to delay. The `$watcherId` will be
     *     invalidated before the callback call.
     * @param mixed $data Arbitrary data given to the callback function as the `$data` parameter.
     *
     * @return string An unique identifier that can be used to cancel, enable or disable the watcher.
     */
    public static function delay($delay, callable $callback, $data = null)
    {
        if (!\is_int($delay)) {
            if (!(\is_bool($delay) || \is_numeric($delay))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($delay) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($delay) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $delay = (int) $delay;
            }
        }
        $phabelReturn = self::$driver->delay($delay, $callback, $data);
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
     * Repeatedly execute a callback.
     *
     * The interval between executions is a minimum and approximate, accuracy is not guaranteed. Order of calls MUST be
     * determined by which timers expire first, but timers with the same expiration time MAY be executed in any order.
     * The first execution is scheduled after the first interval period.
     *
     * The created watcher MUST immediately be marked as enabled, but only be activated (i.e. callback can be called)
     * right before the next tick. Callbacks of watchers MUST NOT be called in the tick they were enabled.
     *
     * @param int   $interval The time interval, in milliseconds, to wait between executions.
     * @param callable(string $watcherId, mixed $data) $callback The callback to repeat.
     * @param mixed $data Arbitrary data given to the callback function as the `$data` parameter.
     *
     * @return string An unique identifier that can be used to cancel, enable or disable the watcher.
     */
    public static function repeat($interval, callable $callback, $data = null)
    {
        if (!\is_int($interval)) {
            if (!(\is_bool($interval) || \is_numeric($interval))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($interval) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($interval) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $interval = (int) $interval;
            }
        }
        $phabelReturn = self::$driver->repeat($interval, $callback, $data);
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
     * Execute a callback when a stream resource becomes readable or is closed for reading.
     *
     * Warning: Closing resources locally, e.g. with `fclose`, might not invoke the callback. Be sure to `cancel` the
     * watcher when closing the resource locally. Drivers MAY choose to notify the user if there are watchers on invalid
     * resources, but are not required to, due to the high performance impact. Watchers on closed resources are
     * therefore undefined behavior.
     *
     * Multiple watchers on the same stream MAY be executed in any order.
     *
     * The created watcher MUST immediately be marked as enabled, but only be activated (i.e. callback can be called)
     * right before the next tick. Callbacks of watchers MUST NOT be called in the tick they were enabled.
     *
     * @param resource $stream The stream to monitor.
     * @param callable(string $watcherId, resource $stream, mixed $data) $callback The callback to execute.
     * @param mixed    $data Arbitrary data given to the callback function as the `$data` parameter.
     *
     * @return string An unique identifier that can be used to cancel, enable or disable the watcher.
     */
    public static function onReadable($stream, callable $callback, $data = null)
    {
        $phabelReturn = self::$driver->onReadable($stream, $callback, $data);
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
     * Execute a callback when a stream resource becomes writable or is closed for writing.
     *
     * Warning: Closing resources locally, e.g. with `fclose`, might not invoke the callback. Be sure to `cancel` the
     * watcher when closing the resource locally. Drivers MAY choose to notify the user if there are watchers on invalid
     * resources, but are not required to, due to the high performance impact. Watchers on closed resources are
     * therefore undefined behavior.
     *
     * Multiple watchers on the same stream MAY be executed in any order.
     *
     * The created watcher MUST immediately be marked as enabled, but only be activated (i.e. callback can be called)
     * right before the next tick. Callbacks of watchers MUST NOT be called in the tick they were enabled.
     *
     * @param resource $stream The stream to monitor.
     * @param callable(string $watcherId, resource $stream, mixed $data) $callback The callback to execute.
     * @param mixed    $data Arbitrary data given to the callback function as the `$data` parameter.
     *
     * @return string An unique identifier that can be used to cancel, enable or disable the watcher.
     */
    public static function onWritable($stream, callable $callback, $data = null)
    {
        $phabelReturn = self::$driver->onWritable($stream, $callback, $data);
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
     * Execute a callback when a signal is received.
     *
     * Warning: Installing the same signal on different instances of this interface is deemed undefined behavior.
     * Implementations MAY try to detect this, if possible, but are not required to. This is due to technical
     * limitations of the signals being registered globally per process.
     *
     * Multiple watchers on the same signal MAY be executed in any order.
     *
     * The created watcher MUST immediately be marked as enabled, but only be activated (i.e. callback can be called)
     * right before the next tick. Callbacks of watchers MUST NOT be called in the tick they were enabled.
     *
     * @param int   $signo The signal number to monitor.
     * @param callable(string $watcherId, int $signo, mixed $data) $callback The callback to execute.
     * @param mixed $data Arbitrary data given to the callback function as the $data parameter.
     *
     * @return string An unique identifier that can be used to cancel, enable or disable the watcher.
     *
     * @throws UnsupportedFeatureException If signal handling is not supported.
     */
    public static function onSignal($signo, callable $callback, $data = null)
    {
        if (!\is_int($signo)) {
            if (!(\is_bool($signo) || \is_numeric($signo))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($signo) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($signo) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $signo = (int) $signo;
            }
        }
        $phabelReturn = self::$driver->onSignal($signo, $callback, $data);
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
     * Enable a watcher to be active starting in the next tick.
     *
     * Watchers MUST immediately be marked as enabled, but only be activated (i.e. callbacks can be called) right before
     * the next tick. Callbacks of watchers MUST NOT be called in the tick they were enabled.
     *
     * @param string $watcherId The watcher identifier.
     *
     * @return void
     *
     * @throws InvalidWatcherError If the watcher identifier is invalid.
     */
    public static function enable($watcherId)
    {
        if (!\is_string($watcherId)) {
            if (!(\is_string($watcherId) || \is_object($watcherId) && \method_exists($watcherId, '__toString') || (\is_bool($watcherId) || \is_numeric($watcherId)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcherId) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcherId) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $watcherId = (string) $watcherId;
            }
        }
        self::$driver->enable($watcherId);
    }
    /**
     * Disable a watcher immediately.
     *
     * A watcher MUST be disabled immediately, e.g. if a defer watcher disables a later defer watcher, the second defer
     * watcher isn't executed in this tick.
     *
     * Disabling a watcher MUST NOT invalidate the watcher. Calling this function MUST NOT fail, even if passed an
     * invalid watcher.
     *
     * @param string $watcherId The watcher identifier.
     *
     * @return void
     */
    public static function disable($watcherId)
    {
        if (!\is_string($watcherId)) {
            if (!(\is_string($watcherId) || \is_object($watcherId) && \method_exists($watcherId, '__toString') || (\is_bool($watcherId) || \is_numeric($watcherId)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcherId) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcherId) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $watcherId = (string) $watcherId;
            }
        }
        if (\PHP_VERSION_ID < 70200 && !isset(self::$driver)) {
            // Prior to PHP 7.2, self::$driver may be unset during destruct.
            // See https://github.com/amphp/amp/issues/212.
            return;
        }
        self::$driver->disable($watcherId);
    }
    /**
     * Cancel a watcher.
     *
     * This will detatch the event loop from all resources that are associated to the watcher. After this operation the
     * watcher is permanently invalid. Calling this function MUST NOT fail, even if passed an invalid watcher.
     *
     * @param string $watcherId The watcher identifier.
     *
     * @return void
     */
    public static function cancel($watcherId)
    {
        if (!\is_string($watcherId)) {
            if (!(\is_string($watcherId) || \is_object($watcherId) && \method_exists($watcherId, '__toString') || (\is_bool($watcherId) || \is_numeric($watcherId)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcherId) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcherId) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $watcherId = (string) $watcherId;
            }
        }
        if (\PHP_VERSION_ID < 70200 && !isset(self::$driver)) {
            // Prior to PHP 7.2, self::$driver may be unset during destruct.
            // See https://github.com/amphp/amp/issues/212.
            return;
        }
        self::$driver->cancel($watcherId);
    }
    /**
     * Reference a watcher.
     *
     * This will keep the event loop alive whilst the watcher is still being monitored. Watchers have this state by
     * default.
     *
     * @param string $watcherId The watcher identifier.
     *
     * @return void
     *
     * @throws InvalidWatcherError If the watcher identifier is invalid.
     */
    public static function reference($watcherId)
    {
        if (!\is_string($watcherId)) {
            if (!(\is_string($watcherId) || \is_object($watcherId) && \method_exists($watcherId, '__toString') || (\is_bool($watcherId) || \is_numeric($watcherId)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcherId) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcherId) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $watcherId = (string) $watcherId;
            }
        }
        self::$driver->reference($watcherId);
    }
    /**
     * Unreference a watcher.
     *
     * The event loop should exit the run method when only unreferenced watchers are still being monitored. Watchers
     * are all referenced by default.
     *
     * @param string $watcherId The watcher identifier.
     *
     * @return void
     */
    public static function unreference($watcherId)
    {
        if (!\is_string($watcherId)) {
            if (!(\is_string($watcherId) || \is_object($watcherId) && \method_exists($watcherId, '__toString') || (\is_bool($watcherId) || \is_numeric($watcherId)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcherId) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcherId) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $watcherId = (string) $watcherId;
            }
        }
        if (\PHP_VERSION_ID < 70200 && !isset(self::$driver)) {
            // Prior to PHP 7.2, self::$driver may be unset during destruct.
            // See https://github.com/amphp/amp/issues/212.
            return;
        }
        self::$driver->unreference($watcherId);
    }
    /**
     * Returns the current loop time in millisecond increments. Note this value does not necessarily correlate to
     * wall-clock time, rather the value returned is meant to be used in relative comparisons to prior values returned
     * by this method (intervals, expiration calculations, etc.) and is only updated once per loop tick.
     *
     * @return int
     */
    public static function now()
    {
        $phabelReturn = self::$driver->now();
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
     * Stores information in the loop bound registry.
     *
     * Stored information is package private. Packages MUST NOT retrieve the stored state of other packages. Packages
     * MUST use their namespace as prefix for keys. They may do so by using `SomeClass::class` as key.
     *
     * If packages want to expose loop bound state to consumers other than the package, they SHOULD provide a dedicated
     * interface for that purpose instead of sharing the storage key.
     *
     * @param string $key The namespaced storage key.
     * @param mixed  $value The value to be stored.
     *
     * @return void
     */
    public static function setState($key, $value)
    {
        if (!\is_string($key)) {
            if (!(\is_string($key) || \is_object($key) && \method_exists($key, '__toString') || (\is_bool($key) || \is_numeric($key)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($key) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($key) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $key = (string) $key;
            }
        }
        self::$driver->setState($key, $value);
    }
    /**
     * Gets information stored bound to the loop.
     *
     * Stored information is package private. Packages MUST NOT retrieve the stored state of other packages. Packages
     * MUST use their namespace as prefix for keys. They may do so by using `SomeClass::class` as key.
     *
     * If packages want to expose loop bound state to consumers other than the package, they SHOULD provide a dedicated
     * interface for that purpose instead of sharing the storage key.
     *
     * @param string $key The namespaced storage key.
     *
     * @return mixed The previously stored value or `null` if it doesn't exist.
     */
    public static function getState($key)
    {
        if (!\is_string($key)) {
            if (!(\is_string($key) || \is_object($key) && \method_exists($key, '__toString') || (\is_bool($key) || \is_numeric($key)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($key) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($key) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $key = (string) $key;
            }
        }
        return self::$driver->getState($key);
    }
    /**
     * Set a callback to be executed when an error occurs.
     *
     * The callback receives the error as the first and only parameter. The return value of the callback gets ignored.
     * If it can't handle the error, it MUST throw the error. Errors thrown by the callback or during its invocation
     * MUST be thrown into the `run` loop and stop the driver.
     *
     * Subsequent calls to this method will overwrite the previous handler.
     *
     * @param callable(\Throwable $error)|null $callback The callback to execute. `null` will clear the
     *     current handler.
     *
     * @return callable(\Throwable $error)|null The previous handler, `null` if there was none.
     */
    public static function setErrorHandler(callable $callback = null)
    {
        return self::$driver->setErrorHandler($callback);
    }
    /**
     * Retrieve an associative array of information about the event loop driver.
     *
     * The returned array MUST contain the following data describing the driver's currently registered watchers:
     *
     *     [
     *         "defer"            => ["enabled" => int, "disabled" => int],
     *         "delay"            => ["enabled" => int, "disabled" => int],
     *         "repeat"           => ["enabled" => int, "disabled" => int],
     *         "on_readable"      => ["enabled" => int, "disabled" => int],
     *         "on_writable"      => ["enabled" => int, "disabled" => int],
     *         "on_signal"        => ["enabled" => int, "disabled" => int],
     *         "enabled_watchers" => ["referenced" => int, "unreferenced" => int],
     *         "running"          => bool
     *     ];
     *
     * Implementations MAY optionally add more information in the array but at minimum the above `key => value` format
     * MUST always be provided.
     *
     * @return array Statistics about the loop in the described format.
     */
    public static function getInfo()
    {
        $phabelReturn = self::$driver->getInfo();
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Retrieve the event loop driver that is in scope.
     *
     * @return Driver
     */
    public static function get()
    {
        $phabelReturn = self::$driver;
        if (!$phabelReturn instanceof Driver) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Driver, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
if (!\class_exists(PhabelAnonymousClassf66c86d93faee87e6cff006ff0599931355803a1ef8fc34109e457bffbf5fc844::class)) {
    class PhabelAnonymousClassf66c86d93faee87e6cff006ff0599931355803a1ef8fc34109e457bffbf5fc844 extends Driver implements \Phabel\Target\Php70\AnonymousClass\AnonymousClassInterface
    {
        /**
         * @return void
         */
        protected function activate(array $watchers)
        {
            throw new \Error("Can't activate watcher during garbage collection.");
        }
        /**
         * @return void
         */
        protected function dispatch($blocking)
        {
            if (!\is_bool($blocking)) {
                if (!(\is_bool($blocking) || \is_numeric($blocking) || \is_string($blocking))) {
                    throw new \TypeError(Driver::class . '@anonymous:' . __FUNCTION__ . '(): Argument #1 ($blocking) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($blocking) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $blocking = (bool) $blocking;
                }
            }
            throw new \Error("Can't dispatch during garbage collection.");
        }
        /**
         * @return void
         */
        protected function deactivate(Watcher $watcher)
        {
            // do nothing
        }
        public function getHandle()
        {
            return null;
        }
        public static function getPhabelOriginalName()
        {
            return Driver::class . '@anonymous';
        }
    }
}
// Default factory, don't move this to a file loaded by the composer "files" autoload mechanism, otherwise custom
// implementations might have issues setting a default loop, because it's overridden by us then.
// @codeCoverageIgnoreStart
Loop::set((new DriverFactory())->create());
// @codeCoverageIgnoreEnd
