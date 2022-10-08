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
 * Represents a string of Unicode grapheme clusters encoded as UTF-8.
 *
 * A letter followed by combining characters (accents typically) form what Unicode defines
 * as a grapheme cluster: a character as humans mean it in written texts. This class knows
 * about the concept and won't split a letter apart from its combining accents. It also
 * ensures all string comparisons happen on their canonically-composed representation,
 * ignoring e.g. the order in which accents are listed when a letter has many of them.
 *
 * @see https://unicode.org/reports/tr15/
 *
 * @author Nicolas Grekas <p@tchwork.com>
 * @author Hugo Hamon <hugohamon@neuf.fr>
 *
 * @throws ExceptionInterface
 */
class UnicodeString extends AbstractUnicodeString
{
    /**
     *
     */
    public function __construct(string $string = '')
    {
        $this->string = \normalizer_is_normalized($string) ? $string : \normalizer_normalize($string);
        if (\false === $this->string) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
    }
    /**
     * @return static
     */
    public function append(string ...$suffix)
    {
        $str = clone $this;
        $str->string = $this->string . (1 >= \count($suffix) ? $suffix[0] ?? '' : \implode('', $suffix));
        \normalizer_is_normalized($str->string) ?: ($str->string = \normalizer_normalize($str->string));
        if (\false === $str->string) {
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
            $rx .= '\\X{65535}';
            $length -= 65535;
        }
        $rx .= '\\X{' . $length . '})/u';
        $str = clone $this;
        $chunks = [];
        foreach (\preg_split($rx, $this->string, -1, \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_NO_EMPTY) as $chunk) {
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
        $form = null === $this->ignoreCase ? \Normalizer::NFD : \Normalizer::NFC;
        \normalizer_is_normalized($suffix, $form) ?: ($suffix = \normalizer_normalize($suffix, $form));
        if ('' === $suffix || \false === $suffix) {
            return \false;
        }
        if ($this->ignoreCase) {
            return 0 === \mb_stripos(\grapheme_extract($this->string, \strlen($suffix), \GRAPHEME_EXTR_MAXBYTES, \strlen($this->string) - \strlen($suffix)), $suffix, 0, 'UTF-8');
        }
        return $suffix === \grapheme_extract($this->string, \strlen($suffix), \GRAPHEME_EXTR_MAXBYTES, \strlen($this->string) - \strlen($suffix));
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
        $form = null === $this->ignoreCase ? \Normalizer::NFD : \Normalizer::NFC;
        \normalizer_is_normalized($string, $form) ?: ($string = \normalizer_normalize($string, $form));
        if ('' !== $string && \false !== $string && $this->ignoreCase) {
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
        $form = null === $this->ignoreCase ? \Normalizer::NFD : \Normalizer::NFC;
        \normalizer_is_normalized($needle, $form) ?: ($needle = \normalizer_normalize($needle, $form));
        if ('' === $needle || \false === $needle) {
            return null;
        }
        try {
            $i = $this->ignoreCase ? \grapheme_stripos($this->string, $needle, $offset) : \grapheme_strpos($this->string, $needle, $offset);
        } catch (\ValueError $phabel_dc7f48eddcee6734) {
            return null;
        }
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
        $form = null === $this->ignoreCase ? \Normalizer::NFD : \Normalizer::NFC;
        \normalizer_is_normalized($needle, $form) ?: ($needle = \normalizer_normalize($needle, $form));
        if ('' === $needle || \false === $needle) {
            return null;
        }
        $string = $this->string;
        if (0 > $offset) {
            // workaround https://bugs.php.net/74264
            if (0 > ($offset += \grapheme_strlen($needle))) {
                $string = \grapheme_substr($string, 0, $offset);
            }
            $offset = 0;
        }
        $i = $this->ignoreCase ? \grapheme_strripos($string, $needle, $offset) : \grapheme_strrpos($string, $needle, $offset);
        return \false === $i ? null : $i;
    }
    /**
     * @return static
     */
    public function join(array $strings, string $lastGlue = NULL)
    {
        $str = parent::join($strings, $lastGlue);
        \normalizer_is_normalized($str->string) ?: ($str->string = \normalizer_normalize($str->string));
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
        return \grapheme_strlen($this->string);
    }
    /**
     * @return static
     */
    public function normalize(int $form = self::NFC)
    {
        $str = clone $this;
        if (\in_array($form, [self::NFC, self::NFKC], \true)) {
            \normalizer_is_normalized($str->string, $form) ?: ($str->string = \normalizer_normalize($str->string, $form));
        } elseif (!\in_array($form, [self::NFD, self::NFKD], \true)) {
            throw new InvalidArgumentException('Unsupported normalization form.');
        } elseif (!\normalizer_is_normalized($str->string, $form)) {
            $str->string = \normalizer_normalize($str->string, $form);
            $str->ignoreCase = null;
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
    public function prepend(string ...$prefix)
    {
        $str = clone $this;
        $str->string = (1 >= \count($prefix) ? $prefix[0] ?? '' : \implode('', $prefix)) . $this->string;
        \normalizer_is_normalized($str->string) ?: ($str->string = \normalizer_normalize($str->string));
        if (\false === $str->string) {
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
        \normalizer_is_normalized($from) ?: ($from = \normalizer_normalize($from));
        if ('' !== $from && \false !== $from) {
            $tail = $str->string;
            $result = '';
            $indexOf = $this->ignoreCase ? 'grapheme_stripos' : 'grapheme_strpos';
            while ('' !== $tail && \false !== ($i = $indexOf($tail, $from))) {
                $slice = \grapheme_substr($tail, 0, $i);
                $result .= $slice . $to;
                $tail = \Phabel\Target\Php80\Polyfill::substr($tail, \strlen($slice) + \strlen($from));
            }
            $str->string = $result . $tail;
            \normalizer_is_normalized($str->string) ?: ($str->string = \normalizer_normalize($str->string));
            if (\false === $str->string) {
                throw new InvalidArgumentException('Invalid UTF-8 string.');
            }
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
        $str = parent::replaceMatches($fromRegexp, $to);
        \normalizer_is_normalized($str->string) ?: ($str->string = \normalizer_normalize($str->string));
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
        $str->string = (string) \grapheme_substr($this->string, $start, $length ?? 2147483647);
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
        $start = $start ? \strlen(\grapheme_substr($this->string, 0, $start)) : 0;
        $length = $length ? \strlen(\grapheme_substr($this->string, $start, $length ?? 2147483647)) : $length;
        $str->string = \Phabel\Target\Php80\Polyfill::substr_replace($this->string, $replacement, $start, $length ?? 2147483647);
        \normalizer_is_normalized($str->string) ?: ($str->string = \normalizer_normalize($str->string));
        if (\false === $str->string) {
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
    public function split(string $delimiter, int $limit = NULL, int $flags = NULL) : array
    {
        if (1 > ($limit = $limit ?? 2147483647)) {
            throw new InvalidArgumentException('Split limit must be a positive integer.');
        }
        if ('' === $delimiter) {
            throw new InvalidArgumentException('Split delimiter is empty.');
        }
        if (null !== $flags) {
            return parent::split($delimiter . 'u', $limit, $flags);
        }
        \normalizer_is_normalized($delimiter) ?: ($delimiter = \normalizer_normalize($delimiter));
        if (\false === $delimiter) {
            throw new InvalidArgumentException('Split delimiter is not a valid UTF-8 string.');
        }
        $str = clone $this;
        $tail = $this->string;
        $chunks = [];
        $indexOf = $this->ignoreCase ? 'grapheme_stripos' : 'grapheme_strpos';
        while (1 < $limit && \false !== ($i = $indexOf($tail, $delimiter))) {
            $str->string = \grapheme_substr($tail, 0, $i);
            $chunks[] = clone $str;
            $tail = \Phabel\Target\Php80\Polyfill::substr($tail, \strlen($str->string) + \strlen($delimiter));
            --$limit;
        }
        $str->string = $tail;
        $chunks[] = clone $str;
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
        $form = null === $this->ignoreCase ? \Normalizer::NFD : \Normalizer::NFC;
        \normalizer_is_normalized($prefix, $form) ?: ($prefix = \normalizer_normalize($prefix, $form));
        if ('' === $prefix || \false === $prefix) {
            return \false;
        }
        if ($this->ignoreCase) {
            return 0 === \mb_stripos(\grapheme_extract($this->string, \strlen($prefix), \GRAPHEME_EXTR_MAXBYTES), $prefix, 0, 'UTF-8');
        }
        return $prefix === \grapheme_extract($this->string, \strlen($prefix), \GRAPHEME_EXTR_MAXBYTES);
    }
    /**
     *
     */
    public function __wakeup()
    {
        if (!\is_string($this->string)) {
            throw new \BadMethodCallException('Cannot unserialize ' . __CLASS__);
        }
        \normalizer_is_normalized($this->string) ?: ($this->string = \normalizer_normalize($this->string));
    }
    /**
     *
     */
    public function __clone()
    {
        if (null === $this->ignoreCase) {
            \normalizer_is_normalized($this->string) ?: ($this->string = \normalizer_normalize($this->string));
        }
        $this->ignoreCase = \false;
    }
}
