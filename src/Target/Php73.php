<?php

namespace Phabel\Target;

use Phabel\Plugin;

/**
 * Makes changes necessary to polyfill PHP 7.3 and run on PHP 7.2 and below.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Php73 extends Plugin
{
    public static function composerRequires(): array
    {
        return ['symfony/polyfill-php72' => '*'];
    }
}
