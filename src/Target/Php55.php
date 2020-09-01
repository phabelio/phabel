<?php

namespace Phabel\Target;

use Phabel\Plugin;
use Phabel\Target\Php55\YieldDetector;

/**
 * Makes changes necessary to polyfill PHP 5.5 and run on PHP 5.4 and below.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Php55 extends Plugin
{
    public static function composerRequires(): array
    {
        return ['symfony/polyfill-php55' => '*'];
    }
    public static function runWithAfter(): array
    {
        return [
            YieldDetector::class
        ];
    }
}
