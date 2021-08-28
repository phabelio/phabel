<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\String;

use Phabel\Symfony\Component\String\Exception\ExceptionInterface;
use Phabel\Symfony\Component\String\Exception\InvalidArgumentException;
use Phabel\Symfony\Component\String\Exception\RuntimeException;
/**
 * Represents a string of abstract characters.
 *
 * Unicode defines 3 types of "characters" (bytes, code points and grapheme clusters).
 * This class is the abstract type to use as a type-hint when the logic you want to
 * implement doesn't care about the exact variant it deals with.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 * @author Hugo Hamon <hugohamon@neuf.fr>
 *
 * @throws ExceptionInterface
 */
abstract class AbstractString implements \Stringable, \JsonSerializable
{
    const PREG_PATTERN_ORDER = \PREG_PATTERN_ORDER;
    const PREG_SET_ORDER = \PREG_SET_ORDER;
    const PREG_OFFSET_CAPTURE = \PREG_OFFSET_CAPTURE;
    const PREG_UNMATCHED_AS_NULL = \PREG_UNMATCHED_AS_NULL;
    const PREG_SPLIT = 0;
    const PREG_SPLIT_NO_EMPTY = \PREG_SPLIT_NO_EMPTY;
    const PREG_SPLIT_DELIM_CAPTURE = \PREG_SPLIT_DELIM_CAPTURE;
    const PREG_SPLIT_OFFSET_CAPTURE = \PREG_SPLIT_OFFSET_CAPTURE;
    protected $string = '';
    protected $ignoreCase = \false;
    public abstract function __construct($string = '');
    /**
     * Unwraps instances of AbstractString back to strings.
     *
     * @return string[]|array
     */
    public static function unwrap(array $values)
    {
        foreach ($values as $k => $v) {
            if ($v instanceof self) {
                $values[$k] = $v->__toString();
            } elseif (\is_array($v) && $values[$k] !== ($v = static::unwrap($v))) {
                $values[$k] = $v;
            }
        }
        $phabelReturn = $values;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Wraps (and normalizes) strings in instances of AbstractString.
     *
     * @return static[]|array
     */
    public static function wrap(array $values)
    {
        $i = 0;
        $keys = null;
        foreach ($values as $k => $v) {
            if (\is_string($k) && '' !== $k && $k !== ($j = (string) new static($k))) {
                $keys = isset($keys) ? $keys : \array_keys($values);
                $keys[$i] = $j;
            }
            if (\is_string($v)) {
                $values[$k] = new static($v);
            } elseif (\is_array($v) && $values[$k] !== ($v = static::wrap($v))) {
                $values[$k] = $v;
            }
            ++$i;
        }
        $phabelReturn = null !== $keys ? \array_combine($keys, $values) : $values;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param string|string[] $needle
     *
     * @return static
     */
    public function after($needle, $includeNeedle = \false, $offset = 0)
    {
        if (!\is_bool($includeNeedle)) {
            if (!(\is_bool($includeNeedle) || \is_numeric($includeNeedle) || \is_string($includeNeedle))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($includeNeedle) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($includeNeedle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $includeNeedle = (bool) $includeNeedle;
            }
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $offset = (int) $offset;
            }
        }
        $str = clone $this;
        $i = \PHP_INT_MAX;
        foreach ((array) $needle as $n) {
            $n = (string) $n;
            $j = $this->indexOf($n, $offset);
            if (null !== $j && $j < $i) {
                $i = $j;
                $str->string = $n;
            }
        }
        if (\PHP_INT_MAX === $i) {
            $phabelReturn = $str;
            if (!$phabelReturn instanceof self) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (!$includeNeedle) {
            $i += $str->length();
        }
        $phabelReturn = $this->slice($i);
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param string|string[] $needle
     *
     * @return static
     */
    public function afterLast($needle, $includeNeedle = \false, $offset = 0)
    {
        if (!\is_bool($includeNeedle)) {
            if (!(\is_bool($includeNeedle) || \is_numeric($includeNeedle) || \is_string($includeNeedle))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($includeNeedle) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($includeNeedle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $includeNeedle = (bool) $includeNeedle;
            }
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $offset = (int) $offset;
            }
        }
        $str = clone $this;
        $i = null;
        foreach ((array) $needle as $n) {
            $n = (string) $n;
            $j = $this->indexOfLast($n, $offset);
            if (null !== $j && $j >= $i) {
                $i = $offset = $j;
                $str->string = $n;
            }
        }
        if (null === $i) {
            $phabelReturn = $str;
            if (!$phabelReturn instanceof self) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (!$includeNeedle) {
            $i += $str->length();
        }
        $phabelReturn = $this->slice($i);
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public abstract function append(...$suffix);
    /**
     * @param string|string[] $needle
     *
     * @return static
     */
    public function before($needle, $includeNeedle = \false, $offset = 0)
    {
        if (!\is_bool($includeNeedle)) {
            if (!(\is_bool($includeNeedle) || \is_numeric($includeNeedle) || \is_string($includeNeedle))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($includeNeedle) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($includeNeedle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $includeNeedle = (bool) $includeNeedle;
            }
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $offset = (int) $offset;
            }
        }
        $str = clone $this;
        $i = \PHP_INT_MAX;
        foreach ((array) $needle as $n) {
            $n = (string) $n;
            $j = $this->indexOf($n, $offset);
            if (null !== $j && $j < $i) {
                $i = $j;
                $str->string = $n;
            }
        }
        if (\PHP_INT_MAX === $i) {
            $phabelReturn = $str;
            if (!$phabelReturn instanceof self) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($includeNeedle) {
            $i += $str->length();
        }
        $phabelReturn = $this->slice(0, $i);
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param string|string[] $needle
     *
     * @return static
     */
    public function beforeLast($needle, $includeNeedle = \false, $offset = 0)
    {
        if (!\is_bool($includeNeedle)) {
            if (!(\is_bool($includeNeedle) || \is_numeric($includeNeedle) || \is_string($includeNeedle))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($includeNeedle) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($includeNeedle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $includeNeedle = (bool) $includeNeedle;
            }
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $offset = (int) $offset;
            }
        }
        $str = clone $this;
        $i = null;
        foreach ((array) $needle as $n) {
            $n = (string) $n;
            $j = $this->indexOfLast($n, $offset);
            if (null !== $j && $j >= $i) {
                $i = $offset = $j;
                $str->string = $n;
            }
        }
        if (null === $i) {
            $phabelReturn = $str;
            if (!$phabelReturn instanceof self) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($includeNeedle) {
            $i += $str->length();
        }
        $phabelReturn = $this->slice(0, $i);
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return int[]
     */
    public function bytesAt($offset)
    {
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $offset = (int) $offset;
            }
        }
        $str = $this->slice($offset, 1);
        $phabelReturn = '' === $str->string ? [] : \array_values(\unpack('C*', $str->string));
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public abstract function camel();
    /**
     * @return static[]
     */
    public abstract function chunk($length = 1);
    /**
     * @return static
     */
    public function collapseWhitespace()
    {
        $str = clone $this;
        $str->string = \trim(\preg_replace('/(?:\\s{2,}+|[^\\S ])/', ' ', $str->string));
        $phabelReturn = $str;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param string|string[] $needle
     */
    public function containsAny($needle)
    {
        $phabelReturn = null !== $this->indexOf($needle);
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
     * @param string|string[] $suffix
     */
    public function endsWith($suffix)
    {
        if (!\is_array($suffix) && !$suffix instanceof \Traversable) {
            throw new \TypeError(\sprintf('Method "%s()" must be overridden by class "%s" to deal with non-iterable values.', __FUNCTION__, static::class));
        }
        foreach ($suffix as $s) {
            if ($this->endsWith((string) $s)) {
                $phabelReturn = \true;
                if (!\is_bool($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (bool) $phabelReturn;
                    }
                }
                return $phabelReturn;
            }
        }
        $phabelReturn = \false;
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
     * @return static
     */
    public function ensureEnd($suffix)
    {
        if (!\is_string($suffix)) {
            if (!(\is_string($suffix) || \is_object($suffix) && \method_exists($suffix, '__toString') || (\is_bool($suffix) || \is_numeric($suffix)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($suffix) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($suffix) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $suffix = (string) $suffix;
            }
        }
        if (!$this->endsWith($suffix)) {
            $phabelReturn = $this->append($suffix);
            if (!$phabelReturn instanceof self) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $suffix = \preg_quote($suffix);
        $regex = '{(' . $suffix . ')(?:' . $suffix . ')++$}D';
        $phabelReturn = $this->replaceMatches($regex . ($this->ignoreCase ? 'i' : ''), '$1');
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function ensureStart($prefix)
    {
        if (!\is_string($prefix)) {
            if (!(\is_string($prefix) || \is_object($prefix) && \method_exists($prefix, '__toString') || (\is_bool($prefix) || \is_numeric($prefix)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($prefix) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($prefix) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $prefix = (string) $prefix;
            }
        }
        $prefix = new static($prefix);
        if (!$this->startsWith($prefix)) {
            $phabelReturn = $this->prepend($prefix);
            if (!$phabelReturn instanceof self) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $str = clone $this;
        $i = $prefixLen = $prefix->length();
        while ($this->indexOf($prefix, $i) === $i) {
            $str = $str->slice($prefixLen);
            $i += $prefixLen;
        }
        $phabelReturn = $str;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param string|string[] $string
     */
    public function equalsTo($string)
    {
        if (!\is_array($string) && !$string instanceof \Traversable) {
            throw new \TypeError(\sprintf('Method "%s()" must be overridden by class "%s" to deal with non-iterable values.', __FUNCTION__, static::class));
        }
        foreach ($string as $s) {
            if ($this->equalsTo((string) $s)) {
                $phabelReturn = \true;
                if (!\is_bool($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (bool) $phabelReturn;
                    }
                }
                return $phabelReturn;
            }
        }
        $phabelReturn = \false;
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
     * @return static
     */
    public abstract function folded();
    /**
     * @return static
     */
    public function ignoreCase()
    {
        $str = clone $this;
        $str->ignoreCase = \true;
        $phabelReturn = $str;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param string|string[] $needle
     */
    public function indexOf($needle, $offset = 0)
    {
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $offset = (int) $offset;
            }
        }
        if (!\is_array($needle) && !$needle instanceof \Traversable) {
            throw new \TypeError(\sprintf('Method "%s()" must be overridden by class "%s" to deal with non-iterable values.', __FUNCTION__, static::class));
        }
        $i = \PHP_INT_MAX;
        foreach ($needle as $n) {
            $j = $this->indexOf((string) $n, $offset);
            if (null !== $j && $j < $i) {
                $i = $j;
            }
        }
        $phabelReturn = \PHP_INT_MAX === $i ? null : $i;
        if (!\is_null($phabelReturn)) {
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
    /**
     * @param string|string[] $needle
     */
    public function indexOfLast($needle, $offset = 0)
    {
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $offset = (int) $offset;
            }
        }
        if (!\is_array($needle) && !$needle instanceof \Traversable) {
            throw new \TypeError(\sprintf('Method "%s()" must be overridden by class "%s" to deal with non-iterable values.', __FUNCTION__, static::class));
        }
        $i = null;
        foreach ($needle as $n) {
            $j = $this->indexOfLast((string) $n, $offset);
            if (null !== $j && $j >= $i) {
                $i = $offset = $j;
            }
        }
        $phabelReturn = $i;
        if (!\is_null($phabelReturn)) {
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
    public function isEmpty()
    {
        $phabelReturn = '' === $this->string;
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
     * @return static
     */
    public abstract function join(array $strings, $lastGlue = null);
    public function jsonSerialize()
    {
        $phabelReturn = $this->string;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public abstract function length();
    /**
     * @return static
     */
    public abstract function lower();
    /**
     * Matches the string using a regular expression.
     *
     * Pass PREG_PATTERN_ORDER or PREG_SET_ORDER as $flags to get all occurrences matching the regular expression.
     *
     * @return array All matches in a multi-dimensional array ordered according to flags
     */
    public abstract function match($regexp, $flags = 0, $offset = 0);
    /**
     * @return static
     */
    public abstract function padBoth($length, $padStr = ' ');
    /**
     * @return static
     */
    public abstract function padEnd($length, $padStr = ' ');
    /**
     * @return static
     */
    public abstract function padStart($length, $padStr = ' ');
    /**
     * @return static
     */
    public abstract function prepend(...$prefix);
    /**
     * @return static
     */
    public function repeat($multiplier)
    {
        if (!\is_int($multiplier)) {
            if (!(\is_bool($multiplier) || \is_numeric($multiplier))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($multiplier) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($multiplier) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $multiplier = (int) $multiplier;
            }
        }
        if (0 > $multiplier) {
            throw new InvalidArgumentException(\sprintf('Multiplier must be positive, %d given.', $multiplier));
        }
        $str = clone $this;
        $str->string = \str_repeat($str->string, $multiplier);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public abstract function replace($from, $to);
    /**
     * @param string|callable $to
     *
     * @return static
     */
    public abstract function replaceMatches($fromRegexp, $to);
    /**
     * @return static
     */
    public abstract function reverse();
    /**
     * @return static
     */
    public abstract function slice($start = 0, $length = null);
    /**
     * @return static
     */
    public abstract function snake();
    /**
     * @return static
     */
    public abstract function splice($replacement, $start = 0, $length = null);
    /**
     * @return static[]
     */
    public function split($delimiter, $limit = null, $flags = null)
    {
        if (!\is_string($delimiter)) {
            if (!(\is_string($delimiter) || \is_object($delimiter) && \method_exists($delimiter, '__toString') || (\is_bool($delimiter) || \is_numeric($delimiter)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($delimiter) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($delimiter) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $delimiter = (string) $delimiter;
            }
        }
        if (!\is_null($limit)) {
            if (!\is_int($limit)) {
                if (!(\is_bool($limit) || \is_numeric($limit))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($limit) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($limit) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $limit = (int) $limit;
                }
            }
        }
        if (!\is_null($flags)) {
            if (!\is_int($flags)) {
                if (!(\is_bool($flags) || \is_numeric($flags))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($flags) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($flags) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $flags = (int) $flags;
                }
            }
        }
        if (null === $flags) {
            throw new \TypeError('Split behavior when $flags is null must be implemented by child classes.');
        }
        if ($this->ignoreCase) {
            $delimiter .= 'i';
        }
        \set_error_handler(static function ($t, $m) {
            throw new InvalidArgumentException($m);
        });
        try {
            if (\false === ($chunks = \preg_split($delimiter, $this->string, $limit, $flags))) {
                $lastError = \preg_last_error();
                foreach (\get_defined_constants(\true)['pcre'] as $k => $v) {
                    if ($lastError === $v && '_ERROR' === \substr($k, -6)) {
                        throw new RuntimeException('Splitting failed with ' . $k . '.');
                    }
                }
                throw new RuntimeException('Splitting failed with unknown error code.');
            }
        } finally {
            \restore_error_handler();
        }
        $str = clone $this;
        if (self::PREG_SPLIT_OFFSET_CAPTURE & $flags) {
            foreach ($chunks as &$chunk) {
                $str->string = $chunk[0];
                $chunk[0] = clone $str;
            }
        } else {
            foreach ($chunks as &$chunk) {
                $str->string = $chunk;
                $chunk = clone $str;
            }
        }
        $phabelReturn = $chunks;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param string|string[] $prefix
     */
    public function startsWith($prefix)
    {
        if (!\is_array($prefix) && !$prefix instanceof \Traversable) {
            throw new \TypeError(\sprintf('Method "%s()" must be overridden by class "%s" to deal with non-iterable values.', __FUNCTION__, static::class));
        }
        foreach ($prefix as $prefix) {
            if ($this->startsWith((string) $prefix)) {
                $phabelReturn = \true;
                if (!\is_bool($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (bool) $phabelReturn;
                    }
                }
                return $phabelReturn;
            }
        }
        $phabelReturn = \false;
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
     * @return static
     */
    public abstract function title($allWords = \false);
    public function toByteString($toEncoding = null)
    {
        if (!\is_null($toEncoding)) {
            if (!\is_string($toEncoding)) {
                if (!(\is_string($toEncoding) || \is_object($toEncoding) && \method_exists($toEncoding, '__toString') || (\is_bool($toEncoding) || \is_numeric($toEncoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($toEncoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($toEncoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $toEncoding = (string) $toEncoding;
                }
            }
        }
        $b = new ByteString();
        $toEncoding = \in_array($toEncoding, ['utf8', 'utf-8', 'UTF8'], \true) ? 'UTF-8' : $toEncoding;
        if (null === $toEncoding || $toEncoding === ($fromEncoding = $this instanceof AbstractUnicodeString || \preg_match('//u', $b->string) ? 'UTF-8' : 'Windows-1252')) {
            $b->string = $this->string;
            $phabelReturn = $b;
            if (!$phabelReturn instanceof ByteString) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ByteString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        \set_error_handler(static function ($t, $m) {
            throw new InvalidArgumentException($m);
        });
        try {
            try {
                $b->string = \mb_convert_encoding($this->string, $toEncoding, 'UTF-8');
            } catch (InvalidArgumentException $e) {
                if (!\function_exists('iconv')) {
                    throw $e;
                }
                $b->string = \iconv('UTF-8', $toEncoding, $this->string);
            }
        } finally {
            \restore_error_handler();
        }
        $phabelReturn = $b;
        if (!$phabelReturn instanceof ByteString) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ByteString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function toCodePointString()
    {
        $phabelReturn = new CodePointString($this->string);
        if (!$phabelReturn instanceof CodePointString) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type CodePointString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function toString()
    {
        $phabelReturn = $this->string;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function toUnicodeString()
    {
        $phabelReturn = new UnicodeString($this->string);
        if (!$phabelReturn instanceof UnicodeString) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type UnicodeString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public abstract function trim($chars = " \t\n\r\x00\v\f ﻿");
    /**
     * @return static
     */
    public abstract function trimEnd($chars = " \t\n\r\x00\v\f ﻿");
    /**
     * @return static
     */
    public abstract function trimStart($chars = " \t\n\r\x00\v\f ﻿");
    /**
     * @return static
     */
    public function truncate($length, $ellipsis = '', $cut = \true)
    {
        if (!\is_int($length)) {
            if (!(\is_bool($length) || \is_numeric($length))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($length) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $length = (int) $length;
            }
        }
        if (!\is_string($ellipsis)) {
            if (!(\is_string($ellipsis) || \is_object($ellipsis) && \method_exists($ellipsis, '__toString') || (\is_bool($ellipsis) || \is_numeric($ellipsis)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($ellipsis) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($ellipsis) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $ellipsis = (string) $ellipsis;
            }
        }
        if (!\is_bool($cut)) {
            if (!(\is_bool($cut) || \is_numeric($cut) || \is_string($cut))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($cut) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($cut) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $cut = (bool) $cut;
            }
        }
        $stringLength = $this->length();
        if ($stringLength <= $length) {
            $phabelReturn = clone $this;
            if (!$phabelReturn instanceof self) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $ellipsisLength = '' !== $ellipsis ? (new static($ellipsis))->length() : 0;
        if ($length < $ellipsisLength) {
            $ellipsisLength = 0;
        }
        if (!$cut) {
            if (null === ($length = $this->indexOf([' ', "\r", "\n", "\t"], ($length ?: 1) - 1))) {
                $phabelReturn = clone $this;
                if (!$phabelReturn instanceof self) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
            $length += $ellipsisLength;
        }
        $str = $this->slice(0, $length - $ellipsisLength);
        $phabelReturn = $ellipsisLength ? $str->trimEnd()->append($ellipsis) : $str;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public abstract function upper();
    /**
     * Returns the printable length on a terminal.
     */
    public abstract function width($ignoreAnsiDecoration = \true);
    /**
     * @return static
     */
    public function wordwrap($width = 75, $break = "\n", $cut = \false)
    {
        if (!\is_int($width)) {
            if (!(\is_bool($width) || \is_numeric($width))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($width) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($width) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $width = (int) $width;
            }
        }
        if (!\is_string($break)) {
            if (!(\is_string($break) || \is_object($break) && \method_exists($break, '__toString') || (\is_bool($break) || \is_numeric($break)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($break) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($break) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $break = (string) $break;
            }
        }
        if (!\is_bool($cut)) {
            if (!(\is_bool($cut) || \is_numeric($cut) || \is_string($cut))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($cut) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($cut) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $cut = (bool) $cut;
            }
        }
        $lines = '' !== $break ? $this->split($break) : [clone $this];
        $chars = [];
        $mask = '';
        if (1 === \count($lines) && '' === $lines[0]->string) {
            $phabelReturn = $lines[0];
            if (!$phabelReturn instanceof self) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        foreach ($lines as $i => $line) {
            if ($i) {
                $chars[] = $break;
                $mask .= '#';
            }
            foreach ($line->chunk() as $char) {
                $chars[] = $char->string;
                $mask .= ' ' === $char->string ? ' ' : '?';
            }
        }
        $string = '';
        $j = 0;
        $b = $i = -1;
        $mask = \wordwrap($mask, $width, '#', $cut);
        while (\false !== ($b = \strpos($mask, '#', $b + 1))) {
            for (++$i; $i < $b; ++$i) {
                $string .= $chars[$j];
                unset($chars[$j++]);
            }
            if ($break === $chars[$j] || ' ' === $chars[$j]) {
                unset($chars[$j++]);
            }
            $string .= $break;
        }
        $str = clone $this;
        $str->string = $string . \implode('', $chars);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function __sleep()
    {
        $phabelReturn = ['string'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function __clone()
    {
        $this->ignoreCase = \false;
    }
    public function __toString()
    {
        $phabelReturn = $this->string;
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
