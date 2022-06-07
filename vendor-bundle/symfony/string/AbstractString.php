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

use PhabelVendor\Symfony\Component\String\Exception\ExceptionInterface;
use PhabelVendor\Symfony\Component\String\Exception\InvalidArgumentException;
use PhabelVendor\Symfony\Component\String\Exception\RuntimeException;
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
    public const PREG_PATTERN_ORDER = \PREG_PATTERN_ORDER;
    public const PREG_SET_ORDER = \PREG_SET_ORDER;
    public const PREG_OFFSET_CAPTURE = \PREG_OFFSET_CAPTURE;
    public const PREG_UNMATCHED_AS_NULL = 0;
    public const PREG_SPLIT = 0;
    public const PREG_SPLIT_NO_EMPTY = \PREG_SPLIT_NO_EMPTY;
    public const PREG_SPLIT_DELIM_CAPTURE = \PREG_SPLIT_DELIM_CAPTURE;
    public const PREG_SPLIT_OFFSET_CAPTURE = \PREG_SPLIT_OFFSET_CAPTURE;
    protected $string = '';
    protected $ignoreCase = \false;
    /**
     *
     */
    public abstract function __construct(string $string = '');
    /**
     * Unwraps instances of AbstractString back to strings.
     *
     * @return (string[] | array)
     */
    public static function unwrap(array $values) : array
    {
        foreach ($values as $k => $v) {
            if ($v instanceof self) {
                $values[$k] = $v->__toString();
            } elseif (\is_array($v) && $values[$k] !== ($v = static::unwrap($v))) {
                $values[$k] = $v;
            }
        }
        return $values;
    }
    /**
     * Wraps (and normalizes) strings in instances of AbstractString.
     *
     * @return (static[] | array)
     */
    public static function wrap(array $values) : array
    {
        $i = 0;
        $keys = null;
        foreach ($values as $k => $v) {
            if (\is_string($k) && '' !== $k && $k !== ($j = (string) new static($k))) {
                $keys = $keys ?? \array_keys($values);
                $keys[$i] = $j;
            }
            if (\is_string($v)) {
                $values[$k] = new static($v);
            } elseif (\is_array($v) && $values[$k] !== ($v = static::wrap($v))) {
                $values[$k] = $v;
            }
            ++$i;
        }
        return null !== $keys ? \array_combine($keys, $values) : $values;
    }
    /**
     * @param (string | string[]) $needle
     * @return static
     */
    public function after($needle, bool $includeNeedle = \false, int $offset = 0)
    {
        if (!(\is_string($needle) || (\is_array($needle) || $needle instanceof \Traversable))) {
            if (!(\is_string($needle) || \Phabel\Target\Php72\Polyfill::is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($needle) must be of type string|iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $needle = (string) $needle;
            }
        }
        $str = clone $this;
        $i = \PHP_INT_MAX;
        if (\is_string($needle)) {
            $needle = [$needle];
        }
        foreach ($needle as $n) {
            $n = (string) $n;
            $j = $this->indexOf($n, $offset);
            if (null !== $j && $j < $i) {
                $i = $j;
                $str->string = $n;
            }
        }
        if (\PHP_INT_MAX === $i) {
            $phabelReturn = $str;
            if (!$phabelReturn instanceof static) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (!$includeNeedle) {
            $i += $str->length();
        }
        $phabelReturn = $this->slice($i);
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param (string | string[]) $needle
     * @return static
     */
    public function afterLast($needle, bool $includeNeedle = \false, int $offset = 0)
    {
        if (!(\is_string($needle) || (\is_array($needle) || $needle instanceof \Traversable))) {
            if (!(\is_string($needle) || \Phabel\Target\Php72\Polyfill::is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($needle) must be of type string|iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $needle = (string) $needle;
            }
        }
        $str = clone $this;
        $i = null;
        if (\is_string($needle)) {
            $needle = [$needle];
        }
        foreach ($needle as $n) {
            $n = (string) $n;
            $j = $this->indexOfLast($n, $offset);
            if (null !== $j && $j >= $i) {
                $i = $offset = $j;
                $str->string = $n;
            }
        }
        if (null === $i) {
            $phabelReturn = $str;
            if (!$phabelReturn instanceof static) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (!$includeNeedle) {
            $i += $str->length();
        }
        $phabelReturn = $this->slice($i);
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     *
     * @return static
     */
    public abstract function append(string ...$suffix);
    /**
     * @param (string | string[]) $needle
     * @return static
     */
    public function before($needle, bool $includeNeedle = \false, int $offset = 0)
    {
        if (!(\is_string($needle) || (\is_array($needle) || $needle instanceof \Traversable))) {
            if (!(\is_string($needle) || \Phabel\Target\Php72\Polyfill::is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($needle) must be of type string|iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $needle = (string) $needle;
            }
        }
        $str = clone $this;
        $i = \PHP_INT_MAX;
        if (\is_string($needle)) {
            $needle = [$needle];
        }
        foreach ($needle as $n) {
            $n = (string) $n;
            $j = $this->indexOf($n, $offset);
            if (null !== $j && $j < $i) {
                $i = $j;
                $str->string = $n;
            }
        }
        if (\PHP_INT_MAX === $i) {
            $phabelReturn = $str;
            if (!$phabelReturn instanceof static) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($includeNeedle) {
            $i += $str->length();
        }
        $phabelReturn = $this->slice(0, $i);
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param (string | string[]) $needle
     * @return static
     */
    public function beforeLast($needle, bool $includeNeedle = \false, int $offset = 0)
    {
        if (!(\is_string($needle) || (\is_array($needle) || $needle instanceof \Traversable))) {
            if (!(\is_string($needle) || \Phabel\Target\Php72\Polyfill::is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($needle) must be of type string|iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $needle = (string) $needle;
            }
        }
        $str = clone $this;
        $i = null;
        if (\is_string($needle)) {
            $needle = [$needle];
        }
        foreach ($needle as $n) {
            $n = (string) $n;
            $j = $this->indexOfLast($n, $offset);
            if (null !== $j && $j >= $i) {
                $i = $offset = $j;
                $str->string = $n;
            }
        }
        if (null === $i) {
            $phabelReturn = $str;
            if (!$phabelReturn instanceof static) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($includeNeedle) {
            $i += $str->length();
        }
        $phabelReturn = $this->slice(0, $i);
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return int[]
     */
    public function bytesAt(int $offset) : array
    {
        $str = $this->slice($offset, 1);
        return '' === $str->string ? [] : \array_values(\unpack('C*', $str->string));
    }
    /**
     *
     * @return static
     */
    public abstract function camel();
    /**
     * @return static[]
     */
    public abstract function chunk(int $length = 1) : array;
    /**
     *
     * @return static
     */
    public function collapseWhitespace()
    {
        $str = clone $this;
        $str->string = \trim(\preg_replace('/(?:\\s{2,}+|[^\\S ])/', ' ', $str->string));
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param (string | string[]) $needle
     */
    public function containsAny($needle) : bool
    {
        if (!(\is_string($needle) || (\is_array($needle) || $needle instanceof \Traversable))) {
            if (!(\is_string($needle) || \Phabel\Target\Php72\Polyfill::is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($needle) must be of type string|iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $needle = (string) $needle;
            }
        }
        return null !== $this->indexOf($needle);
    }
    /**
     * @param (string | string[]) $suffix
     */
    public function endsWith($suffix) : bool
    {
        if (!(\is_string($suffix) || (\is_array($suffix) || $suffix instanceof \Traversable))) {
            if (!(\is_string($suffix) || \Phabel\Target\Php72\Polyfill::is_object($suffix) && \method_exists($suffix, '__toString') || (\is_bool($suffix) || \is_numeric($suffix)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($suffix) must be of type string|iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($suffix) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $suffix = (string) $suffix;
            }
        }
        if (\is_string($suffix)) {
            throw new \TypeError(\sprintf('Method "%s()" must be overridden by class "%s" to deal with non-iterable values.', __FUNCTION__, static::class));
        }
        foreach ($suffix as $s) {
            if ($this->endsWith((string) $s)) {
                return \true;
            }
        }
        return \false;
    }
    /**
     *
     * @return static
     */
    public function ensureEnd(string $suffix)
    {
        if (!$this->endsWith($suffix)) {
            $phabelReturn = $this->append($suffix);
            if (!$phabelReturn instanceof static) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $suffix = \preg_quote($suffix);
        $regex = '{(' . $suffix . ')(?:' . $suffix . ')++$}D';
        $phabelReturn = $this->replaceMatches($regex . ($this->ignoreCase ? 'i' : ''), '$1');
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     *
     * @return static
     */
    public function ensureStart(string $prefix)
    {
        $prefix = new static($prefix);
        if (!$this->startsWith($prefix)) {
            $phabelReturn = $this->prepend($prefix);
            if (!$phabelReturn instanceof static) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param (string | string[]) $string
     */
    public function equalsTo($string) : bool
    {
        if (!(\is_string($string) || (\is_array($string) || $string instanceof \Traversable))) {
            if (!(\is_string($string) || \Phabel\Target\Php72\Polyfill::is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type string|iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $string = (string) $string;
            }
        }
        if (\is_string($string)) {
            throw new \TypeError(\sprintf('Method "%s()" must be overridden by class "%s" to deal with non-iterable values.', __FUNCTION__, static::class));
        }
        foreach ($string as $s) {
            if ($this->equalsTo((string) $s)) {
                return \true;
            }
        }
        return \false;
    }
    /**
     *
     * @return static
     */
    public abstract function folded();
    /**
     *
     * @return static
     */
    public function ignoreCase()
    {
        $str = clone $this;
        $str->ignoreCase = \true;
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param (string | string[]) $needle
     */
    public function indexOf($needle, int $offset = 0) : ?int
    {
        if (!(\is_string($needle) || (\is_array($needle) || $needle instanceof \Traversable))) {
            if (!(\is_string($needle) || \Phabel\Target\Php72\Polyfill::is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($needle) must be of type string|iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $needle = (string) $needle;
            }
        }
        if (\is_string($needle)) {
            throw new \TypeError(\sprintf('Method "%s()" must be overridden by class "%s" to deal with non-iterable values.', __FUNCTION__, static::class));
        }
        $i = \PHP_INT_MAX;
        foreach ($needle as $n) {
            $j = $this->indexOf((string) $n, $offset);
            if (null !== $j && $j < $i) {
                $i = $j;
            }
        }
        return \PHP_INT_MAX === $i ? null : $i;
    }
    /**
     * @param (string | string[]) $needle
     */
    public function indexOfLast($needle, int $offset = 0) : ?int
    {
        if (!(\is_string($needle) || (\is_array($needle) || $needle instanceof \Traversable))) {
            if (!(\is_string($needle) || \Phabel\Target\Php72\Polyfill::is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($needle) must be of type string|iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $needle = (string) $needle;
            }
        }
        if (\is_string($needle)) {
            throw new \TypeError(\sprintf('Method "%s()" must be overridden by class "%s" to deal with non-iterable values.', __FUNCTION__, static::class));
        }
        $i = null;
        foreach ($needle as $n) {
            $j = $this->indexOfLast((string) $n, $offset);
            if (null !== $j && $j >= $i) {
                $i = $offset = $j;
            }
        }
        return $i;
    }
    /**
     *
     */
    public function isEmpty() : bool
    {
        return '' === $this->string;
    }
    /**
     *
     * @return static
     */
    public abstract function join(array $strings, string $lastGlue = NULL);
    /**
     *
     */
    public function jsonSerialize() : string
    {
        return $this->string;
    }
    /**
     *
     */
    public abstract function length() : int;
    /**
     *
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
    public abstract function match(string $regexp, int $flags = 0, int $offset = 0) : array;
    /**
     *
     * @return static
     */
    public abstract function padBoth(int $length, string $padStr = ' ');
    /**
     *
     * @return static
     */
    public abstract function padEnd(int $length, string $padStr = ' ');
    /**
     *
     * @return static
     */
    public abstract function padStart(int $length, string $padStr = ' ');
    /**
     *
     * @return static
     */
    public abstract function prepend(string ...$prefix);
    /**
     *
     * @return static
     */
    public function repeat(int $multiplier)
    {
        if (0 > $multiplier) {
            throw new InvalidArgumentException(\sprintf('Multiplier must be positive, %d given.', $multiplier));
        }
        $str = clone $this;
        $str->string = \str_repeat($str->string, $multiplier);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     *
     * @return static
     */
    public abstract function replace(string $from, string $to);
    /**
     * @param (string | callable) $to
     * @return static
     */
    public abstract function replaceMatches(string $fromRegexp, $to);
    /**
     *
     * @return static
     */
    public abstract function reverse();
    /**
     *
     * @return static
     */
    public abstract function slice(int $start = 0, int $length = NULL);
    /**
     *
     * @return static
     */
    public abstract function snake();
    /**
     *
     * @return static
     */
    public abstract function splice(string $replacement, int $start = 0, int $length = NULL);
    /**
     * @return static[]
     */
    public function split(string $delimiter, int $limit = NULL, int $flags = NULL) : array
    {
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
                    if ($lastError === $v && \str_ends_with($k, '_ERROR')) {
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
        return $chunks;
    }
    /**
     * @param (string | string[]) $prefix
     */
    public function startsWith($prefix) : bool
    {
        if (!(\is_string($prefix) || (\is_array($prefix) || $prefix instanceof \Traversable))) {
            if (!(\is_string($prefix) || \Phabel\Target\Php72\Polyfill::is_object($prefix) && \method_exists($prefix, '__toString') || (\is_bool($prefix) || \is_numeric($prefix)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($prefix) must be of type string|iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($prefix) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $prefix = (string) $prefix;
            }
        }
        if (\is_string($prefix)) {
            throw new \TypeError(\sprintf('Method "%s()" must be overridden by class "%s" to deal with non-iterable values.', __FUNCTION__, static::class));
        }
        foreach ($prefix as $prefix) {
            if ($this->startsWith((string) $prefix)) {
                return \true;
            }
        }
        return \false;
    }
    /**
     *
     * @return static
     */
    public abstract function title(bool $allWords = \false);
    /**
     *
     */
    public function toByteString(string $toEncoding = NULL) : ByteString
    {
        $b = new ByteString();
        $toEncoding = \in_array($toEncoding, ['utf8', 'utf-8', 'UTF8'], \true) ? 'UTF-8' : $toEncoding;
        if (null === $toEncoding || $toEncoding === ($fromEncoding = $this instanceof AbstractUnicodeString || \preg_match('//u', $b->string) ? 'UTF-8' : 'Windows-1252')) {
            $b->string = $this->string;
            return $b;
        }
        \set_error_handler(static function ($t, $m) {
            throw new InvalidArgumentException($m);
        });
        try {
            try {
                $b->string = \Phabel\Target\Php72\Polyfill::mb_convert_encoding($this->string, $toEncoding, 'UTF-8');
            } catch (InvalidArgumentException $e) {
                if (!\function_exists('iconv')) {
                    throw $e;
                }
                $b->string = \iconv('UTF-8', $toEncoding, $this->string);
            }
        } finally {
            \restore_error_handler();
        }
        return $b;
    }
    /**
     *
     */
    public function toCodePointString() : CodePointString
    {
        return new CodePointString($this->string);
    }
    /**
     *
     */
    public function toString() : string
    {
        return $this->string;
    }
    /**
     *
     */
    public function toUnicodeString() : UnicodeString
    {
        return new UnicodeString($this->string);
    }
    /**
     *
     * @return static
     */
    public abstract function trim(string $chars = ' 	
' . "\x00" . ' ﻿');
    /**
     *
     * @return static
     */
    public abstract function trimEnd(string $chars = ' 	
' . "\x00" . ' ﻿');
    /**
     * @param (string | string[]) $prefix
     * @return static
     */
    public function trimPrefix($prefix)
    {
        if (\is_array($prefix) || $prefix instanceof \Traversable) {
            // don't use is_iterable(), it's slow
            foreach ($prefix as $s) {
                $t = $this->trimPrefix($s);
                if ($t->string !== $this->string) {
                    $phabelReturn = $t;
                    if (!$phabelReturn instanceof static) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    return $phabelReturn;
                }
            }
            $phabelReturn = clone $this;
            if (!$phabelReturn instanceof static) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $str = clone $this;
        if ($prefix instanceof self) {
            $prefix = $prefix->string;
        } else {
            $prefix = (string) $prefix;
        }
        if ('' !== $prefix && \strlen($this->string) >= \strlen($prefix) && 0 === \Phabel\Target\Php80\Polyfill::substr_compare($this->string, $prefix, 0, \strlen($prefix), $this->ignoreCase)) {
            $str->string = \Phabel\Target\Php80\Polyfill::substr($this->string, \strlen($prefix));
        }
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     *
     * @return static
     */
    public abstract function trimStart(string $chars = ' 	
' . "\x00" . ' ﻿');
    /**
     * @param (string | string[]) $suffix
     * @return static
     */
    public function trimSuffix($suffix)
    {
        if (\is_array($suffix) || $suffix instanceof \Traversable) {
            // don't use is_iterable(), it's slow
            foreach ($suffix as $s) {
                $t = $this->trimSuffix($s);
                if ($t->string !== $this->string) {
                    $phabelReturn = $t;
                    if (!$phabelReturn instanceof static) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    return $phabelReturn;
                }
            }
            $phabelReturn = clone $this;
            if (!$phabelReturn instanceof static) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $str = clone $this;
        if ($suffix instanceof self) {
            $suffix = $suffix->string;
        } else {
            $suffix = (string) $suffix;
        }
        if ('' !== $suffix && \strlen($this->string) >= \strlen($suffix) && 0 === \Phabel\Target\Php80\Polyfill::substr_compare($this->string, $suffix, -\strlen($suffix), null, $this->ignoreCase)) {
            $str->string = \Phabel\Target\Php80\Polyfill::substr($this->string, 0, -\strlen($suffix));
        }
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     *
     * @return static
     */
    public function truncate(int $length, string $ellipsis = '', bool $cut = \true)
    {
        $stringLength = $this->length();
        if ($stringLength <= $length) {
            $phabelReturn = clone $this;
            if (!$phabelReturn instanceof static) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
                if (!$phabelReturn instanceof static) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
            $length += $ellipsisLength;
        }
        $str = $this->slice(0, $length - $ellipsisLength);
        $phabelReturn = $ellipsisLength ? $str->trimEnd()->append($ellipsis) : $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     *
     * @return static
     */
    public abstract function upper();
    /**
     * Returns the printable length on a terminal.
     */
    public abstract function width(bool $ignoreAnsiDecoration = \true) : int;
    /**
     *
     * @return static
     */
    public function wordwrap(int $width = 75, string $break = '
', bool $cut = \false)
    {
        $lines = '' !== $break ? $this->split($break) : [clone $this];
        $chars = [];
        $mask = '';
        if (1 === \count($lines) && '' === $lines[0]->string) {
            $phabelReturn = $lines[0];
            if (!$phabelReturn instanceof static) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     *
     */
    public function __sleep() : array
    {
        return ['string'];
    }
    /**
     *
     */
    public function __clone()
    {
        $this->ignoreCase = \false;
    }
    /**
     *
     */
    public function __toString() : string
    {
        return $this->string;
    }
}
