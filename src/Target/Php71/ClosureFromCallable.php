<?php

namespace Phabel\Target\Php71;

use Closure;
use Phabel\Plugin;
use Phabel\PhpParser\Node\Expr;
use Phabel\PhpParser\Node\Expr\StaticCall;
use Phabel\PhpParser\Node\Name;
use ReflectionClass;
use ReflectionFunction;
/**
 * Polyfills Closure::fromCallable.
 */
class ClosureFromCallable extends Plugin
{
    public function enter(StaticCall $staticCall)
    {
        if (!$staticCall->class instanceof Name || self::getFqdn($staticCall->class) !== Closure::class) {
            $phabelReturn = null;
            if (!($phabelReturn instanceof StaticCall || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?StaticCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($staticCall->name instanceof Expr) {
            $phabelReturn = self::callPoly('proxy', $staticCall->name, ...$staticCall->args);
            if (!($phabelReturn instanceof StaticCall || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?StaticCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        } elseif (\strtolower($staticCall->name->name) === 'fromcallable') {
            $phabelReturn = self::callPoly('fromCallable', $staticCall->args[0]);
            if (!($phabelReturn instanceof StaticCall || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?StaticCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = null;
        if (!($phabelReturn instanceof StaticCall || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?StaticCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public static function proxy($method, ...$args)
    {
        if (!\is_string($method)) {
            if (!(\is_string($method) || \is_object($method) && \method_exists($method, '__toString') || (\is_bool($method) || \is_numeric($method)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($method) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($method) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $method = (string) $method;
        }
        if (\strtolower($method) === 'fromcallable') {
            return self::fromCallable($args[0]);
        }
        return \Phabel\Target\Php71\ClosureFromCallable::proxy($method, ...$args);
    }
    /**
     * Create closure from callable.
     *
     * @param callable $callable
     * @return Closure
     */
    public static function fromCallable($callable)
    {
        if ($callable instanceof Closure) {
            $phabelReturn = $callable;
            if (!$phabelReturn instanceof Closure) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Closure, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (\is_object($callable)) {
            $callable = [$callable, '__invoke'];
        }
        if (\is_array($callable)) {
            $method = (new ReflectionClass($callable[0]))->getMethod($callable[1]);
            $phabelReturn = \is_string($callable[0]) ? $method->getClosure() : $method->getClosure($callable[0]);
            if (!$phabelReturn instanceof Closure) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Closure, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = (new ReflectionFunction($callable))->getClosure();
        if (!$phabelReturn instanceof Closure) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Closure, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
