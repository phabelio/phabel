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
    public function __construct($string = '')
    {
        if (!\is_string($string)) {
            if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $string = (string) $string;
            }
        }
        $this->string = \normalizer_is_normalized($string) ? $string : \normalizer_normalize($string);
        if (\false === $this->string) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
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
        $str->string = $this->string . (1 >= \count($suffix) ? isset($suffix[0]) ? $suffix[0] : '' : \implode('', $suffix));
        \normalizer_is_normalized($str->string) ?: ($str->string = \normalizer_normalize($str->string));
        if (\false === $str->string) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
        $phabelReturn = $str;
        if (!$phabelReturn instanceof AbstractString) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type AbstractString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
        $phabelReturn = $chunks;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function endsWith($suffix)
    {
        if ($suffix instanceof AbstractString) {
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
        $form = null === $this->ignoreCase ? \Normalizer::NFD : \Normalizer::NFC;
        \normalizer_is_normalized($suffix, $form) ?: ($suffix = \normalizer_normalize($suffix, $form));
        if ('' === $suffix || \false === $suffix) {
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
        if ($this->ignoreCase) {
            $phabelReturn = 0 === \mb_stripos(\grapheme_extract($this->string, \strlen($suffix), \GRAPHEME_EXTR_MAXBYTES, \strlen($this->string) - \strlen($suffix)), $suffix, 0, 'UTF-8');
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (bool) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = $suffix === \grapheme_extract($this->string, \strlen($suffix), \GRAPHEME_EXTR_MAXBYTES, \strlen($this->string) - \strlen($suffix));
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
        if ($string instanceof AbstractString) {
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
        $form = null === $this->ignoreCase ? \Normalizer::NFD : \Normalizer::NFC;
        \normalizer_is_normalized($string, $form) ?: ($string = \normalizer_normalize($string, $form));
        if ('' !== $string && \false !== $string && $this->ignoreCase) {
            $phabelReturn = \strlen($string) === \strlen($this->string) && 0 === \mb_stripos($this->string, $string, 0, 'UTF-8');
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
    public function indexOf($needle, $offset = 0)
    {
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $offset = (int) $offset;
            }
        }
        if ($needle instanceof AbstractString) {
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
        $form = null === $this->ignoreCase ? \Normalizer::NFD : \Normalizer::NFC;
        \normalizer_is_normalized($needle, $form) ?: ($needle = \normalizer_normalize($needle, $form));
        if ('' === $needle || \false === $needle) {
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
        try {
            $i = $this->ignoreCase ? \grapheme_stripos($this->string, $needle, $offset) : \grapheme_strpos($this->string, $needle, $offset);
        } catch (\ValueError $e) {
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
        if ($needle instanceof AbstractString) {
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
        $form = null === $this->ignoreCase ? \Normalizer::NFD : \Normalizer::NFC;
        \normalizer_is_normalized($needle, $form) ?: ($needle = \normalizer_normalize($needle, $form));
        if ('' === $needle || \false === $needle) {
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
        $string = $this->string;
        if (0 > $offset) {
            // workaround https://bugs.php.net/74264
            if (0 > ($offset += \grapheme_strlen($needle))) {
                $string = \grapheme_substr($string, 0, $offset);
            }
            $offset = 0;
        }
        $i = $this->ignoreCase ? \grapheme_strripos($string, $needle, $offset) : \grapheme_strrpos($string, $needle, $offset);
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
        $str = parent::join($strings, $lastGlue);
        \normalizer_is_normalized($str->string) ?: ($str->string = \normalizer_normalize($str->string));
        $phabelReturn = $str;
        if (!$phabelReturn instanceof AbstractString) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type AbstractString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function length()
    {
        $phabelReturn = \grapheme_strlen($this->string);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * @return static
     */
    public function normalize($form = self::NFC)
    {
        if (!\is_int($form)) {
            if (!(\is_bool($form) || \is_numeric($form))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($form) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($form) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $form = (int) $form;
            }
        }
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
        $str->string = (1 >= \count($prefix) ? isset($prefix[0]) ? $prefix[0] : '' : \implode('', $prefix)) . $this->string;
        \normalizer_is_normalized($str->string) ?: ($str->string = \normalizer_normalize($str->string));
        if (\false === $str->string) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
        $phabelReturn = $str;
        if (!$phabelReturn instanceof AbstractString) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type AbstractString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
        \normalizer_is_normalized($from) ?: ($from = \normalizer_normalize($from));
        if ('' !== $from && \false !== $from) {
            $tail = $str->string;
            $result = '';
            $indexOf = $this->ignoreCase ? 'grapheme_stripos' : 'grapheme_strpos';
            while ('' !== $tail && \false !== ($i = $indexOf($tail, $from))) {
                $slice = \grapheme_substr($tail, 0, $i);
                $result .= $slice . $to;
                $tail = \substr($tail, \strlen($slice) + \strlen($from));
            }
            $str->string = $result . $tail;
            \normalizer_is_normalized($str->string) ?: ($str->string = \normalizer_normalize($str->string));
            if (\false === $str->string) {
                throw new InvalidArgumentException('Invalid UTF-8 string.');
            }
        }
        $phabelReturn = $str;
        if (!$phabelReturn instanceof AbstractString) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type AbstractString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
        $str = parent::replaceMatches($fromRegexp, $to);
        \normalizer_is_normalized($str->string) ?: ($str->string = \normalizer_normalize($str->string));
        $phabelReturn = $str;
        if (!$phabelReturn instanceof AbstractString) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type AbstractString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
        if (\PHP_VERSION_ID < 80000 && 0 > $start && \grapheme_strlen($this->string) < -$start) {
            $start = 0;
        }
        $str->string = (string) \grapheme_substr($this->string, $start, isset($length) ? $length : 2147483647);
        $phabelReturn = $str;
        if (!$phabelReturn instanceof AbstractString) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type AbstractString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
        if (\PHP_VERSION_ID < 80000 && 0 > $start && \grapheme_strlen($this->string) < -$start) {
            $start = 0;
        }
        $start = $start ? \strlen(\grapheme_substr($this->string, 0, $start)) : 0;
        $length = $length ? \strlen(\grapheme_substr($this->string, $start, isset($length) ? $length : 2147483647)) : $length;
        $str->string = \substr_replace($this->string, $replacement, $start, isset($length) ? $length : 2147483647);
        \normalizer_is_normalized($str->string) ?: ($str->string = \normalizer_normalize($str->string));
        if (\false === $str->string) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
        $phabelReturn = $str;
        if (!$phabelReturn instanceof AbstractString) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type AbstractString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
        if (1 > ($limit = isset($limit) ? $limit : 2147483647)) {
            throw new InvalidArgumentException('Split limit must be a positive integer.');
        }
        if ('' === $delimiter) {
            throw new InvalidArgumentException('Split delimiter is empty.');
        }
        if (null !== $flags) {
            $phabelReturn = parent::split($delimiter . 'u', $limit, $flags);
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
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
            $tail = \substr($tail, \strlen($str->string) + \strlen($delimiter));
            --$limit;
        }
        $str->string = $tail;
        $chunks[] = clone $str;
        $phabelReturn = $chunks;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function startsWith($prefix)
    {
        if ($prefix instanceof AbstractString) {
            $prefix = $prefix->string;
        } elseif (\is_array($prefix) || $prefix instanceof \Traversable) {
            $phabelReturn = parent::startsWith($prefix);
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (bool) $phabelReturn;
                }
            }
            return $phabelReturn;
        } else {
            $prefix = (string) $prefix;
        }
        $form = null === $this->ignoreCase ? \Normalizer::NFD : \Normalizer::NFC;
        \normalizer_is_normalized($prefix, $form) ?: ($prefix = \normalizer_normalize($prefix, $form));
        if ('' === $prefix || \false === $prefix) {
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
        if ($this->ignoreCase) {
            $phabelReturn = 0 === \mb_stripos(\grapheme_extract($this->string, \strlen($prefix), \GRAPHEME_EXTR_MAXBYTES), $prefix, 0, 'UTF-8');
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (bool) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = $prefix === \grapheme_extract($this->string, \strlen($prefix), \GRAPHEME_EXTR_MAXBYTES);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function __wakeup()
    {
        if (!\is_string($this->string)) {
            throw new \BadMethodCallException('Cannot unserialize ' . __CLASS__);
        }
        \normalizer_is_normalized($this->string) ?: ($this->string = \normalizer_normalize($this->string));
    }
    public function __clone()
    {
        if (null === $this->ignoreCase) {
            \normalizer_is_normalized($this->string) ?: ($this->string = \normalizer_normalize($this->string));
        }
        $this->ignoreCase = \false;
    }
}
