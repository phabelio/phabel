<?php

namespace Phabel\Amp;

use Phabel\React\Promise\PromiseInterface as ReactPromise;
use function Phabel\Amp\Promise\rethrow;
/**
 * A cancellation token source provides a mechanism to cancel operations.
 *
 * Cancellation of operation works by creating a cancellation token source and passing the corresponding token when
 * starting the operation. To cancel the operation, invoke `CancellationTokenSource::cancel()`.
 *
 * Any operation can decide what to do on a cancellation request, it has "don't care" semantics. An operation SHOULD be
 * aborted, but MAY continue. Example: A DNS client might continue to receive and cache the response, as the query has
 * been sent anyway. An HTTP client would usually close a connection, but might not do so in case a response is close to
 * be fully received to reuse the connection.
 *
 * **Example**
 *
 * ```php
 * $tokenSource = new CancellationTokenSource;
 * $token = $tokenSource->getToken();
 *
 * $response = yield $httpClient->request("https://example.com/stream", $token);
 * $responseBody = $response->getBody();
 *
 * while (($chunk = yield $response->read()) !== null) {
 *     // consume $chunk
 *
 *     if ($noLongerInterested) {
 *         $cancellationTokenSource->cancel();
 *         break;
 *     }
 * }
 * ```
 *
 * @see CancellationToken
 * @see CancelledException
 */
final class CancellationTokenSource
{
    /** @var CancellationToken */
    private $token;
    /** @var callable|null */
    private $onCancel;
    public function __construct()
    {
        $onCancel = null;
        $this->token = new PhabelAnonymousClass867203916b829f7de24ce3f00173dbbad106acb640e8804052daf7588402a3bd2($onCancel);
        $this->onCancel = $onCancel;
    }
    public function getToken()
    {
        $phabelReturn = $this->token;
        if (!$phabelReturn instanceof CancellationToken) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type CancellationToken, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param \Throwable|null $previous Exception to be used as the previous exception to CancelledException.
     *
     * @return void
     */
    public function cancel(\Throwable $previous = null)
    {
        if ($this->onCancel === null) {
            return;
        }
        $onCancel = $this->onCancel;
        $this->onCancel = null;
        $onCancel(new CancelledException($previous));
    }
}
if (!\class_exists(PhabelAnonymousClass867203916b829f7de24ce3f00173dbbad106acb640e8804052daf7588402a3bd2::class)) {
    class PhabelAnonymousClass867203916b829f7de24ce3f00173dbbad106acb640e8804052daf7588402a3bd2 implements CancellationToken, \Phabel\Target\Php70\AnonymousClass\AnonymousClassInterface
    {
        /** @var string */
        private $nextId = "a";
        /** @var callable[] */
        private $callbacks = [];
        /** @var \Throwable|null */
        private $exception;
        /**
         * @param mixed $onCancel
         * @param-out callable $onCancel
         */
        public function __construct(&$onCancel)
        {
            /** @psalm-suppress MissingClosureReturnType We still support PHP 7.0 */
            $onCancel = function (\Throwable $exception) {
                $this->exception = $exception;
                $callbacks = $this->callbacks;
                $this->callbacks = [];
                foreach ($callbacks as $callback) {
                    $this->invokeCallback($callback);
                }
            };
        }
        /**
         * @param callable $callback
         *
         * @return void
         */
        private function invokeCallback(callable $callback)
        {
            // No type declaration to prevent exception outside the try!
            try {
                /** @var mixed $result */
                $result = $callback($this->exception);
                if ($result instanceof \Generator) {
                    /** @psalm-var \Generator<mixed, Promise|ReactPromise|(Promise|ReactPromise)[], mixed, mixed> $result */
                    $result = new Coroutine($result);
                }
                if ($result instanceof Promise || $result instanceof ReactPromise) {
                    rethrow($result);
                }
            } catch (\Exception $exception) {
                Loop::defer(static function () use($exception) {
                    throw $exception;
                });
            } catch (\Error $exception) {
                Loop::defer(static function () use($exception) {
                    throw $exception;
                });
            }
        }
        public function subscribe(callable $callback)
        {
            $id = $this->nextId++;
            if ($this->exception) {
                $this->invokeCallback($callback);
            } else {
                $this->callbacks[$id] = $callback;
            }
            $phabelReturn = $id;
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(CancellationToken::class . '@anonymous:' . __FUNCTION__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        public function unsubscribe($id)
        {
            if (!\is_string($id)) {
                if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                    throw new \TypeError(CancellationToken::class . '@anonymous:' . __FUNCTION__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $id = (string) $id;
                }
            }
            unset($this->callbacks[$id]);
        }
        public function isRequested()
        {
            $phabelReturn = isset($this->exception);
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(CancellationToken::class . '@anonymous:' . __FUNCTION__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (bool) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        public function throwIfRequested()
        {
            if (isset($this->exception)) {
                throw $this->exception;
            }
        }
        public static function getPhabelOriginalName()
        {
            return CancellationToken::class . '@anonymous';
        }
    }
}
