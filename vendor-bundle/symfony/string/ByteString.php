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
 * Represents a binary-safe string of bytes.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 * @author Hugo Hamon <hugohamon@neuf.fr>
 *
 * @throws ExceptionInterface
 */
class ByteString extends AbstractString
{
    const ALPHABET_ALPHANUMERIC = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
    public function __construct($string = '')
    {
        if (!\is_string($string)) {
            if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $string = (string) $string;
            }
        }
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
    public static function fromRandom($length = 16, $alphabet = null)
    {
        if (!\is_int($length)) {
            if (!(\is_bool($length) || \is_numeric($length))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($length) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $length = (int) $length;
            }
        }
        if (!\is_null($alphabet)) {
            if (!\is_string($alphabet)) {
                if (!(\is_string($alphabet) || \is_object($alphabet) && \method_exists($alphabet, '__toString') || (\is_bool($alphabet) || \is_numeric($alphabet)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($alphabet) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($alphabet) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $alphabet = (string) $alphabet;
                }
            }
        }
        if ($length <= 0) {
            throw new InvalidArgumentException(\sprintf('A strictly positive length is expected, "%d" given.', $length));
        }
        $alphabet = isset($alphabet) ? $alphabet : self::ALPHABET_ALPHANUMERIC;
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
        $phabelReturn = new static($ret);
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function bytesAt($offset)
    {
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $offset = (int) $offset;
            }
        }
        $str = isset($this->string[$offset]) ? $this->string[$offset] : '';
        $phabelReturn = '' === $str ? [] : [\ord($str)];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function append(...$suffix)
    {
        foreach ($suffix as $phabelVariadicIndex => $phabelVariadic) {
            if (!\is_string($phabelVariadic)) {
                if (!(\is_string($phabelVariadic) || \is_object($phabelVariadic) && \method_exists($phabelVariadic, '__toString') || (\is_bool($phabelVariadic) || \is_numeric($phabelVariadic)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #' . (1 + $phabelVariadicIndex) . ' must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($suffix) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelVariadic = (string) $phabelVariadic;
                }
            }
        }
        $str = clone $this;
        $str->string .= 1 >= \count($suffix) ? isset($suffix[0]) ? $suffix[0] : '' : \implode('', $suffix);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function camel()
    {
        $str = clone $this;
        $str->string = \lcfirst(\str_replace(' ', '', \ucwords(\preg_replace('/[^a-zA-Z0-9\\x7f-\\xff]++/', ' ', $this->string))));
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function chunk($length = 1)
    {
        if (!\is_int($length)) {
            if (!(\is_bool($length) || \is_numeric($length))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($length) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $length = (int) $length;
            }
        }
        if (1 > $length) {
            throw new InvalidArgumentException('The chunk length must be greater than zero.');
        }
        if ('' === $this->string) {
            $phabelReturn = [];
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $str = clone $this;
        $chunks = [];
        foreach (\str_split($this->string, $length) as $chunk) {
            $str->string = $chunk;
            $chunks[] = clone $str;
        }
        $phabelReturn = $chunks;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function endsWith($suffix)
    {
        if ($suffix instanceof parent) {
            $suffix = $suffix->string;
        } elseif (\is_array($suffix) || $suffix instanceof \Traversable) {
            $phabelReturn = parent::endsWith($suffix);
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (bool) $phabelReturn;
                }
            }
            return $phabelReturn;
        } else {
            $suffix = (string) $suffix;
        }
        $phabelReturn = '' !== $suffix && \strlen($this->string) >= \strlen($suffix) && 0 === \substr_compare($this->string, $suffix, -\strlen($suffix), null, $this->ignoreCase);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function equalsTo($string)
    {
        if ($string instanceof parent) {
            $string = $string->string;
        } elseif (\is_array($string) || $string instanceof \Traversable) {
            $phabelReturn = parent::equalsTo($string);
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (bool) $phabelReturn;
                }
            }
            return $phabelReturn;
        } else {
            $string = (string) $string;
        }
        if ('' !== $string && $this->ignoreCase) {
            $phabelReturn = 0 === \strcasecmp($string, $this->string);
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (bool) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = $string === $this->string;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function folded()
    {
        $str = clone $this;
        $str->string = \strtolower($str->string);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function indexOf($needle, $offset = 0)
    {
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $offset = (int) $offset;
            }
        }
        if ($needle instanceof parent) {
            $needle = $needle->string;
        } elseif (\is_array($needle) || $needle instanceof \Traversable) {
            $phabelReturn = parent::indexOf($needle, $offset);
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
        } else {
            $needle = (string) $needle;
        }
        if ('' === $needle) {
            $phabelReturn = null;
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
        $i = $this->ignoreCase ? \stripos($this->string, $needle, $offset) : \strpos($this->string, $needle, $offset);
        $phabelReturn = \false === $i ? null : $i;
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
    public function indexOfLast($needle, $offset = 0)
    {
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $offset = (int) $offset;
            }
        }
        if ($needle instanceof parent) {
            $needle = $needle->string;
        } elseif (\is_array($needle) || $needle instanceof \Traversable) {
            $phabelReturn = parent::indexOfLast($needle, $offset);
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
        } else {
            $needle = (string) $needle;
        }
        if ('' === $needle) {
            $phabelReturn = null;
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
        $i = $this->ignoreCase ? \strripos($this->string, $needle, $offset) : \strrpos($this->string, $needle, $offset);
        $phabelReturn = \false === $i ? null : $i;
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
    public function isUtf8()
    {
        $phabelReturn = '' === $this->string || \preg_match('//u', $this->string);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function join(array $strings, $lastGlue = null)
    {
        if (!\is_null($lastGlue)) {
            if (!\is_string($lastGlue)) {
                if (!(\is_string($lastGlue) || \is_object($lastGlue) && \method_exists($lastGlue, '__toString') || (\is_bool($lastGlue) || \is_numeric($lastGlue)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($lastGlue) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($lastGlue) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $lastGlue = (string) $lastGlue;
                }
            }
        }
        $str = clone $this;
        $tail = null !== $lastGlue && 1 < \count($strings) ? $lastGlue . \array_pop($strings) : '';
        $str->string = \implode($this->string, $strings) . $tail;
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function length()
    {
        $phabelReturn = \strlen($this->string);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function lower()
    {
        $str = clone $this;
        $str->string = \strtolower($str->string);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function match($regexp, $flags = 0, $offset = 0)
    {
        if (!\is_string($regexp)) {
            if (!(\is_string($regexp) || \is_object($regexp) && \method_exists($regexp, '__toString') || (\is_bool($regexp) || \is_numeric($regexp)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($regexp) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($regexp) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $regexp = (string) $regexp;
            }
        }
        if (!\is_int($flags)) {
            if (!(\is_bool($flags) || \is_numeric($flags))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($flags) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($flags) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $flags = (int) $flags;
            }
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $offset = (int) $offset;
            }
        }
        $match = (\PREG_PATTERN_ORDER | \PREG_SET_ORDER) & $flags ? 'preg_match_all' : 'preg_match';
        if ($this->ignoreCase) {
            $regexp .= 'i';
        }
        \set_error_handler(static function ($t, $m) {
            throw new InvalidArgumentException($m);
        });
        try {
            if (\false === $match($regexp, $this->string, $matches, $flags | \PREG_UNMATCHED_AS_NULL, $offset)) {
                $lastError = \preg_last_error();
                foreach (\get_defined_constants(\true)['pcre'] as $k => $v) {
                    if ($lastError === $v && '_ERROR' === \substr($k, -6)) {
                        throw new RuntimeException('Matching failed with ' . $k . '.');
                    }
                }
                throw new RuntimeException('Matching failed with unknown error code.');
            }
        } finally {
            \restore_error_handler();
        }
        $phabelReturn = $matches;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function padBoth($length, $padStr = ' ')
    {
        if (!\is_int($length)) {
            if (!(\is_bool($length) || \is_numeric($length))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($length) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $length = (int) $length;
            }
        }
        if (!\is_string($padStr)) {
            if (!(\is_string($padStr) || \is_object($padStr) && \method_exists($padStr, '__toString') || (\is_bool($padStr) || \is_numeric($padStr)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($padStr) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($padStr) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $padStr = (string) $padStr;
            }
        }
        $str = clone $this;
        $str->string = \str_pad($this->string, $length, $padStr, \STR_PAD_BOTH);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function padEnd($length, $padStr = ' ')
    {
        if (!\is_int($length)) {
            if (!(\is_bool($length) || \is_numeric($length))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($length) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $length = (int) $length;
            }
        }
        if (!\is_string($padStr)) {
            if (!(\is_string($padStr) || \is_object($padStr) && \method_exists($padStr, '__toString') || (\is_bool($padStr) || \is_numeric($padStr)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($padStr) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($padStr) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $padStr = (string) $padStr;
            }
        }
        $str = clone $this;
        $str->string = \str_pad($this->string, $length, $padStr, \STR_PAD_RIGHT);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function padStart($length, $padStr = ' ')
    {
        if (!\is_int($length)) {
            if (!(\is_bool($length) || \is_numeric($length))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($length) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $length = (int) $length;
            }
        }
        if (!\is_string($padStr)) {
            if (!(\is_string($padStr) || \is_object($padStr) && \method_exists($padStr, '__toString') || (\is_bool($padStr) || \is_numeric($padStr)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($padStr) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($padStr) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $padStr = (string) $padStr;
            }
        }
        $str = clone $this;
        $str->string = \str_pad($this->string, $length, $padStr, \STR_PAD_LEFT);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function prepend(...$prefix)
    {
        foreach ($prefix as $phabelVariadicIndex => $phabelVariadic) {
            if (!\is_string($phabelVariadic)) {
                if (!(\is_string($phabelVariadic) || \is_object($phabelVariadic) && \method_exists($phabelVariadic, '__toString') || (\is_bool($phabelVariadic) || \is_numeric($phabelVariadic)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #' . (1 + $phabelVariadicIndex) . ' must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($prefix) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelVariadic = (string) $phabelVariadic;
                }
            }
        }
        $str = clone $this;
        $str->string = (1 >= \count($prefix) ? isset($prefix[0]) ? $prefix[0] : '' : \implode('', $prefix)) . $str->string;
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function replace($from, $to)
    {
        if (!\is_string($from)) {
            if (!(\is_string($from) || \is_object($from) && \method_exists($from, '__toString') || (\is_bool($from) || \is_numeric($from)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($from) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($from) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $from = (string) $from;
            }
        }
        if (!\is_string($to)) {
            if (!(\is_string($to) || \is_object($to) && \method_exists($to, '__toString') || (\is_bool($to) || \is_numeric($to)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($to) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($to) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $to = (string) $to;
            }
        }
        $str = clone $this;
        if ('' !== $from) {
            $str->string = $this->ignoreCase ? \str_ireplace($from, $to, $this->string) : \str_replace($from, $to, $this->string);
        }
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function replaceMatches($fromRegexp, $to)
    {
        if (!\is_string($fromRegexp)) {
            if (!(\is_string($fromRegexp) || \is_object($fromRegexp) && \method_exists($fromRegexp, '__toString') || (\is_bool($fromRegexp) || \is_numeric($fromRegexp)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($fromRegexp) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($fromRegexp) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $fromRegexp = (string) $fromRegexp;
            }
        }
        if ($this->ignoreCase) {
            $fromRegexp .= 'i';
        }
        if (\is_array($to)) {
            if (!\is_callable($to)) {
                throw new \TypeError(\sprintf('Argument 2 passed to "%s::replaceMatches()" must be callable, array given.', static::class));
            }
            $replace = 'preg_replace_callback';
        } else {
            $replace = $to instanceof \Closure ? 'preg_replace_callback' : 'preg_replace';
        }
        \set_error_handler(static function ($t, $m) {
            throw new InvalidArgumentException($m);
        });
        try {
            if (null === ($string = $replace($fromRegexp, $to, $this->string))) {
                $lastError = \preg_last_error();
                foreach (\get_defined_constants(\true)['pcre'] as $k => $v) {
                    if ($lastError === $v && '_ERROR' === \substr($k, -6)) {
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
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function reverse()
    {
        $str = clone $this;
        $str->string = \strrev($str->string);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function slice($start = 0, $length = null)
    {
        if (!\is_int($start)) {
            if (!(\is_bool($start) || \is_numeric($start))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($start) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($start) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $start = (int) $start;
            }
        }
        if (!\is_null($length)) {
            if (!\is_int($length)) {
                if (!(\is_bool($length) || \is_numeric($length))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($length) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $length = (int) $length;
                }
            }
        }
        $str = clone $this;
        $str->string = (string) \substr($this->string, $start, isset($length) ? $length : \PHP_INT_MAX);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function snake()
    {
        $str = $this->camel()->title();
        $str->string = \strtolower(\preg_replace(['/([A-Z]+)([A-Z][a-z])/', '/([a-z\\d])([A-Z])/'], 'Phabel\\1_\\2', $str->string));
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function splice($replacement, $start = 0, $length = null)
    {
        if (!\is_string($replacement)) {
            if (!(\is_string($replacement) || \is_object($replacement) && \method_exists($replacement, '__toString') || (\is_bool($replacement) || \is_numeric($replacement)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($replacement) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($replacement) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $replacement = (string) $replacement;
            }
        }
        if (!\is_int($start)) {
            if (!(\is_bool($start) || \is_numeric($start))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($start) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($start) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $start = (int) $start;
            }
        }
        if (!\is_null($length)) {
            if (!\is_int($length)) {
                if (!(\is_bool($length) || \is_numeric($length))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($length) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $length = (int) $length;
                }
            }
        }
        $str = clone $this;
        $str->string = \substr_replace($this->string, $replacement, $start, isset($length) ? $length : \PHP_INT_MAX);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
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
        if (1 > ($limit = isset($limit) ? $limit : \PHP_INT_MAX)) {
            throw new InvalidArgumentException('Split limit must be a positive integer.');
        }
        if ('' === $delimiter) {
            throw new InvalidArgumentException('Split delimiter is empty.');
        }
        if (null !== $flags) {
            $phabelReturn = parent::split($delimiter, $limit, $flags);
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $str = clone $this;
        $chunks = $this->ignoreCase ? \preg_split('{' . \preg_quote($delimiter) . '}iD', $this->string, $limit) : \explode($delimiter, $this->string, $limit);
        foreach ($chunks as &$chunk) {
            $str->string = $chunk;
            $chunk = clone $str;
        }
        $phabelReturn = $chunks;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function startsWith($prefix)
    {
        if ($prefix instanceof parent) {
            $prefix = $prefix->string;
        } elseif (!\is_string($prefix)) {
            $phabelReturn = parent::startsWith($prefix);
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (bool) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = '' !== $prefix && 0 === ($this->ignoreCase ? \strncasecmp($this->string, $prefix, \strlen($prefix)) : \strncmp($this->string, $prefix, \strlen($prefix)));
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function title($allWords = \false)
    {
        if (!\is_bool($allWords)) {
            if (!(\is_bool($allWords) || \is_numeric($allWords) || \is_string($allWords))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($allWords) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($allWords) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $allWords = (bool) $allWords;
            }
        }
        $str = clone $this;
        $str->string = $allWords ? \ucwords($str->string) : \ucfirst($str->string);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function toUnicodeString($fromEncoding = null)
    {
        if (!\is_null($fromEncoding)) {
            if (!\is_string($fromEncoding)) {
                if (!(\is_string($fromEncoding) || \is_object($fromEncoding) && \method_exists($fromEncoding, '__toString') || (\is_bool($fromEncoding) || \is_numeric($fromEncoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($fromEncoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($fromEncoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $fromEncoding = (string) $fromEncoding;
                }
            }
        }
        $phabelReturn = new UnicodeString($this->toCodePointString($fromEncoding)->string);
        if (!$phabelReturn instanceof UnicodeString) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type UnicodeString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function toCodePointString($fromEncoding = null)
    {
        if (!\is_null($fromEncoding)) {
            if (!\is_string($fromEncoding)) {
                if (!(\is_string($fromEncoding) || \is_object($fromEncoding) && \method_exists($fromEncoding, '__toString') || (\is_bool($fromEncoding) || \is_numeric($fromEncoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($fromEncoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($fromEncoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $fromEncoding = (string) $fromEncoding;
                }
            }
        }
        $u = new CodePointString();
        if (\in_array($fromEncoding, [null, 'utf8', 'utf-8', 'UTF8', 'UTF-8'], \true) && \preg_match('//u', $this->string)) {
            $u->string = $this->string;
            $phabelReturn = $u;
            if (!$phabelReturn instanceof CodePointString) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type CodePointString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        \set_error_handler(static function ($t, $m) {
            throw new InvalidArgumentException($m);
        });
        try {
            try {
                $validEncoding = \false !== \mb_detect_encoding($this->string, isset($fromEncoding) ? $fromEncoding : 'Windows-1252', \true);
            } catch (InvalidArgumentException $e) {
                if (!\function_exists('iconv')) {
                    throw $e;
                }
                $u->string = \iconv(isset($fromEncoding) ? $fromEncoding : 'Windows-1252', 'UTF-8', $this->string);
                $phabelReturn = $u;
                if (!$phabelReturn instanceof CodePointString) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type CodePointString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
        } finally {
            \restore_error_handler();
        }
        if (!$validEncoding) {
            throw new InvalidArgumentException(\sprintf('Invalid "%s" string.', isset($fromEncoding) ? $fromEncoding : 'Windows-1252'));
        }
        $u->string = \mb_convert_encoding($this->string, 'UTF-8', isset($fromEncoding) ? $fromEncoding : 'Windows-1252');
        $phabelReturn = $u;
        if (!$phabelReturn instanceof CodePointString) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type CodePointString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function trim($chars = " \t\n\r\x00\v\f")
    {
        if (!\is_string($chars)) {
            if (!(\is_string($chars) || \is_object($chars) && \method_exists($chars, '__toString') || (\is_bool($chars) || \is_numeric($chars)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($chars) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($chars) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $chars = (string) $chars;
            }
        }
        $str = clone $this;
        $str->string = \trim($str->string, $chars);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function trimEnd($chars = " \t\n\r\x00\v\f")
    {
        if (!\is_string($chars)) {
            if (!(\is_string($chars) || \is_object($chars) && \method_exists($chars, '__toString') || (\is_bool($chars) || \is_numeric($chars)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($chars) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($chars) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $chars = (string) $chars;
            }
        }
        $str = clone $this;
        $str->string = \rtrim($str->string, $chars);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function trimStart($chars = " \t\n\r\x00\v\f")
    {
        if (!\is_string($chars)) {
            if (!(\is_string($chars) || \is_object($chars) && \method_exists($chars, '__toString') || (\is_bool($chars) || \is_numeric($chars)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($chars) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($chars) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $chars = (string) $chars;
            }
        }
        $str = clone $this;
        $str->string = \ltrim($str->string, $chars);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function upper()
    {
        $str = clone $this;
        $str->string = \strtoupper($str->string);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function width($ignoreAnsiDecoration = \true)
    {
        if (!\is_bool($ignoreAnsiDecoration)) {
            if (!(\is_bool($ignoreAnsiDecoration) || \is_numeric($ignoreAnsiDecoration) || \is_string($ignoreAnsiDecoration))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($ignoreAnsiDecoration) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($ignoreAnsiDecoration) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $ignoreAnsiDecoration = (bool) $ignoreAnsiDecoration;
            }
        }
        $string = \preg_match('//u', $this->string) ? $this->string : \preg_replace('/[\\x80-\\xFF]/', '?', $this->string);
        $phabelReturn = (new CodePointString($string))->width($ignoreAnsiDecoration);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
