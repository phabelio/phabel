<?php

namespace Phabel;

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Phabel\Symfony\Polyfill\Mbstring as p;
if (!\function_exists('mb_convert_encoding')) {
    function mb_convert_encoding($string, $to_encoding, $from_encoding = null)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    if (!\is_array($string)) {
                        throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type array|string|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!\is_null($to_encoding)) {
            if (!\is_string($to_encoding)) {
                if (!(\is_string($to_encoding) || \is_object($to_encoding) && \method_exists($to_encoding, '__toString') || (\is_bool($to_encoding) || \is_numeric($to_encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($to_encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($to_encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $to_encoding = (string) $to_encoding;
                }
            }
        }
        if (!(\is_null($from_encoding) || \is_null($from_encoding))) {
            if (!\is_string($from_encoding)) {
                if (!(\is_string($from_encoding) || \is_object($from_encoding) && \method_exists($from_encoding, '__toString') || (\is_bool($from_encoding) || \is_numeric($from_encoding)))) {
                    if (!\is_array($from_encoding)) {
                        throw new \TypeError(__METHOD__ . '(): Argument #3 ($from_encoding) must be of type ?array|string|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($from_encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $from_encoding = (string) $from_encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_convert_encoding(isset($string) ? $string : '', (string) $to_encoding, $from_encoding);
        if (!$phabelReturn instanceof false) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    if (!\is_array($phabelReturn)) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type false|array|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_decode_mimeheader')) {
    function mb_decode_mimeheader($string)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_decode_mimeheader((string) $string);
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
if (!\function_exists('mb_encode_mimeheader')) {
    function mb_encode_mimeheader($string, $charset = null, $transfer_encoding = null, $newline = "\r\n", $indent = 0)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!\is_null($charset)) {
            if (!\is_string($charset)) {
                if (!(\is_string($charset) || \is_object($charset) && \method_exists($charset, '__toString') || (\is_bool($charset) || \is_numeric($charset)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($charset) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($charset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $charset = (string) $charset;
                }
            }
        }
        if (!\is_null($transfer_encoding)) {
            if (!\is_string($transfer_encoding)) {
                if (!(\is_string($transfer_encoding) || \is_object($transfer_encoding) && \method_exists($transfer_encoding, '__toString') || (\is_bool($transfer_encoding) || \is_numeric($transfer_encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($transfer_encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($transfer_encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $transfer_encoding = (string) $transfer_encoding;
                }
            }
        }
        if (!\is_null($newline)) {
            if (!\is_string($newline)) {
                if (!(\is_string($newline) || \is_object($newline) && \method_exists($newline, '__toString') || (\is_bool($newline) || \is_numeric($newline)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($newline) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($newline) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $newline = (string) $newline;
                }
            }
        }
        if (!\is_null($indent)) {
            if (!\is_int($indent)) {
                if (!(\is_bool($indent) || \is_numeric($indent))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #5 ($indent) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($indent) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $indent = (int) $indent;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_encode_mimeheader((string) $string, $charset, $transfer_encoding, (string) $newline, (int) $indent);
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
if (!\function_exists('mb_decode_numericentity')) {
    function mb_decode_numericentity($string, array $map, $encoding = null)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_decode_numericentity((string) $string, $map, $encoding);
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
if (!\function_exists('mb_encode_numericentity')) {
    function mb_encode_numericentity($string, array $map, $encoding = null, $hex = \false)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        if (!\is_null($hex)) {
            if (!\is_bool($hex)) {
                if (!(\is_bool($hex) || \is_numeric($hex) || \is_string($hex))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($hex) must be of type ?bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($hex) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $hex = (bool) $hex;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_encode_numericentity((string) $string, $map, $encoding, (bool) $hex);
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
if (!\function_exists('mb_convert_case')) {
    function mb_convert_case($string, $mode, $encoding = null)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!\is_null($mode)) {
            if (!\is_int($mode)) {
                if (!(\is_bool($mode) || \is_numeric($mode))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($mode) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($mode) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $mode = (int) $mode;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_convert_case((string) $string, (int) $mode, $encoding);
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
if (!\function_exists('mb_internal_encoding')) {
    function mb_internal_encoding($encoding = null)
    {
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_internal_encoding($encoding);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_language')) {
    function mb_language($language = null)
    {
        if (!\is_null($language)) {
            if (!\is_string($language)) {
                if (!(\is_string($language) || \is_object($language) && \method_exists($language, '__toString') || (\is_bool($language) || \is_numeric($language)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($language) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($language) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $language = (string) $language;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_language($language);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_list_encodings')) {
    function mb_list_encodings()
    {
        $phabelReturn = p\Mbstring::mb_list_encodings();
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_encoding_aliases')) {
    function mb_encoding_aliases($encoding)
    {
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_encoding_aliases((string) $encoding);
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_check_encoding')) {
    function mb_check_encoding($value = null, $encoding = null)
    {
        if (!(\is_null($value) || \is_null($value))) {
            if (!\is_string($value)) {
                if (!(\is_string($value) || \is_object($value) && \method_exists($value, '__toString') || (\is_bool($value) || \is_numeric($value)))) {
                    if (!\is_array($value)) {
                        throw new \TypeError(__METHOD__ . '(): Argument #1 ($value) must be of type ?array|string|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($value) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $value = (string) $value;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_check_encoding($value, $encoding);
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
if (!\function_exists('mb_detect_encoding')) {
    function mb_detect_encoding($string, $encodings = null, $strict = \false)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!(\is_null($encodings) || \is_null($encodings))) {
            if (!\is_string($encodings)) {
                if (!(\is_string($encodings) || \is_object($encodings) && \method_exists($encodings, '__toString') || (\is_bool($encodings) || \is_numeric($encodings)))) {
                    if (!\is_array($encodings)) {
                        throw new \TypeError(__METHOD__ . '(): Argument #2 ($encodings) must be of type ?array|string|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encodings) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $encodings = (string) $encodings;
                }
            }
        }
        if (!\is_null($strict)) {
            if (!\is_bool($strict)) {
                if (!(\is_bool($strict) || \is_numeric($strict) || \is_string($strict))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($strict) must be of type ?bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($strict) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $strict = (bool) $strict;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_detect_encoding((string) $string, $encodings, (bool) $strict);
        if (!$phabelReturn instanceof false) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_detect_order')) {
    function mb_detect_order($encoding = null)
    {
        if (!(\is_null($encoding) || \is_null($encoding))) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    if (!\is_array($encoding)) {
                        throw new \TypeError(__METHOD__ . '(): Argument #1 ($encoding) must be of type ?array|string|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_detect_order($encoding);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_array($phabelReturn)) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type array|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_parse_str')) {
    function mb_parse_str($string, &$result = [])
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        \parse_str((string) $string, $result);
        $phabelReturn = (bool) $result;
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
if (!\function_exists('mb_strlen')) {
    function mb_strlen($string, $encoding = null)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_strlen((string) $string, $encoding);
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
if (!\function_exists('mb_strpos')) {
    function mb_strpos($haystack, $needle, $offset = 0, $encoding = null)
    {
        if (!\is_null($haystack)) {
            if (!\is_string($haystack)) {
                if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $haystack = (string) $haystack;
                }
            }
        }
        if (!\is_null($needle)) {
            if (!\is_string($needle)) {
                if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $needle = (string) $needle;
                }
            }
        }
        if (!\is_null($offset)) {
            if (!\is_int($offset)) {
                if (!(\is_bool($offset) || \is_numeric($offset))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $offset = (int) $offset;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_strpos((string) $haystack, (string) $needle, (int) $offset, $encoding);
        if (!$phabelReturn instanceof false) {
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_strtolower')) {
    function mb_strtolower($string, $encoding = null)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_strtolower((string) $string, $encoding);
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
if (!\function_exists('mb_strtoupper')) {
    function mb_strtoupper($string, $encoding = null)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_strtoupper((string) $string, $encoding);
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
if (!\function_exists('mb_substitute_character')) {
    function mb_substitute_character($substitute_character = null)
    {
        if (!(\is_null($substitute_character) || \is_null($substitute_character))) {
            if (!\is_int($substitute_character)) {
                if (!(\is_bool($substitute_character) || \is_numeric($substitute_character))) {
                    if (!\is_string($substitute_character)) {
                        if (!(\is_string($substitute_character) || \is_object($substitute_character) && \method_exists($substitute_character, '__toString') || (\is_bool($substitute_character) || \is_numeric($substitute_character)))) {
                            throw new \TypeError(__METHOD__ . '(): Argument #1 ($substitute_character) must be of type ?string|int|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($substitute_character) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        } else {
                            $substitute_character = (string) $substitute_character;
                        }
                    }
                } else {
                    $substitute_character = (int) $substitute_character;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_substitute_character($substitute_character);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_int($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        if (!\is_string($phabelReturn)) {
                            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                                throw new \TypeError(__METHOD__ . '(): Return value must be of type string|int|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                            } else {
                                $phabelReturn = (string) $phabelReturn;
                            }
                        }
                    } else {
                        $phabelReturn = (int) $phabelReturn;
                    }
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_substr')) {
    function mb_substr($string, $start, $length = null, $encoding = null)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!\is_null($start)) {
            if (!\is_int($start)) {
                if (!(\is_bool($start) || \is_numeric($start))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($start) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($start) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $start = (int) $start;
                }
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
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_substr((string) $string, (int) $start, $length, $encoding);
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
if (!\function_exists('mb_stripos')) {
    function mb_stripos($haystack, $needle, $offset = 0, $encoding = null)
    {
        if (!\is_null($haystack)) {
            if (!\is_string($haystack)) {
                if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $haystack = (string) $haystack;
                }
            }
        }
        if (!\is_null($needle)) {
            if (!\is_string($needle)) {
                if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $needle = (string) $needle;
                }
            }
        }
        if (!\is_null($offset)) {
            if (!\is_int($offset)) {
                if (!(\is_bool($offset) || \is_numeric($offset))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $offset = (int) $offset;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_stripos((string) $haystack, (string) $needle, (int) $offset, $encoding);
        if (!$phabelReturn instanceof false) {
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_stristr')) {
    function mb_stristr($haystack, $needle, $before_needle = \false, $encoding = null)
    {
        if (!\is_null($haystack)) {
            if (!\is_string($haystack)) {
                if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $haystack = (string) $haystack;
                }
            }
        }
        if (!\is_null($needle)) {
            if (!\is_string($needle)) {
                if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $needle = (string) $needle;
                }
            }
        }
        if (!\is_null($before_needle)) {
            if (!\is_bool($before_needle)) {
                if (!(\is_bool($before_needle) || \is_numeric($before_needle) || \is_string($before_needle))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($before_needle) must be of type ?bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($before_needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $before_needle = (bool) $before_needle;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_stristr((string) $haystack, (string) $needle, (bool) $before_needle, $encoding);
        if (!$phabelReturn instanceof false) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_strrchr')) {
    function mb_strrchr($haystack, $needle, $before_needle = \false, $encoding = null)
    {
        if (!\is_null($haystack)) {
            if (!\is_string($haystack)) {
                if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $haystack = (string) $haystack;
                }
            }
        }
        if (!\is_null($needle)) {
            if (!\is_string($needle)) {
                if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $needle = (string) $needle;
                }
            }
        }
        if (!\is_null($before_needle)) {
            if (!\is_bool($before_needle)) {
                if (!(\is_bool($before_needle) || \is_numeric($before_needle) || \is_string($before_needle))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($before_needle) must be of type ?bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($before_needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $before_needle = (bool) $before_needle;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_strrchr((string) $haystack, (string) $needle, (bool) $before_needle, $encoding);
        if (!$phabelReturn instanceof false) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_strrichr')) {
    function mb_strrichr($haystack, $needle, $before_needle = \false, $encoding = null)
    {
        if (!\is_null($haystack)) {
            if (!\is_string($haystack)) {
                if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $haystack = (string) $haystack;
                }
            }
        }
        if (!\is_null($needle)) {
            if (!\is_string($needle)) {
                if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $needle = (string) $needle;
                }
            }
        }
        if (!\is_null($before_needle)) {
            if (!\is_bool($before_needle)) {
                if (!(\is_bool($before_needle) || \is_numeric($before_needle) || \is_string($before_needle))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($before_needle) must be of type ?bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($before_needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $before_needle = (bool) $before_needle;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_strrichr((string) $haystack, (string) $needle, (bool) $before_needle, $encoding);
        if (!$phabelReturn instanceof false) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_strripos')) {
    function mb_strripos($haystack, $needle, $offset = 0, $encoding = null)
    {
        if (!\is_null($haystack)) {
            if (!\is_string($haystack)) {
                if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $haystack = (string) $haystack;
                }
            }
        }
        if (!\is_null($needle)) {
            if (!\is_string($needle)) {
                if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $needle = (string) $needle;
                }
            }
        }
        if (!\is_null($offset)) {
            if (!\is_int($offset)) {
                if (!(\is_bool($offset) || \is_numeric($offset))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $offset = (int) $offset;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_strripos((string) $haystack, (string) $needle, (int) $offset, $encoding);
        if (!$phabelReturn instanceof false) {
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_strrpos')) {
    function mb_strrpos($haystack, $needle, $offset = 0, $encoding = null)
    {
        if (!\is_null($haystack)) {
            if (!\is_string($haystack)) {
                if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $haystack = (string) $haystack;
                }
            }
        }
        if (!\is_null($needle)) {
            if (!\is_string($needle)) {
                if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $needle = (string) $needle;
                }
            }
        }
        if (!\is_null($offset)) {
            if (!\is_int($offset)) {
                if (!(\is_bool($offset) || \is_numeric($offset))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $offset = (int) $offset;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_strrpos((string) $haystack, (string) $needle, (int) $offset, $encoding);
        if (!$phabelReturn instanceof false) {
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_strstr')) {
    function mb_strstr($haystack, $needle, $before_needle = \false, $encoding = null)
    {
        if (!\is_null($haystack)) {
            if (!\is_string($haystack)) {
                if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $haystack = (string) $haystack;
                }
            }
        }
        if (!\is_null($needle)) {
            if (!\is_string($needle)) {
                if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $needle = (string) $needle;
                }
            }
        }
        if (!\is_null($before_needle)) {
            if (!\is_bool($before_needle)) {
                if (!(\is_bool($before_needle) || \is_numeric($before_needle) || \is_string($before_needle))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($before_needle) must be of type ?bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($before_needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $before_needle = (bool) $before_needle;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_strstr((string) $haystack, (string) $needle, (bool) $before_needle, $encoding);
        if (!$phabelReturn instanceof false) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_get_info')) {
    function mb_get_info($type = 'all')
    {
        if (!\is_null($type)) {
            if (!\is_string($type)) {
                if (!(\is_string($type) || \is_object($type) && \method_exists($type, '__toString') || (\is_bool($type) || \is_numeric($type)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($type) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $type = (string) $type;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_get_info((string) $type);
        if (!$phabelReturn instanceof false) {
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    if (!\is_string($phabelReturn)) {
                        if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                            if (!\is_array($phabelReturn)) {
                                throw new \TypeError(__METHOD__ . '(): Return value must be of type false|array|string|int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                            }
                        } else {
                            $phabelReturn = (string) $phabelReturn;
                        }
                    }
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_http_output')) {
    function mb_http_output($encoding = null)
    {
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_http_output($encoding);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_strwidth')) {
    function mb_strwidth($string, $encoding = null)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_strwidth((string) $string, $encoding);
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
if (!\function_exists('mb_substr_count')) {
    function mb_substr_count($haystack, $needle, $encoding = null)
    {
        if (!\is_null($haystack)) {
            if (!\is_string($haystack)) {
                if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $haystack = (string) $haystack;
                }
            }
        }
        if (!\is_null($needle)) {
            if (!\is_string($needle)) {
                if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $needle = (string) $needle;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_substr_count((string) $haystack, (string) $needle, $encoding);
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
if (!\function_exists('mb_output_handler')) {
    function mb_output_handler($string, $status)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!\is_null($status)) {
            if (!\is_int($status)) {
                if (!(\is_bool($status) || \is_numeric($status))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($status) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($status) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $status = (int) $status;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_output_handler((string) $string, (int) $status);
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
if (!\function_exists('mb_http_input')) {
    function mb_http_input($type = null)
    {
        if (!\is_null($type)) {
            if (!\is_string($type)) {
                if (!(\is_string($type) || \is_object($type) && \method_exists($type, '__toString') || (\is_bool($type) || \is_numeric($type)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($type) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $type = (string) $type;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_http_input($type);
        if (!$phabelReturn instanceof false) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    if (!\is_array($phabelReturn)) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type false|array|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_convert_variables')) {
    function mb_convert_variables($to_encoding, $from_encoding, mixed &$var, mixed &...$vars)
    {
        if (!\is_null($to_encoding)) {
            if (!\is_string($to_encoding)) {
                if (!(\is_string($to_encoding) || \is_object($to_encoding) && \method_exists($to_encoding, '__toString') || (\is_bool($to_encoding) || \is_numeric($to_encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($to_encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($to_encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $to_encoding = (string) $to_encoding;
                }
            }
        }
        if (!\is_null($from_encoding)) {
            if (!\is_string($from_encoding)) {
                if (!(\is_string($from_encoding) || \is_object($from_encoding) && \method_exists($from_encoding, '__toString') || (\is_bool($from_encoding) || \is_numeric($from_encoding)))) {
                    if (!\is_array($from_encoding)) {
                        throw new \TypeError(__METHOD__ . '(): Argument #2 ($from_encoding) must be of type array|string|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($from_encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $from_encoding = (string) $from_encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_convert_variables((string) $to_encoding, isset($from_encoding) ? $from_encoding : '', $var, ...$vars);
        if (!$phabelReturn instanceof false) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_ord')) {
    function mb_ord($string, $encoding = null)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_ord((string) $string, $encoding);
        if (!$phabelReturn instanceof false) {
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_chr')) {
    function mb_chr($codepoint, $encoding = null)
    {
        if (!\is_null($codepoint)) {
            if (!\is_int($codepoint)) {
                if (!(\is_bool($codepoint) || \is_numeric($codepoint))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($codepoint) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($codepoint) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $codepoint = (int) $codepoint;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_chr((int) $codepoint, $encoding);
        if (!$phabelReturn instanceof false) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('mb_scrub')) {
    function mb_scrub($string, $encoding = null)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $encoding = isset($encoding) ? $encoding : \mb_internal_encoding();
        $phabelReturn = \mb_convert_encoding((string) $string, $encoding, $encoding);
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
if (!\function_exists('mb_str_split')) {
    function mb_str_split($string, $length = 1, $encoding = null)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
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
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $encoding = (string) $encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_str_split((string) $string, (int) $length, $encoding);
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
if (\extension_loaded('mbstring')) {
    return;
}
if (!\defined('MB_CASE_UPPER')) {
    \define('MB_CASE_UPPER', 0);
}
if (!\defined('MB_CASE_LOWER')) {
    \define('MB_CASE_LOWER', 1);
}
if (!\defined('MB_CASE_TITLE')) {
    \define('MB_CASE_TITLE', 2);
}
