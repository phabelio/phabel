<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhabelVendor\Symfony\Component\String;

/**
 * A string whose value is computed lazily by a callback.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class LazyString implements \Stringable, \JsonSerializable
{
    /**
     * @var (\Closure | string) $value
     */
    private $value;
    /**
     * @param (callable | array) $callback A callable or a [Closure, method] lazy-callable
     * @param mixed ...$arguments
     * @return static
     */
    public static function fromCallable($callback, ...$arguments)
    {
        if (!(\is_callable($callback) || \is_array($callback))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($callback) must be of type callable|array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($callback) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        foreach ($arguments as $phabelVariadicIndex => $phabelVariadic) {
            if (!\true) {
                throw new \TypeError(__METHOD__ . '(): Argument #' . (2 + $phabelVariadicIndex) . ' must be of type mixed, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($arguments) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
        }
        if (\is_array($callback) && !\is_callable($callback) && !(($callback[0] ?? null) instanceof \Closure || 2 < \count($callback))) {
            throw new \TypeError(\sprintf('Argument 1 passed to "%s()" must be a callable or a [Closure, method] lazy-callable, "%s" given.', __METHOD__, '[' . \implode(', ', \array_map('get_debug_type', $callback)) . ']'));
        }
        $lazyString = new static();
        $lazyString->value = static function () use(&$callback, &$arguments, &$value) : string {
            if (null !== $arguments) {
                if (!\is_callable($callback)) {
                    $callback[0] = $callback[0]();
                    $callback[1] = $callback[1] ?? '__invoke';
                }
                $value = $callback(...$arguments);
                $callback = self::getPrettyName($callback);
                $arguments = null;
            }
            return $value ?? '';
        };
        $phabelReturn = $lazyString;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param (bool | int | float | string | \Stringable) $value
     * @return static
     */
    public static function fromStringable($value)
    {
        if (!(\is_bool($value) || \is_int($value) || \is_float($value) || \is_string($value) || $value instanceof \Stringable)) {
            if (!(\is_string($value) || \is_object($value) && \method_exists($value, '__toString') || (\is_bool($value) || \is_numeric($value)))) {
                if (!(\is_bool($value) || \is_numeric($value))) {
                    if (!(\is_bool($value) || \is_numeric($value))) {
                        if (!(\is_bool($value) || \is_numeric($value) || \is_string($value))) {
                            throw new \TypeError(__METHOD__ . '(): Argument #1 ($value) must be of type Stringable|bool|int|float|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($value) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        } else {
                            $value = (bool) $value;
                        }
                    } else {
                        $value = (int) $value;
                    }
                } else {
                    $value = (double) $value;
                }
            } else {
                $value = (string) $value;
            }
        }
        if (\is_object($value)) {
            $phabelReturn = static::fromCallable([$value, '__toString']);
            if (!$phabelReturn instanceof static) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $lazyString = new static();
        $lazyString->value = (string) $value;
        $phabelReturn = $lazyString;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Tells whether the provided value can be cast to string.
     * @param mixed $value
     */
    public static final function isStringable($value) : bool
    {
        if (!\true) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($value) must be of type mixed, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($value) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return \is_string($value) || $value instanceof \Stringable || \is_scalar($value);
    }
    /**
     * Casts scalars and stringable objects to strings.
     *
     * @throws \TypeError When the provided value is not stringable
     * @param (\Stringable | bool | int | float | string) $value
     */
    public static final function resolve($value) : string
    {
        if (!($value instanceof \Stringable || \is_bool($value) || \is_int($value) || \is_float($value) || \is_string($value))) {
            if (!(\is_string($value) || \is_object($value) && \method_exists($value, '__toString') || (\is_bool($value) || \is_numeric($value)))) {
                if (!(\is_bool($value) || \is_numeric($value))) {
                    if (!(\is_bool($value) || \is_numeric($value))) {
                        if (!(\is_bool($value) || \is_numeric($value) || \is_string($value))) {
                            throw new \TypeError(__METHOD__ . '(): Argument #1 ($value) must be of type Stringable|bool|int|float|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($value) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        } else {
                            $value = (bool) $value;
                        }
                    } else {
                        $value = (int) $value;
                    }
                } else {
                    $value = (double) $value;
                }
            } else {
                $value = (string) $value;
            }
        }
        return $value;
    }
    /**
     *
     */
    public function __toString() : string
    {
        if (\is_string($this->value)) {
            return $this->value;
        }
        try {
            return $this->value = ($this->value)();
        } catch (\Throwable $e) {
            if (\TypeError::class === \get_class($e) && __FILE__ === $e->getFile()) {
                $type = \explode(', ', $e->getMessage());
                $type = \Phabel\Target\Php80\Polyfill::substr(\array_pop($type), 0, -\strlen(' returned'));
                $r = new \ReflectionFunction($this->value);
                $callback = $r->getStaticVariables()['callback'];
                $e = new \TypeError(\sprintf('Return value of %s() passed to %s::fromCallable() must be of the type string, %s returned.', $callback, static::class, $type));
            }
            throw $e;
        }
    }
    /**
     *
     */
    public function __sleep() : array
    {
        $this->__toString();
        return ['value'];
    }
    /**
     *
     */
    public function jsonSerialize() : string
    {
        return $this->__toString();
    }
    /**
     *
     */
    private function __construct()
    {
    }
    /**
     *
     */
    private static function getPrettyName(callable $callback) : string
    {
        if (\is_string($callback)) {
            return $callback;
        }
        if (\is_array($callback)) {
            $class = \is_object($callback[0]) ? \get_debug_type($callback[0]) : $callback[0];
            $method = $callback[1];
        } elseif ($callback instanceof \Closure) {
            $r = new \ReflectionFunction($callback);
            if (\str_contains($r->name, '{closure}') || !($class = $r->getClosureScopeClass())) {
                return $r->name;
            }
            $class = $class->name;
            $method = $r->name;
        } else {
            $class = \get_debug_type($callback);
            $method = '__invoke';
        }
        return $class . '::' . $method;
    }
}
