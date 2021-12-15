<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Symfony\Polyfill\Mbstring as p;
if (!function_exists('mb_convert_encoding')) {
    /**
     * @param (array | string | null) $string
     * @param (array | string | null) $from_encoding
     * @return (array | string | false)
     */
    function mb_convert_encoding($string, ?string $to_encoding, $from_encoding = null)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \Phabel\Target\Php72\Polyfill::is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    if (!\is_array($string)) {
                        throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type array|string|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!(\is_null($from_encoding) || \is_null($from_encoding))) {
            if (!\is_string($from_encoding)) {
                if (!(\is_string($from_encoding) || \Phabel\Target\Php72\Polyfill::is_object($from_encoding) && \method_exists($from_encoding, '__toString') || (\is_bool($from_encoding) || \is_numeric($from_encoding)))) {
                    if (!\is_array($from_encoding)) {
                        throw new \TypeError(__METHOD__ . '(): Argument #3 ($from_encoding) must be of type ?array|string|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($from_encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $from_encoding = (string) $from_encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_convert_encoding($string ?? '', (string) $to_encoding, $from_encoding);
        if (!($phabelReturn === false)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
if (!function_exists('mb_decode_mimeheader')) {
    /**
     *
     */
    function mb_decode_mimeheader(?string $string) : string
    {
        return p\Mbstring::mb_decode_mimeheader((string) $string);
    }
}
if (!function_exists('mb_encode_mimeheader')) {
    /**
     *
     */
    function mb_encode_mimeheader(?string $string, ?string $charset = null, ?string $transfer_encoding = null, ?string $newline = "\r\n", ?int $indent = 0) : string
    {
        return p\Mbstring::mb_encode_mimeheader((string) $string, $charset, $transfer_encoding, (string) $newline, (int) $indent);
    }
}
if (!function_exists('mb_decode_numericentity')) {
    /**
     *
     */
    function mb_decode_numericentity(?string $string, array $map, ?string $encoding = null) : string
    {
        return p\Mbstring::mb_decode_numericentity((string) $string, $map, $encoding);
    }
}
if (!function_exists('mb_encode_numericentity')) {
    /**
     *
     */
    function mb_encode_numericentity(?string $string, array $map, ?string $encoding = null, ?bool $hex = false) : string
    {
        return p\Mbstring::mb_encode_numericentity((string) $string, $map, $encoding, (bool) $hex);
    }
}
if (!function_exists('mb_convert_case')) {
    /**
     *
     */
    function mb_convert_case(?string $string, ?int $mode, ?string $encoding = null) : string
    {
        return p\Mbstring::mb_convert_case((string) $string, (int) $mode, $encoding);
    }
}
if (!function_exists('mb_internal_encoding')) {
    /**
     * @return (string | bool)
     */
    function mb_internal_encoding(?string $encoding = null)
    {
        $phabelReturn = p\Mbstring::mb_internal_encoding($encoding);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
if (!function_exists('mb_language')) {
    /**
     * @return (string | bool)
     */
    function mb_language(?string $language = null)
    {
        $phabelReturn = p\Mbstring::mb_language($language);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
if (!function_exists('mb_list_encodings')) {
    /**
     *
     */
    function mb_list_encodings() : array
    {
        return p\Mbstring::mb_list_encodings();
    }
}
if (!function_exists('mb_encoding_aliases')) {
    /**
     *
     */
    function mb_encoding_aliases(?string $encoding) : array
    {
        return p\Mbstring::mb_encoding_aliases((string) $encoding);
    }
}
if (!function_exists('mb_check_encoding')) {
    /**
     * @param (array | string | null) $value
     */
    function mb_check_encoding($value = null, ?string $encoding = null) : bool
    {
        if (!(\is_null($value) || \is_null($value))) {
            if (!\is_string($value)) {
                if (!(\is_string($value) || \Phabel\Target\Php72\Polyfill::is_object($value) && \method_exists($value, '__toString') || (\is_bool($value) || \is_numeric($value)))) {
                    if (!\is_array($value)) {
                        throw new \TypeError(__METHOD__ . '(): Argument #1 ($value) must be of type ?array|string|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($value) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $value = (string) $value;
                }
            }
        }
        return p\Mbstring::mb_check_encoding($value, $encoding);
    }
}
if (!function_exists('mb_detect_encoding')) {
    /**
     * @param (array | string | null) $encodings
     * @return (string | false)
     */
    function mb_detect_encoding(?string $string, $encodings = null, ?bool $strict = false)
    {
        if (!(\is_null($encodings) || \is_null($encodings))) {
            if (!\is_string($encodings)) {
                if (!(\is_string($encodings) || \Phabel\Target\Php72\Polyfill::is_object($encodings) && \method_exists($encodings, '__toString') || (\is_bool($encodings) || \is_numeric($encodings)))) {
                    if (!\is_array($encodings)) {
                        throw new \TypeError(__METHOD__ . '(): Argument #2 ($encodings) must be of type ?array|string|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encodings) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $encodings = (string) $encodings;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_detect_encoding((string) $string, $encodings, (bool) $strict);
        if (!($phabelReturn === false)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!function_exists('mb_detect_order')) {
    /**
     * @param (array | string | null) $encoding
     * @return (array | bool)
     */
    function mb_detect_order($encoding = null)
    {
        if (!(\is_null($encoding) || \is_null($encoding))) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \Phabel\Target\Php72\Polyfill::is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
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
if (!function_exists('mb_parse_str')) {
    /**
     *
     */
    function mb_parse_str(?string $string, &$result = []) : bool
    {
        parse_str((string) $string, $result);
        return (bool) $result;
    }
}
if (!function_exists('mb_strlen')) {
    /**
     *
     */
    function mb_strlen(?string $string, ?string $encoding = null) : int
    {
        return p\Mbstring::mb_strlen((string) $string, $encoding);
    }
}
if (!function_exists('mb_strpos')) {
    /**
     * @return (int | false)
     */
    function mb_strpos(?string $haystack, ?string $needle, ?int $offset = 0, ?string $encoding = null)
    {
        $phabelReturn = p\Mbstring::mb_strpos((string) $haystack, (string) $needle, (int) $offset, $encoding);
        if (!($phabelReturn === false)) {
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
if (!function_exists('mb_strtolower')) {
    /**
     *
     */
    function mb_strtolower(?string $string, ?string $encoding = null) : string
    {
        return p\Mbstring::mb_strtolower((string) $string, $encoding);
    }
}
if (!function_exists('mb_strtoupper')) {
    /**
     *
     */
    function mb_strtoupper(?string $string, ?string $encoding = null) : string
    {
        return p\Mbstring::mb_strtoupper((string) $string, $encoding);
    }
}
if (!function_exists('mb_substitute_character')) {
    /**
     * @param (string | int | null) $substitute_character
     * @return (string | int | bool)
     */
    function mb_substitute_character($substitute_character = null)
    {
        if (!(\is_null($substitute_character) || \is_null($substitute_character))) {
            if (!\is_int($substitute_character)) {
                if (!(\is_bool($substitute_character) || \is_numeric($substitute_character))) {
                    if (!\is_string($substitute_character)) {
                        if (!(\is_string($substitute_character) || \Phabel\Target\Php72\Polyfill::is_object($substitute_character) && \method_exists($substitute_character, '__toString') || (\is_bool($substitute_character) || \is_numeric($substitute_character)))) {
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
                            if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
if (!function_exists('mb_substr')) {
    /**
     *
     */
    function mb_substr(?string $string, ?int $start, ?int $length = null, ?string $encoding = null) : string
    {
        return p\Mbstring::mb_substr((string) $string, (int) $start, $length, $encoding);
    }
}
if (!function_exists('mb_stripos')) {
    /**
     * @return (int | false)
     */
    function mb_stripos(?string $haystack, ?string $needle, ?int $offset = 0, ?string $encoding = null)
    {
        $phabelReturn = p\Mbstring::mb_stripos((string) $haystack, (string) $needle, (int) $offset, $encoding);
        if (!($phabelReturn === false)) {
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
if (!function_exists('mb_stristr')) {
    /**
     * @return (string | false)
     */
    function mb_stristr(?string $haystack, ?string $needle, ?bool $before_needle = false, ?string $encoding = null)
    {
        $phabelReturn = p\Mbstring::mb_stristr((string) $haystack, (string) $needle, (bool) $before_needle, $encoding);
        if (!($phabelReturn === false)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!function_exists('mb_strrchr')) {
    /**
     * @return (string | false)
     */
    function mb_strrchr(?string $haystack, ?string $needle, ?bool $before_needle = false, ?string $encoding = null)
    {
        $phabelReturn = p\Mbstring::mb_strrchr((string) $haystack, (string) $needle, (bool) $before_needle, $encoding);
        if (!($phabelReturn === false)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!function_exists('mb_strrichr')) {
    /**
     * @return (string | false)
     */
    function mb_strrichr(?string $haystack, ?string $needle, ?bool $before_needle = false, ?string $encoding = null)
    {
        $phabelReturn = p\Mbstring::mb_strrichr((string) $haystack, (string) $needle, (bool) $before_needle, $encoding);
        if (!($phabelReturn === false)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!function_exists('mb_strripos')) {
    /**
     * @return (int | false)
     */
    function mb_strripos(?string $haystack, ?string $needle, ?int $offset = 0, ?string $encoding = null)
    {
        $phabelReturn = p\Mbstring::mb_strripos((string) $haystack, (string) $needle, (int) $offset, $encoding);
        if (!($phabelReturn === false)) {
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
if (!function_exists('mb_strrpos')) {
    /**
     * @return (int | false)
     */
    function mb_strrpos(?string $haystack, ?string $needle, ?int $offset = 0, ?string $encoding = null)
    {
        $phabelReturn = p\Mbstring::mb_strrpos((string) $haystack, (string) $needle, (int) $offset, $encoding);
        if (!($phabelReturn === false)) {
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
if (!function_exists('mb_strstr')) {
    /**
     * @return (string | false)
     */
    function mb_strstr(?string $haystack, ?string $needle, ?bool $before_needle = false, ?string $encoding = null)
    {
        $phabelReturn = p\Mbstring::mb_strstr((string) $haystack, (string) $needle, (bool) $before_needle, $encoding);
        if (!($phabelReturn === false)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!function_exists('mb_get_info')) {
    /**
     * @return (array | string | int | false)
     */
    function mb_get_info(?string $type = 'all')
    {
        $phabelReturn = p\Mbstring::mb_get_info((string) $type);
        if (!($phabelReturn === false)) {
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    if (!\is_string($phabelReturn)) {
                        if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
if (!function_exists('mb_http_output')) {
    /**
     * @return (string | bool)
     */
    function mb_http_output(?string $encoding = null)
    {
        $phabelReturn = p\Mbstring::mb_http_output($encoding);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
if (!function_exists('mb_strwidth')) {
    /**
     *
     */
    function mb_strwidth(?string $string, ?string $encoding = null) : int
    {
        return p\Mbstring::mb_strwidth((string) $string, $encoding);
    }
}
if (!function_exists('mb_substr_count')) {
    /**
     *
     */
    function mb_substr_count(?string $haystack, ?string $needle, ?string $encoding = null) : int
    {
        return p\Mbstring::mb_substr_count((string) $haystack, (string) $needle, $encoding);
    }
}
if (!function_exists('mb_output_handler')) {
    /**
     *
     */
    function mb_output_handler(?string $string, ?int $status) : string
    {
        return p\Mbstring::mb_output_handler((string) $string, (int) $status);
    }
}
if (!function_exists('mb_http_input')) {
    /**
     * @return (array | string | false)
     */
    function mb_http_input(?string $type = null)
    {
        $phabelReturn = p\Mbstring::mb_http_input($type);
        if (!($phabelReturn === false)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
if (!function_exists('mb_convert_variables')) {
    /**
     * @param (array | string | null) $from_encoding
     * @return (string | false)
     * @param mixed $var
     * @param mixed ...$vars
     */
    function mb_convert_variables(?string $to_encoding, $from_encoding, &$var, &...$vars)
    {
        if (!true) {
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($var) must be of type mixed, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($var) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        foreach ($vars as $phabelVariadicIndex => $phabelVariadic) {
            if (!true) {
                throw new \TypeError(__METHOD__ . '(): Argument #' . (4 + $phabelVariadicIndex) . ' must be of type mixed, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($vars) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
        }
        if (!\is_null($from_encoding)) {
            if (!\is_string($from_encoding)) {
                if (!(\is_string($from_encoding) || \Phabel\Target\Php72\Polyfill::is_object($from_encoding) && \method_exists($from_encoding, '__toString') || (\is_bool($from_encoding) || \is_numeric($from_encoding)))) {
                    if (!\is_array($from_encoding)) {
                        throw new \TypeError(__METHOD__ . '(): Argument #2 ($from_encoding) must be of type array|string|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($from_encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $from_encoding = (string) $from_encoding;
                }
            }
        }
        $phabelReturn = p\Mbstring::mb_convert_variables((string) $to_encoding, $from_encoding ?? '', $var, ...$vars);
        if (!($phabelReturn === false)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!function_exists('mb_ord')) {
    /**
     * @return (int | false)
     */
    function mb_ord(?string $string, ?string $encoding = null)
    {
        $phabelReturn = p\Mbstring::mb_ord((string) $string, $encoding);
        if (!($phabelReturn === false)) {
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
if (!function_exists('mb_chr')) {
    /**
     * @return (string | false)
     */
    function mb_chr(?int $codepoint, ?string $encoding = null)
    {
        $phabelReturn = p\Mbstring::mb_chr((int) $codepoint, $encoding);
        if (!($phabelReturn === false)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!function_exists('mb_scrub')) {
    /**
     *
     */
    function mb_scrub(?string $string, ?string $encoding = null) : string
    {
        $encoding = $encoding ?? mb_internal_encoding();
        return \Phabel\Target\Php72\Polyfill::mb_convert_encoding((string) $string, $encoding, $encoding);
    }
}
if (!function_exists('mb_str_split')) {
    /**
     *
     */
    function mb_str_split(?string $string, ?int $length = 1, ?string $encoding = null) : array
    {
        return p\Mbstring::mb_str_split((string) $string, (int) $length, $encoding);
    }
}
if (extension_loaded('mbstring')) {
    return;
}
if (!defined('MB_CASE_UPPER')) {
    define('MB_CASE_UPPER', 0);
}
if (!defined('MB_CASE_LOWER')) {
    define('MB_CASE_LOWER', 1);
}
if (!defined('MB_CASE_TITLE')) {
    define('MB_CASE_TITLE', 2);
}