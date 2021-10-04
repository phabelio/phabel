<?php

namespace Phabel\Target\Php71;

use Closure;
use Phabel\Plugin;
use PhabelVendor\PhpParser\Node\Expr;
use PhabelVendor\PhpParser\Node\Expr\StaticCall;
use PhabelVendor\PhpParser\Node\Name;
use ReflectionClass;
use ReflectionFunction;
/**
 * Polyfills Closure::fromCallable.
 */
class ClosureFromCallable extends Plugin
{
    public function enter(StaticCall $staticCall) : ?StaticCall
    {
        if (!$staticCall->class instanceof Name || self::getFqdn($staticCall->class) !== Closure::class) {
            return null;
        }
        if ($staticCall->name instanceof Expr) {
            return self::callPoly('proxy', $staticCall->name, ...$staticCall->args);
        } elseif (\strtolower($staticCall->name->name) === 'fromcallable') {
            return self::callPoly('fromCallable', $staticCall->args[0]);
        }
        return null;
    }
    public static function proxy(string $method, ...$args)
    {
        if (\strtolower($method) === 'fromcallable') {
            return self::fromCallable($args[0]);
        }
        return Closure::$method(...$args);
    }
    /**
     * Create closure from callable.
     *
     * @param callable $callable
     * @return Closure
     */
    public static function fromCallable($callable) : Closure
    {
        if ($callable instanceof Closure) {
            return $callable;
        }
        if (\Phabel\Target\Php72\Polyfill::is_object($callable)) {
            $callable = [$callable, '__invoke'];
        }
        if (\is_array($callable)) {
            $method = (new ReflectionClass($callable[0]))->getMethod($callable[1]);
            return \is_string($callable[0]) ? $method->getClosure() : $method->getClosure($callable[0]);
        }
        return (new ReflectionFunction($callable))->getClosure();
    }
}
