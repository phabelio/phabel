<?php

namespace Phabel\Amp;

// @codeCoverageIgnoreStart
if (\PHP_VERSION_ID < 70100) {
    /** @psalm-suppress DuplicateClass */
    trait CallableMaker
    {
        /** @var \ReflectionClass */
        private static $__reflectionClass;
        /** @var \ReflectionMethod[] */
        private static $__reflectionMethods = [];
        /**
         * Creates a callable from a protected or private instance method that may be invoked by callers requiring a
         * publicly invokable callback.
         *
         * @param string $method Instance method name.
         *
         * @return callable
         *
         * @psalm-suppress MixedInferredReturnType
         */
        private function callableFromInstanceMethod($method)
        {
            if (!\is_string($method)) {
                if (!(\is_string($method) || \is_object($method) && \method_exists($method, '__toString') || (\is_bool($method) || \is_numeric($method)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($method) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($method) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $method = (string) $method;
                }
            }
            if (!isset(self::$__reflectionMethods[$method])) {
                if (self::$__reflectionClass === null) {
                    self::$__reflectionClass = new \ReflectionClass(self::class);
                }
                self::$__reflectionMethods[$method] = self::$__reflectionClass->getMethod($method);
            }
            $phabelReturn = self::$__reflectionMethods[$method]->getClosure($this);
            if (!\is_callable($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        /**
         * Creates a callable from a protected or private static method that may be invoked by methods requiring a
         * publicly invokable callback.
         *
         * @param string $method Static method name.
         *
         * @return callable
         *
         * @psalm-suppress MixedInferredReturnType
         */
        private static function callableFromStaticMethod($method)
        {
            if (!\is_string($method)) {
                if (!(\is_string($method) || \is_object($method) && \method_exists($method, '__toString') || (\is_bool($method) || \is_numeric($method)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($method) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($method) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $method = (string) $method;
                }
            }
            if (!isset(self::$__reflectionMethods[$method])) {
                if (self::$__reflectionClass === null) {
                    self::$__reflectionClass = new \ReflectionClass(self::class);
                }
                self::$__reflectionMethods[$method] = self::$__reflectionClass->getMethod($method);
            }
            $phabelReturn = self::$__reflectionMethods[$method]->getClosure();
            if (!\is_callable($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
    }
} else {
    /** @psalm-suppress DuplicateClass */
    trait CallableMaker
    {
        /**
         * @deprecated Use \Closure::fromCallable() instead of this method in PHP 7.1.
         */
        private function callableFromInstanceMethod($method)
        {
            if (!\is_string($method)) {
                if (!(\is_string($method) || \is_object($method) && \method_exists($method, '__toString') || (\is_bool($method) || \is_numeric($method)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($method) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($method) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $method = (string) $method;
                }
            }
            $phabelReturn = \Phabel\Target\Php71\ClosureFromCallable::fromCallable([$this, $method]);
            if (!\is_callable($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        /**
         * @deprecated Use \Closure::fromCallable() instead of this method in PHP 7.1.
         */
        private static function callableFromStaticMethod($method)
        {
            if (!\is_string($method)) {
                if (!(\is_string($method) || \is_object($method) && \method_exists($method, '__toString') || (\is_bool($method) || \is_numeric($method)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($method) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($method) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $method = (string) $method;
                }
            }
            $phabelReturn = \Phabel\Target\Php71\ClosureFromCallable::fromCallable([self::class, $method]);
            if (!\is_callable($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
    }
}
// @codeCoverageIgnoreEnd
