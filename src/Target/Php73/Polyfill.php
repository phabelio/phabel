<?php

namespace Phabel\Target\Php73;

use Phabel\Plugin;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Polyfill extends Plugin
{
    // Skip apache_request_headers
    // Todo: setrawcookie
    // Todo: password_hash

    public static function getComposerRequires(array $config): array
    {
        return ['ralouphie/getallheaders' => '^3|^2'];
    }

    public static function array_push(array &$array, ...$values): int
    {
        if (\count($values) === 0) {
            return \count($array);
        }
        return \array_push($array, ...$values);
    }
    public static function array_unshift(array &$array, ...$values): int
    {
        if (\count($values) === 0) {
            return \count($array);
        }
        return \array_unshift($array, ...$values);
    }
}
