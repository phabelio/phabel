<?php

namespace Phabel\Amp;

use Phabel\React\Promise\PromiseInterface as ReactPromise;
/**
 * Returns a new function that wraps $callback in a promise/coroutine-aware function that automatically runs
 * Generators as coroutines. The returned function always returns a promise when invoked. Errors have to be handled
 * by the callback caller or they will go unnoticed.
 *
 * Use this function to create a coroutine-aware callable for a promise-aware callback caller.
 *
 * @template TReturn
 * @template TPromise
 * @template TGeneratorReturn
 * @template TGeneratorPromise
 *
 * @template TGenerator as TGeneratorReturn|Promise<TGeneratorPromise>
 * @template T as TReturn|Promise<TPromise>|\Generator<mixed, mixed, mixed, TGenerator>
 *
 * @formatter:off
 *
 * @param callable(...mixed): T $callback
 *
 * @return callable
 * @psalm-return (T is Promise ? (callable(mixed...): Promise<TPromise>) : (T is \Generator ? (TGenerator is Promise ? (callable(mixed...): Promise<TGeneratorPromise>) : (callable(mixed...): Promise<TGeneratorReturn>)) : (callable(mixed...): Promise<TReturn>)))
 *
 * @formatter:on
 *
 * @see asyncCoroutine()
 *
 * @psalm-suppress InvalidReturnType
 */
function coroutine(callable $callback)
{
    $phabelReturn = static function (...$args) use($callback) {
        $phabelReturn = call($callback, ...$args);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    };
    if (!\is_callable($phabelReturn)) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    /** @psalm-suppress InvalidReturnStatement */
    return $phabelReturn;
}
/**
 * Returns a new function that wraps $callback in a promise/coroutine-aware function that automatically runs
 * Generators as coroutines. The returned function always returns void when invoked. Errors are forwarded to the
 * loop's error handler using `Amp\Promise\rethrow()`.
 *
 * Use this function to create a coroutine-aware callable for a non-promise-aware callback caller.
 *
 * @param callable(...mixed): mixed $callback
 *
 * @return callable
 * @psalm-return callable(mixed...): void
 *
 * @see coroutine()
 */
function asyncCoroutine(callable $callback)
{
    $phabelReturn = static function (...$args) use($callback) {
        Promise\rethrow(call($callback, ...$args));
    };
    if (!\is_callable($phabelReturn)) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Calls the given function, always returning a promise. If the function returns a Generator, it will be run as a
 * coroutine. If the function throws, a failed promise will be returned.
 *
 * @template TReturn
 * @template TPromise
 * @template TGeneratorReturn
 * @template TGeneratorPromise
 *
 * @template TGenerator as TGeneratorReturn|Promise<TGeneratorPromise>
 * @template T as TReturn|Promise<TPromise>|\Generator<mixed, mixed, mixed, TGenerator>
 *
 * @formatter:off
 *
 * @param callable(...mixed): T $callback
 * @param mixed ...$args Arguments to pass to the function.
 *
 * @return Promise
 * @psalm-return (T is Promise ? Promise<TPromise> : (T is \Generator ? (TGenerator is Promise ? Promise<TGeneratorPromise> : Promise<TGeneratorReturn>) : Promise<TReturn>))
 *
 * @formatter:on
 */
function call(callable $callback, ...$args)
{
    try {
        $result = $callback(...$args);
    } catch (\Exception $exception) {
        $phabelReturn = new Failure($exception);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    } catch (\Error $exception) {
        $phabelReturn = new Failure($exception);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    if ($result instanceof \Generator) {
        $phabelReturn = new Coroutine($result);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    if ($result instanceof Promise) {
        $phabelReturn = $result;
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    if ($result instanceof ReactPromise) {
        $phabelReturn = Promise\adapt($result);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    $phabelReturn = new Success($result);
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Calls the given function. If the function returns a Generator, it will be run as a coroutine. If the function
 * throws or returns a failing promise, the failure is forwarded to the loop error handler.
 *
 * @param callable(...mixed): mixed $callback
 * @param mixed ...$args Arguments to pass to the function.
 *
 * @return void
 */
function asyncCall(callable $callback, ...$args)
{
    Promise\rethrow(call($callback, ...$args));
}
/**
 * Sleeps for the specified number of milliseconds.
 *
 * @param int $milliseconds
 *
 * @return Delayed
 */
function delay($milliseconds)
{
    if (!\is_int($milliseconds)) {
        if (!(\is_bool($milliseconds) || \is_numeric($milliseconds))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($milliseconds) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($milliseconds) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        } else {
            $milliseconds = (int) $milliseconds;
        }
    }
    $phabelReturn = new Delayed($milliseconds);
    if (!$phabelReturn instanceof Delayed) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Delayed, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Returns the current time relative to an arbitrary point in time.
 *
 * @return int Time in milliseconds.
 */
function getCurrentTime()
{
    $phabelReturn = Internal\getCurrentTime();
    if (!\is_int($phabelReturn)) {
        if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        } else {
            $phabelReturn = (int) $phabelReturn;
        }
    }
    return $phabelReturn;
}
namespace Phabel\Amp\Promise;

use Phabel\Amp\Deferred;
use Phabel\Amp\Loop;
use Phabel\Amp\MultiReasonException;
use Phabel\Amp\Promise;
use Phabel\Amp\Success;
use Phabel\Amp\TimeoutException;
use Phabel\React\Promise\PromiseInterface as ReactPromise;
use function Phabel\Amp\call;
use function Phabel\Amp\Internal\createTypeError;
/**
 * Registers a callback that will forward the failure reason to the event loop's error handler if the promise fails.
 *
 * Use this function if you neither return the promise nor handle a possible error yourself to prevent errors from
 * going entirely unnoticed.
 *
 * @param Promise|ReactPromise $promise Promise to register the handler on.
 *
 * @return void
 * @throws \TypeError If $promise is not an instance of \Amp\Promise or \React\Promise\PromiseInterface.
 *
 */
function rethrow($promise)
{
    if (!$promise instanceof Promise) {
        if ($promise instanceof ReactPromise) {
            $promise = adapt($promise);
        } else {
            throw createTypeError([Promise::class, ReactPromise::class], $promise);
        }
    }
    $promise->onResolve(static function ($exception) {
        if ($exception) {
            throw $exception;
        }
    });
}
/**
 * Runs the event loop until the promise is resolved. Should not be called within a running event loop.
 *
 * Use this function only in synchronous contexts to wait for an asynchronous operation. Use coroutines and yield to
 * await promise resolution in a fully asynchronous application instead.
 *
 * @template TPromise
 * @template T as Promise<TPromise>|ReactPromise
 *
 * @param Promise|ReactPromise $promise Promise to wait for.
 *
 * @return mixed Promise success value.
 *
 * @psalm-param T              $promise
 * @psalm-return (T is Promise ? TPromise : mixed)
 *
 * @throws \TypeError If $promise is not an instance of \Amp\Promise or \React\Promise\PromiseInterface.
 * @throws \Error If the event loop stopped without the $promise being resolved.
 * @throws \Throwable Promise failure reason.
 */
function wait($promise)
{
    if (!$promise instanceof Promise) {
        if ($promise instanceof ReactPromise) {
            $promise = adapt($promise);
        } else {
            throw createTypeError([Promise::class, ReactPromise::class], $promise);
        }
    }
    $resolved = \false;
    try {
        Loop::run(function () use(&$resolved, &$value, &$exception, $promise) {
            $promise->onResolve(function ($e, $v) use(&$resolved, &$value, &$exception) {
                Loop::stop();
                $resolved = \true;
                $exception = $e;
                $value = $v;
            });
        });
    } catch (\Exception $throwable) {
        throw new \Error("Loop exceptionally stopped without resolving the promise", 0, $throwable);
    } catch (\Error $throwable) {
        throw new \Error("Loop exceptionally stopped without resolving the promise", 0, $throwable);
    }
    if (!$resolved) {
        throw new \Error("Loop stopped without resolving the promise");
    }
    if ($exception) {
        throw $exception;
    }
    return $value;
}
/**
 * Creates an artificial timeout for any `Promise`.
 *
 * If the timeout expires before the promise is resolved, the returned promise fails with an instance of
 * `Amp\TimeoutException`.
 *
 * @template TReturn
 *
 * @param Promise<TReturn>|ReactPromise $promise Promise to which the timeout is applied.
 * @param int                           $timeout Timeout in milliseconds.
 *
 * @return Promise<TReturn>
 *
 * @throws \TypeError If $promise is not an instance of \Amp\Promise or \React\Promise\PromiseInterface.
 */
function timeout($promise, $timeout)
{
    if (!\is_int($timeout)) {
        if (!(\is_bool($timeout) || \is_numeric($timeout))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($timeout) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($timeout) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        } else {
            $timeout = (int) $timeout;
        }
    }
    if (!$promise instanceof Promise) {
        if ($promise instanceof ReactPromise) {
            $promise = adapt($promise);
        } else {
            throw createTypeError([Promise::class, ReactPromise::class], $promise);
        }
    }
    $deferred = new Deferred();
    $watcher = Loop::delay($timeout, static function () use(&$deferred) {
        $temp = $deferred;
        // prevent double resolve
        $deferred = null;
        $temp->fail(new TimeoutException());
    });
    Loop::unreference($watcher);
    $promise->onResolve(function () use(&$deferred, $promise, $watcher) {
        if ($deferred !== null) {
            Loop::cancel($watcher);
            $deferred->resolve($promise);
        }
    });
    $phabelReturn = $deferred->promise();
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Creates an artificial timeout for any `Promise`.
 *
 * If the promise is resolved before the timeout expires, the result is returned
 *
 * If the timeout expires before the promise is resolved, a default value is returned
 *
 * @template TReturn
 *
 * @param Promise<TReturn>|ReactPromise $promise Promise to which the timeout is applied.
 * @param int                           $timeout Timeout in milliseconds.
 * @param TReturn                       $default
 *
 * @return Promise<TReturn>
 *
 * @throws \TypeError If $promise is not an instance of \Amp\Promise or \React\Promise\PromiseInterface.
 */
function timeoutWithDefault($promise, $timeout, $default = null)
{
    if (!\is_int($timeout)) {
        if (!(\is_bool($timeout) || \is_numeric($timeout))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($timeout) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($timeout) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        } else {
            $timeout = (int) $timeout;
        }
    }
    $promise = timeout($promise, $timeout);
    $phabelReturn = call(static function () use($promise, $default) {
        try {
            return (yield $promise);
        } catch (TimeoutException $exception) {
            return $default;
        }
    });
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Adapts any object with a done(callable $onFulfilled, callable $onRejected) or then(callable $onFulfilled,
 * callable $onRejected) method to a promise usable by components depending on placeholders implementing
 * \AsyncInterop\Promise.
 *
 * @param object $promise Object with a done() or then() method.
 *
 * @return Promise Promise resolved by the $thenable object.
 *
 * @throws \Error If the provided object does not have a then() method.
 */
function adapt($promise)
{
    if (!\is_object($promise)) {
        throw new \Error("Object must be provided");
    }
    $deferred = new Deferred();
    if (\method_exists($promise, 'done')) {
        $promise->done([$deferred, 'resolve'], [$deferred, 'fail']);
    } elseif (\method_exists($promise, 'then')) {
        $promise->then([$deferred, 'resolve'], [$deferred, 'fail']);
    } else {
        throw new \Error("Object must have a 'then' or 'done' method");
    }
    $phabelReturn = $deferred->promise();
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Returns a promise that is resolved when all promises are resolved. The returned promise will not fail.
 * Returned promise succeeds with a two-item array delineating successful and failed promise results,
 * with keys identical and corresponding to the original given array.
 *
 * This function is the same as some() with the notable exception that it will never fail even
 * if all promises in the array resolve unsuccessfully.
 *
 * @template TValue
 *
 * @param Promise<TValue>[]|ReactPromise[] $promises
 *
 * @return Promise<array{0: \Throwable[], 1: TValue[]}>
 *
 * @throws \Error If a non-Promise is in the array.
 */
function any(array $promises)
{
    $phabelReturn = some($promises, 0);
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Returns a promise that succeeds when all promises succeed, and fails if any promise fails. Returned
 * promise succeeds with an array of values used to succeed each contained promise, with keys corresponding to
 * the array of promises.
 *
 * @param Promise[]|ReactPromise[]                             $promises Array of only promises.
 *
 * @return Promise
 *
 * @throws \Error If a non-Promise is in the array.
 *
 * @template TValue
 *
 * @psalm-param array<array-key, Promise<TValue>|ReactPromise> $promises
 * @psalm-assert array<array-key, Promise<TValue>|ReactPromise> $promises $promises
 * @psalm-return Promise<array<array-key, TValue>>
 */
function all(array $promises)
{
    if (empty($promises)) {
        $phabelReturn = new Success([]);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    $deferred = new Deferred();
    $result = $deferred->promise();
    $pending = \count($promises);
    $values = [];
    foreach ($promises as $key => $promise) {
        if ($promise instanceof ReactPromise) {
            $promise = adapt($promise);
        } elseif (!$promise instanceof Promise) {
            throw createTypeError([Promise::class, ReactPromise::class], $promise);
        }
        $values[$key] = null;
        // add entry to array to preserve order
        $promise->onResolve(function ($exception, $value) use(&$deferred, &$values, &$pending, $key) {
            if ($pending === 0) {
                return;
            }
            if ($exception) {
                $pending = 0;
                $deferred->fail($exception);
                $deferred = null;
                return;
            }
            $values[$key] = $value;
            if (0 === --$pending) {
                $deferred->resolve($values);
            }
        });
    }
    $phabelReturn = $result;
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Returns a promise that succeeds when the first promise succeeds, and fails only if all promises fail.
 *
 * @template TValue
 *
 * @param Promise<TValue>[]|ReactPromise[] $promises Array of only promises.
 *
 * @return Promise<TValue>
 *
 * @throws \Error If the array is empty or a non-Promise is in the array.
 */
function first(array $promises)
{
    if (empty($promises)) {
        throw new \Error("No promises provided");
    }
    $deferred = new Deferred();
    $result = $deferred->promise();
    $pending = \count($promises);
    $exceptions = [];
    foreach ($promises as $key => $promise) {
        if ($promise instanceof ReactPromise) {
            $promise = adapt($promise);
        } elseif (!$promise instanceof Promise) {
            throw createTypeError([Promise::class, ReactPromise::class], $promise);
        }
        $exceptions[$key] = null;
        // add entry to array to preserve order
        $promise->onResolve(function ($error, $value) use(&$deferred, &$exceptions, &$pending, $key) {
            if ($pending === 0) {
                return;
            }
            if (!$error) {
                $pending = 0;
                $deferred->resolve($value);
                $deferred = null;
                return;
            }
            $exceptions[$key] = $error;
            if (0 === --$pending) {
                $deferred->fail(new MultiReasonException($exceptions));
            }
        });
    }
    $phabelReturn = $result;
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Resolves with a two-item array delineating successful and failed Promise results.
 *
 * The returned promise will only fail if the given number of required promises fail.
 *
 * @template TValue
 *
 * @param Promise<TValue>[]|ReactPromise[] $promises Array of only promises.
 * @param int                              $required Number of promises that must succeed for the
 *     returned promise to succeed.
 *
 * @return Promise<array{0: \Throwable[], 1: TValue[]}>
 *
 * @throws \Error If a non-Promise is in the array.
 */
function some(array $promises, $required = 1)
{
    if (!\is_int($required)) {
        if (!(\is_bool($required) || \is_numeric($required))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($required) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($required) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        } else {
            $required = (int) $required;
        }
    }
    if ($required < 0) {
        throw new \Error("Number of promises required must be non-negative");
    }
    $pending = \count($promises);
    if ($required > $pending) {
        throw new \Error("Too few promises provided");
    }
    if (empty($promises)) {
        $phabelReturn = new Success([[], []]);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    $deferred = new Deferred();
    $result = $deferred->promise();
    $values = [];
    $exceptions = [];
    foreach ($promises as $key => $promise) {
        if ($promise instanceof ReactPromise) {
            $promise = adapt($promise);
        } elseif (!$promise instanceof Promise) {
            throw createTypeError([Promise::class, ReactPromise::class], $promise);
        }
        $values[$key] = $exceptions[$key] = null;
        // add entry to arrays to preserve order
        $promise->onResolve(static function ($exception, $value) use(&$values, &$exceptions, &$pending, $key, $required, $deferred) {
            if ($exception) {
                $exceptions[$key] = $exception;
                unset($values[$key]);
            } else {
                $values[$key] = $value;
                unset($exceptions[$key]);
            }
            if (0 === --$pending) {
                if (\count($values) < $required) {
                    $deferred->fail(new MultiReasonException($exceptions));
                } else {
                    $deferred->resolve([$exceptions, $values]);
                }
            }
        });
    }
    $phabelReturn = $result;
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Wraps a promise into another promise, altering the exception or result.
 *
 * @param Promise|ReactPromise $promise
 * @param callable             $callback
 *
 * @return Promise
 */
function wrap($promise, callable $callback)
{
    if ($promise instanceof ReactPromise) {
        $promise = adapt($promise);
    } elseif (!$promise instanceof Promise) {
        throw createTypeError([Promise::class, ReactPromise::class], $promise);
    }
    $deferred = new Deferred();
    $promise->onResolve(static function (\Throwable $exception = null, $result) use($deferred, $callback) {
        try {
            $result = $callback($exception, $result);
        } catch (\Exception $exception) {
            $deferred->fail($exception);
            return;
        } catch (\Error $exception) {
            $deferred->fail($exception);
            return;
        }
        $deferred->resolve($result);
    });
    $phabelReturn = $deferred->promise();
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
namespace Phabel\Amp\Iterator;

use Phabel\Amp\Delayed;
use Phabel\Amp\Emitter;
use Phabel\Amp\Iterator;
use Phabel\Amp\Producer;
use Phabel\Amp\Promise;
use function Phabel\Amp\call;
use function Phabel\Amp\coroutine;
use function Phabel\Amp\Internal\createTypeError;
/**
 * Creates an iterator from the given iterable, emitting the each value. The iterable may contain promises. If any
 * promise fails, the iterator will fail with the same reason.
 *
 * @param array|\Traversable $iterable Elements to emit.
 * @param int                $delay Delay between element emissions in milliseconds.
 *
 * @return Iterator
 *
 * @throws \TypeError If the argument is not an array or instance of \Traversable.
 */
function fromIterable($iterable, $delay = 0)
{
    if (!\is_int($delay)) {
        if (!(\is_bool($delay) || \is_numeric($delay))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($delay) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($delay) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        } else {
            $delay = (int) $delay;
        }
    }
    if (!$iterable instanceof \Traversable && !\is_array($iterable)) {
        throw createTypeError(["array", "Traversable"], $iterable);
    }
    if ($delay) {
        $phabelReturn = new Producer(static function (callable $emit) use($iterable, $delay) {
            foreach ($iterable as $value) {
                (yield new Delayed($delay));
                (yield $emit($value));
            }
        });
        if (!$phabelReturn instanceof Iterator) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Iterator, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    $phabelReturn = new Producer(static function (callable $emit) use($iterable) {
        foreach ($iterable as $value) {
            (yield $emit($value));
        }
    });
    if (!$phabelReturn instanceof Iterator) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Iterator, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * @template TValue
 * @template TReturn
 *
 * @param Iterator<TValue> $iterator
 * @param callable (TValue $value): TReturn $onEmit
 *
 * @return Iterator<TReturn>
 */
function map(Iterator $iterator, callable $onEmit)
{
    $phabelReturn = new Producer(static function (callable $emit) use($iterator, $onEmit) {
        while ((yield $iterator->advance())) {
            (yield $emit($onEmit($iterator->getCurrent())));
        }
    });
    if (!$phabelReturn instanceof Iterator) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Iterator, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * @template TValue
 *
 * @param Iterator<TValue> $iterator
 * @param callable(TValue $value):bool $filter
 *
 * @return Iterator<TValue>
 */
function filter(Iterator $iterator, callable $filter)
{
    $phabelReturn = new Producer(static function (callable $emit) use($iterator, $filter) {
        while ((yield $iterator->advance())) {
            if ($filter($iterator->getCurrent())) {
                (yield $emit($iterator->getCurrent()));
            }
        }
    });
    if (!$phabelReturn instanceof Iterator) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Iterator, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Creates an iterator that emits values emitted from any iterator in the array of iterators.
 *
 * @param Iterator[] $iterators
 *
 * @return Iterator
 */
function merge(array $iterators)
{
    $emitter = new Emitter();
    $result = $emitter->iterate();
    $coroutine = coroutine(static function (Iterator $iterator) use(&$emitter) {
        while ((yield $iterator->advance()) && $emitter !== null) {
            (yield $emitter->emit($iterator->getCurrent()));
        }
    });
    $coroutines = [];
    foreach ($iterators as $iterator) {
        if (!$iterator instanceof Iterator) {
            throw createTypeError([Iterator::class], $iterator);
        }
        $coroutines[] = $coroutine($iterator);
    }
    Promise\all($coroutines)->onResolve(static function ($exception) use(&$emitter) {
        if ($exception) {
            $emitter->fail($exception);
            $emitter = null;
        } else {
            $emitter->complete();
        }
    });
    $phabelReturn = $result;
    if (!$phabelReturn instanceof Iterator) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Iterator, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Concatenates the given iterators into a single iterator, emitting values from a single iterator at a time. The
 * prior iterator must complete before values are emitted from any subsequent iterators. Iterators are concatenated
 * in the order given (iteration order of the array).
 *
 * @param Iterator[] $iterators
 *
 * @return Iterator
 */
function concat(array $iterators)
{
    foreach ($iterators as $iterator) {
        if (!$iterator instanceof Iterator) {
            throw createTypeError([Iterator::class], $iterator);
        }
    }
    $emitter = new Emitter();
    $previous = [];
    $promise = Promise\all($previous);
    $coroutine = coroutine(static function (Iterator $iterator, callable $emit) {
        while ((yield $iterator->advance())) {
            (yield $emit($iterator->getCurrent()));
        }
    });
    foreach ($iterators as $iterator) {
        $emit = coroutine(static function ($value) use($emitter, $promise) {
            static $pending = \true, $failed = \false;
            if ($failed) {
                return;
            }
            if ($pending) {
                try {
                    (yield $promise);
                    $pending = \false;
                } catch (\Exception $exception) {
                    $failed = \true;
                    return;
                    // Prior iterator failed.
                } catch (\Error $exception) {
                    $failed = \true;
                    return;
                    // Prior iterator failed.
                }
            }
            (yield $emitter->emit($value));
        });
        $previous[] = $coroutine($iterator, $emit);
        $promise = Promise\all($previous);
    }
    $promise->onResolve(static function ($exception) use($emitter) {
        if ($exception) {
            $emitter->fail($exception);
            return;
        }
        $emitter->complete();
    });
    $phabelReturn = $emitter->iterate();
    if (!$phabelReturn instanceof Iterator) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Iterator, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Discards all remaining items and returns the number of discarded items.
 *
 * @template TValue
 *
 * @param Iterator               $iterator
 *
 * @return Promise
 *
 * @psalm-param Iterator<TValue> $iterator
 * @psalm-return Promise<int>
 */
function discard(Iterator $iterator)
{
    $phabelReturn = call(static function () use($iterator) {
        $count = 0;
        while ((yield $iterator->advance())) {
            $count++;
        }
        return $count;
    });
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Collects all items from an iterator into an array.
 *
 * @template TValue
 *
 * @param Iterator               $iterator
 *
 * @psalm-param Iterator<TValue> $iterator
 *
 * @return Promise
 * @psalm-return Promise<array<array-key, TValue>>
 */
function toArray(Iterator $iterator)
{
    $phabelReturn = call(static function () use($iterator) {
        /** @psalm-var list $array */
        $array = [];
        while ((yield $iterator->advance())) {
            $array[] = $iterator->getCurrent();
        }
        return $array;
    });
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
