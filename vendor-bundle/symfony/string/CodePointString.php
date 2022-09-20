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
/**
 * Represents a string of Unicode code points encoded as UTF-8.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 * @author Hugo Hamon <hugohamon@neuf.fr>
 *
 * @throws ExceptionInterface
 */
class CodePointString extends AbstractUnicodeString
{
    /**
     *
     */
    public function __construct(string $string = '')
    {
        if ('' !== $string && !\preg_match('//u', $string)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
        $this->string = $string;
    }
    /**
     * @return static
     */
    public function append(string ...$suffix)
    {
        $str = clone $this;
        $str->string .= 1 >= \count($suffix) ? $suffix[0] ?? '' : \implode('', $suffix);
        if (!\preg_match('//u', $str->string)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
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
        $rx = '/(';
        while (65535 < $length) {
            $rx .= '.{65535}';
            $length -= 65535;
        }
        $rx .= '.{' . $length . '})/us';
        $str = clone $this;
        $chunks = [];
        foreach (\preg_split($rx, $this->string, -1, \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_NO_EMPTY) as $chunk) {
            $str->string = $chunk;
            $chunks[] = clone $str;
        }
        return $chunks;
    }
    /**
     *
     */
    public function codePointsAt(int $offset) : array
    {
        $str = $offset ? $this->slice($offset, 1) : $this;
        return '' === $str->string ? [] : [\mb_ord($str->string, 'UTF-8')];
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
        if ('' === $suffix || !\preg_match('//u', $suffix)) {
            return \false;
        }
        if ($this->ignoreCase) {
            return \preg_match('{' . \preg_quote($suffix) . '$}iuD', $this->string);
        }
        return \strlen($this->string) >= \strlen($suffix) && 0 === \Phabel\Target\Php80\Polyfill::substr_compare($this->string, $suffix, -\strlen($suffix));
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
            return \strlen($string) === \strlen($this->string) && 0 === \mb_stripos($this->string, $string, 0, 'UTF-8');
        }
        return $string === $this->string;
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
        $i = $this->ignoreCase ? \mb_stripos($this->string, $needle, $offset, 'UTF-8') : \mb_strpos($this->string, $needle, $offset, 'UTF-8');
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
        $i = $this->ignoreCase ? \mb_strripos($this->string, $needle, $offset, 'UTF-8') : \mb_strrpos($this->string, $needle, $offset, 'UTF-8');
        return \false === $i ? null : $i;
    }
    /**
     *
     */
    public function length() : int
    {
        return \mb_strlen($this->string, 'UTF-8');
    }
    /**
     * @return static
     */
    public function prepend(string ...$prefix)
    {
        $str = clone $this;
        $str->string = (1 >= \count($prefix) ? $prefix[0] ?? '' : \implode('', $prefix)) . $this->string;
        if (!\preg_match('//u', $str->string)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
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
        if ('' === $from || !\preg_match('//u', $from)) {
            $phabelReturn = $str;
            if (!$phabelReturn instanceof static) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . static::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ('' !== $to && !\preg_match('//u', $to)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
        if ($this->ignoreCase) {
            $str->string = \implode($to, \preg_split('{' . \preg_quote($from) . '}iuD', $this->string));
        } else {
            $str->string = \str_replace($from, $to, $this->string);
        }
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
        $str->string = \mb_substr($this->string, $start, $length, 'UTF-8');
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
        if (!\preg_match('//u', $replacement)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
        $str = clone $this;
        $start = $start ? \strlen(\mb_substr($this->string, 0, $start, 'UTF-8')) : 0;
        $length = $length ? \strlen(\mb_substr($this->string, $start, $length, 'UTF-8')) : $length;
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
            return parent::split($delimiter . 'u', $limit, $flags);
        }
        if (!\preg_match('//u', $delimiter)) {
            throw new InvalidArgumentException('Split delimiter is not a valid UTF-8 string.');
        }
        $str = clone $this;
        $chunks = $this->ignoreCase ? \preg_split('{' . \preg_quote($delimiter) . '}iuD', $this->string, $limit) : \explode($delimiter, $this->string, $limit);
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
        if ('' === $prefix || !\preg_match('//u', $prefix)) {
            return \false;
        }
        if ($this->ignoreCase) {
            return 0 === \mb_stripos($this->string, $prefix, 0, 'UTF-8');
        }
        return 0 === \strncmp($this->string, $prefix, \strlen($prefix));
    }
}
