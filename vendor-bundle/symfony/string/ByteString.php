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
 * Represents a binary-safe string of bytes.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 * @author Hugo Hamon <hugohamon@neuf.fr>
 *
 * @throws ExceptionInterface
 */
class ByteString extends AbstractString
{
    private const ALPHABET_ALPHANUMERIC = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
    /**
     *
     */
    public function __construct(string $string = '')
    {
        $this->string = $string;
    }
    /*
     * The following method was derived from code of the Hack Standard Library (v4.40 - 2020-05-03)
     *
     * https://github.com/hhvm/hsl/blob/80a42c02f036f72a42f0415e80d6b847f4bf62d5/src/random/private.php#L16
     *
     * Code subject to the MIT license (https://github.com/hhvm/hsl/blob/master/LICENSE).
     *
     * Copyright (c) 2004-2020, Facebook, Inc. (https://www.facebook.com/)
     */
    /**
     *
     */
    public static function fromRandom(int $length = 16, string $alphabet = NULL) : self
    {
        if ($length <= 0) {
            throw new InvalidArgumentException(\sprintf('A strictly positive length is expected, "%d" given.', $length));
        }
        $alphabet ??= self::ALPHABET_ALPHANUMERIC;
        $alphabetSize = \strlen($alphabet);
        $bits = (int) \ceil(\log($alphabetSize, 2.0));
        if ($bits <= 0 || $bits > 56) {
            throw new InvalidArgumentException('The length of the alphabet must in the [2^1, 2^56] range.');
        }
        $ret = '';
        while ($length > 0) {
            $urandomLength = (int) \ceil(2 * $length * $bits / 8.0);
            $data = \random_bytes($urandomLength);
            $unpackedData = 0;
            $unpackedBits = 0;
            for ($i = 0; $i < $urandomLength && $length > 0; ++$i) {
                // Unpack 8 bits
                $unpackedData = $unpackedData << 8 | \ord($data[$i]);
                $unpackedBits += 8;
                // While we have enough bits to select a character from the alphabet, keep
                // consuming the random data
                for (; $unpackedBits >= $bits && $length > 0; $unpackedBits -= $bits) {
                    $index = $unpackedData & (1 << $bits) - 1;
                    $unpackedData >>= $bits;
                    // Unfortunately, the alphabet size is not necessarily a power of two.
                    // Worst case, it is 2^k + 1, which means we need (k+1) bits and we
                    // have around a 50% chance of missing as k gets larger
                    if ($index < $alphabetSize) {
                        $ret .= $alphabet[$index];
                        --$length;
                    }
                }
            }
        }
        return new static($ret);
    }
    /**
     *
     */
    public function bytesAt(int $offset) : array
    {
        $str = $this->string[$offset] ?? '';
        return '' === $str ? [] : [\ord($str)];
    }
    /**
     * @return static
     */
    public function append(string ...$suffix)
    {
        $str = clone $this;
        $str->string .= 1 >= \count($suffix) ? $suffix[0] ?? '' : \implode('', $suffix);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function camel()
    {
        $str = clone $this;
        $str->string = \lcfirst(\str_replace(' ', '', \ucwords(\preg_replace('/[^a-zA-Z0-9\\x7f-\\xff]++/', ' ', $this->string))));
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     *
     */
    public function chunk(int $length = 1) : array
    {
        if (1 > $length) {
            throw new InvalidArgumentException('The chunk length must be greater than zero.');
        }
        if ('' === $this->string) {
            return [];
        }
        $str = clone $this;
        $chunks = [];
        foreach (\str_split($this->string, $length) as $chunk) {
            $str->string = $chunk;
            $chunks[] = clone $str;
        }
        return $chunks;
    }
    /**
     * @param (string | iterable | AbstractString) $suffix
     */
    public function endsWith($suffix) : bool
    {
        if (!(\is_string($suffix) || (\is_array($suffix) || $suffix instanceof \Traversable) || $suffix instanceof AbstractString)) {
            if (!(\is_string($suffix) || \is_object($suffix) && \method_exists($suffix, '__toString') || (\is_bool($suffix) || \is_numeric($suffix)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($suffix) must be of type AbstractString|string|iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($suffix) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $suffix = (string) $suffix;
            }
        }
        if ($suffix instanceof AbstractString) {
            $suffix = $suffix->string;
        } elseif (!\is_string($suffix)) {
            return parent::endsWith($suffix);
        }
        return '' !== $suffix && \strlen($this->string) >= \strlen($suffix) && 0 === \Phabel\Target\Php80\Polyfill::substr_compare($this->string, $suffix, -\strlen($suffix), null, $this->ignoreCase);
    }
    /**
     * @param (string | iterable | AbstractString) $string
     */
    public function equalsTo($string) : bool
    {
        if (!(\is_string($string) || (\is_array($string) || $string instanceof \Traversable) || $string instanceof AbstractString)) {
            if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type AbstractString|string|iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $string = (string) $string;
            }
        }
        if ($string instanceof AbstractString) {
            $string = $string->string;
        } elseif (!\is_string($string)) {
            return parent::equalsTo($string);
        }
        if ('' !== $string && $this->ignoreCase) {
            return 0 === \strcasecmp($string, $this->string);
        }
        return $string === $this->string;
    }
    /**
     * @return static
     */
    public function folded()
    {
        $str = clone $this;
        $str->string = \strtolower($str->string);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param (string | iterable | AbstractString) $needle
     */
    public function indexOf($needle, int $offset = 0) : ?int
    {
        if (!(\is_string($needle) || (\is_array($needle) || $needle instanceof \Traversable) || $needle instanceof AbstractString)) {
            if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($needle) must be of type AbstractString|string|iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $needle = (string) $needle;
            }
        }
        if ($needle instanceof AbstractString) {
            $needle = $needle->string;
        } elseif (!\is_string($needle)) {
            return parent::indexOf($needle, $offset);
        }
        if ('' === $needle) {
            return null;
        }
        $i = $this->ignoreCase ? \stripos($this->string, $needle, $offset) : \strpos($this->string, $needle, $offset);
        return \false === $i ? null : $i;
    }
    /**
     * @param (string | iterable | AbstractString) $needle
     */
    public function indexOfLast($needle, int $offset = 0) : ?int
    {
        if (!(\is_string($needle) || (\is_array($needle) || $needle instanceof \Traversable) || $needle instanceof AbstractString)) {
            if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($needle) must be of type AbstractString|string|iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $needle = (string) $needle;
            }
        }
        if ($needle instanceof AbstractString) {
            $needle = $needle->string;
        } elseif (!\is_string($needle)) {
            return parent::indexOfLast($needle, $offset);
        }
        if ('' === $needle) {
            return null;
        }
        $i = $this->ignoreCase ? \strripos($this->string, $needle, $offset) : \strrpos($this->string, $needle, $offset);
        return \false === $i ? null : $i;
    }
    /**
     *
     */
    public function isUtf8() : bool
    {
        return '' === $this->string || \preg_match('//u', $this->string);
    }
    /**
     * @return static
     */
    public function join(array $strings, string $lastGlue = NULL)
    {
        $str = clone $this;
        $tail = null !== $lastGlue && 1 < \count($strings) ? $lastGlue . \array_pop($strings) : '';
        $str->string = \implode($this->string, $strings) . $tail;
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     *
     */
    public function length() : int
    {
        return \strlen($this->string);
    }
    /**
     * @return static
     */
    public function lower()
    {
        $str = clone $this;
        $str->string = \strtolower($str->string);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     *
     */
    public function match(string $regexp, int $flags = 0, int $offset = 0) : array
    {
        $match = (\PREG_PATTERN_ORDER | \PREG_SET_ORDER) & $flags ? 'preg_match_all' : 'preg_match';
        if ($this->ignoreCase) {
            $regexp .= 'i';
        }
        \set_error_handler(static function ($t, $m) {
            throw new InvalidArgumentException($m);
        });
        try {
            if (\false === $match($regexp, $this->string, $matches, $flags | 0, $offset)) {
                $lastError = \preg_last_error();
                foreach (\get_defined_constants(\true)['pcre'] as $k => $v) {
                    if ($lastError === $v && \str_ends_with($k, '_ERROR')) {
                        throw new RuntimeException('Matching failed with ' . $k . '.');
                    }
                }
                throw new RuntimeException('Matching failed with unknown error code.');
            }
        } finally {
            \restore_error_handler();
        }
        array_walk_recursive($match, function (&$v){return $v === "" ? null : $v;}); return $matches;
    }
    /**
     * @return static
     */
    public function padBoth(int $length, string $padStr = ' ')
    {
        $str = clone $this;
        $str->string = \str_pad($this->string, $length, $padStr, \STR_PAD_BOTH);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function padEnd(int $length, string $padStr = ' ')
    {
        $str = clone $this;
        $str->string = \str_pad($this->string, $length, $padStr, \STR_PAD_RIGHT);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function padStart(int $length, string $padStr = ' ')
    {
        $str = clone $this;
        $str->string = \str_pad($this->string, $length, $padStr, \STR_PAD_LEFT);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function prepend(string ...$prefix)
    {
        $str = clone $this;
        $str->string = (1 >= \count($prefix) ? $prefix[0] ?? '' : \implode('', $prefix)) . $str->string;
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function replace(string $from, string $to)
    {
        $str = clone $this;
        if ('' !== $from) {
            $str->string = $this->ignoreCase ? \str_ireplace($from, $to, $this->string) : \str_replace($from, $to, $this->string);
        }
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param (string | callable) $to
     * @return static
     */
    public function replaceMatches(string $fromRegexp, $to)
    {
        if (!(\is_string($to) || \is_callable($to))) {
            if (!(\is_string($to) || \is_object($to) && \method_exists($to, '__toString') || (\is_bool($to) || \is_numeric($to)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($to) must be of type callable|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($to) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $to = (string) $to;
            }
        }
        if ($this->ignoreCase) {
            $fromRegexp .= 'i';
        }
        $replace = \is_array($to) || $to instanceof \Closure ? 'preg_replace_callback' : 'preg_replace';
        \set_error_handler(static function ($t, $m) {
            throw new InvalidArgumentException($m);
        });
        try {
            if (null === ($string = $replace($fromRegexp, $to, $this->string))) {
                $lastError = \preg_last_error();
                foreach (\get_defined_constants(\true)['pcre'] as $k => $v) {
                    if ($lastError === $v && \str_ends_with($k, '_ERROR')) {
                        throw new RuntimeException('Matching failed with ' . $k . '.');
                    }
                }
                throw new RuntimeException('Matching failed with unknown error code.');
            }
        } finally {
            \restore_error_handler();
        }
        $str = clone $this;
        $str->string = $string;
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function reverse()
    {
        $str = clone $this;
        $str->string = \strrev($str->string);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function slice(int $start = 0, int $length = NULL)
    {
        $str = clone $this;
        $str->string = (string) \Phabel\Target\Php80\Polyfill::substr($this->string, $start, $length ?? \PHP_INT_MAX);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function snake()
    {
        $str = $this->camel();
        $str->string = \strtolower(\preg_replace(['/([A-Z]+)([A-Z][a-z])/', '/([a-z\\d])([A-Z])/'], 'PhabelVendor\\1_\\2', $str->string));
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function splice(string $replacement, int $start = 0, int $length = NULL)
    {
        $str = clone $this;
        $str->string = \Phabel\Target\Php80\Polyfill::substr_replace($this->string, $replacement, $start, $length ?? \PHP_INT_MAX);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     *
     */
    public function split(string $delimiter, int $limit = NULL, int $flags = NULL) : array
    {
        if (1 > ($limit ??= \PHP_INT_MAX)) {
            throw new InvalidArgumentException('Split limit must be a positive integer.');
        }
        if ('' === $delimiter) {
            throw new InvalidArgumentException('Split delimiter is empty.');
        }
        if (null !== $flags) {
            return parent::split($delimiter, $limit, $flags);
        }
        $str = clone $this;
        $chunks = $this->ignoreCase ? \preg_split('{' . \preg_quote($delimiter) . '}iD', $this->string, $limit) : \explode($delimiter, $this->string, $limit);
        foreach ($chunks as &$chunk) {
            $str->string = $chunk;
            $chunk = clone $str;
        }
        return $chunks;
    }
    /**
     * @param (string | iterable | AbstractString) $prefix
     */
    public function startsWith($prefix) : bool
    {
        if (!(\is_string($prefix) || (\is_array($prefix) || $prefix instanceof \Traversable) || $prefix instanceof AbstractString)) {
            if (!(\is_string($prefix) || \is_object($prefix) && \method_exists($prefix, '__toString') || (\is_bool($prefix) || \is_numeric($prefix)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($prefix) must be of type AbstractString|string|iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($prefix) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $prefix = (string) $prefix;
            }
        }
        if ($prefix instanceof AbstractString) {
            $prefix = $prefix->string;
        } elseif (!\is_string($prefix)) {
            return parent::startsWith($prefix);
        }
        return '' !== $prefix && 0 === ($this->ignoreCase ? \strncasecmp($this->string, $prefix, \strlen($prefix)) : \strncmp($this->string, $prefix, \strlen($prefix)));
    }
    /**
     * @return static
     */
    public function title(bool $allWords = \false)
    {
        $str = clone $this;
        $str->string = $allWords ? \ucwords($str->string) : \ucfirst($str->string);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     *
     */
    public function toUnicodeString(string $fromEncoding = NULL) : UnicodeString
    {
        return new UnicodeString($this->toCodePointString($fromEncoding)->string);
    }
    /**
     *
     */
    public function toCodePointString(string $fromEncoding = NULL) : CodePointString
    {
        $u = new CodePointString();
        if (\in_array($fromEncoding, [null, 'utf8', 'utf-8', 'UTF8', 'UTF-8'], \true) && \preg_match('//u', $this->string)) {
            $u->string = $this->string;
            return $u;
        }
        \set_error_handler(static function ($t, $m) {
            throw new InvalidArgumentException($m);
        });
        try {
            try {
                $validEncoding = \false !== \mb_detect_encoding($this->string, $fromEncoding ?? 'Windows-1252', \true);
            } catch (InvalidArgumentException $e) {
                if (!\function_exists('iconv')) {
                    throw $e;
                }
                $u->string = \iconv($fromEncoding ?? 'Windows-1252', 'UTF-8', $this->string);
                return $u;
            }
        } finally {
            \restore_error_handler();
        }
        if (!$validEncoding) {
            throw new InvalidArgumentException(\sprintf('Invalid "%s" string.', $fromEncoding ?? 'Windows-1252'));
        }
        $u->string = \mb_convert_encoding($this->string, 'UTF-8', $fromEncoding ?? 'Windows-1252');
        return $u;
    }
    /**
     * @return static
     */
    public function trim(string $chars = ' 	
' . "\x00" . '')
    {
        $str = clone $this;
        $str->string = \trim($str->string, $chars);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function trimEnd(string $chars = ' 	
' . "\x00" . '')
    {
        $str = clone $this;
        $str->string = \rtrim($str->string, $chars);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function trimStart(string $chars = ' 	
' . "\x00" . '')
    {
        $str = clone $this;
        $str->string = \ltrim($str->string, $chars);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function upper()
    {
        $str = clone $this;
        $str->string = \strtoupper($str->string);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof static) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     *
     */
    public function width(bool $ignoreAnsiDecoration = \true) : int
    {
        $string = \preg_match('//u', $this->string) ? $this->string : \preg_replace('/[\\x80-\\xFF]/', '?', $this->string);
        return (new CodePointString($string))->width($ignoreAnsiDecoration);
    }
}
