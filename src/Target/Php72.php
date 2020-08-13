<?php

namespace Phabel\Target;

use Phabel\Plugin;

/**
 * Makes changes necessary to polyfill PHP 7.2 and run on PHP 7.1 and below.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Php72 extends Plugin
{
    public static function composerRequires(): array
    {
        return ['symfony/polyfill-php72' => '*'];
    }
}
