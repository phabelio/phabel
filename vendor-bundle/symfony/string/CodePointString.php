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
 * Represents a string of Unicode code points encoded as UTF-8.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 * @author Hugo Hamon <hugohamon@neuf.fr>
 *
 * @throws ExceptionInterface
 */
class CodePointString extends AbstractUnicodeString
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
        if ('' !== $string && !\preg_match('//u', $string)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
        $this->string = $string;
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
        if (!\preg_match('//u', $str->string)) {
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
        $phabelReturn = $chunks;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function codePointsAt($offset)
    {
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $offset = (int) $offset;
            }
        }
        $str = $offset ? $this->slice($offset, 1) : $this;
        $phabelReturn = '' === $str->string ? [] : [\mb_ord($str->string, 'UTF-8')];
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
        if ('' === $suffix || !\preg_match('//u', $suffix)) {
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
            $phabelReturn = \preg_match('{' . \preg_quote($suffix) . '$}iuD', $this->string);
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (bool) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = \strlen($this->string) >= \strlen($suffix) && 0 === \substr_compare($this->string, $suffix, -\strlen($suffix));
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
        if ('' !== $string && $this->ignoreCase) {
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
        $i = $this->ignoreCase ? \mb_stripos($this->string, $needle, $offset, 'UTF-8') : \mb_strpos($this->string, $needle, $offset, 'UTF-8');
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
        $i = $this->ignoreCase ? \mb_strripos($this->string, $needle, $offset, 'UTF-8') : \mb_strrpos($this->string, $needle, $offset, 'UTF-8');
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
    public function length()
    {
        $phabelReturn = \mb_strlen($this->string, 'UTF-8');
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
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
        if (!\preg_match('//u', $str->string)) {
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
        if ('' === $from || !\preg_match('//u', $from)) {
            $phabelReturn = $str;
            if (!$phabelReturn instanceof AbstractString) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type AbstractString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
        $str->string = \mb_substr($this->string, $start, $length, 'UTF-8');
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
        if (!\preg_match('//u', $replacement)) {
            throw new InvalidArgumentException('Invalid UTF-8 string.');
        }
        $str = clone $this;
        $start = $start ? \strlen(\mb_substr($this->string, 0, $start, 'UTF-8')) : 0;
        $length = $length ? \strlen(\mb_substr($this->string, $start, $length, 'UTF-8')) : $length;
        $str->string = \substr_replace($this->string, $replacement, $start, isset($length) ? $length : \PHP_INT_MAX);
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
        if (1 > ($limit = isset($limit) ? $limit : \PHP_INT_MAX)) {
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
        if (!\preg_match('//u', $delimiter)) {
            throw new InvalidArgumentException('Split delimiter is not a valid UTF-8 string.');
        }
        $str = clone $this;
        $chunks = $this->ignoreCase ? \preg_split('{' . \preg_quote($delimiter) . '}iuD', $this->string, $limit) : \explode($delimiter, $this->string, $limit);
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
        if ('' === $prefix || !\preg_match('//u', $prefix)) {
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
            $phabelReturn = 0 === \mb_stripos($this->string, $prefix, 0, 'UTF-8');
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (bool) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = 0 === \strncmp($this->string, $prefix, \strlen($prefix));
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
