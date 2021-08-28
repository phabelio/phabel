<?php

namespace Phabel\Amp;

use function Phabel\Amp\Internal\formatStacktrace;
/**
 * A TimeoutCancellationToken automatically requests cancellation after the timeout has elapsed.
 */
final class TimeoutCancellationToken implements CancellationToken
{
    /** @var string */
    private $watcher;
    /** @var CancellationToken */
    private $token;
    /**
     * @param int    $timeout Milliseconds until cancellation is requested.
     * @param string $message Message for TimeoutException. Default is "Operation timed out".
     */
    public function __construct($timeout, $message = "Operation timed out")
    {
        if (!\is_int($timeout)) {
            if (!(\is_bool($timeout) || \is_numeric($timeout))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($timeout) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($timeout) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $timeout = (int) $timeout;
            }
        }
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        $source = new CancellationTokenSource();
        $this->token = $source->getToken();
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);
        $this->watcher = Loop::delay($timeout, static function () use($source, $message, $trace) {
            $trace = formatStacktrace($trace);
            $source->cancel(new TimeoutException("{$message}\r\nTimeoutCancellationToken was created here:\r\n{$trace}"));
        });
        Loop::unreference($this->watcher);
    }
    /**
     * Cancels the delay watcher.
     */
    public function __destruct()
    {
        Loop::cancel($this->watcher);
    }
    /**
     * {@inheritdoc}
     */
    public function subscribe(callable $callback)
    {
        $phabelReturn = $this->token->subscribe($callback);
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
    public function unsubscribe($id)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        $this->token->unsubscribe($id);
    }
    /**
     * {@inheritdoc}
     */
    public function isRequested()
    {
        $phabelReturn = $this->token->isRequested();
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
    public function throwIfRequested()
    {
        $this->token->throwIfRequested();
    }
}
