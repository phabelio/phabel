<?php

namespace Phabel\Target\Php71;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintReplacer;
/**
 * Remove void return typehint.
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class VoidReturnType extends Plugin
{
    /**
     *
     */
    public static function previous(array $config) : array
    {
        return [TypeHintReplacer::class => ['void' => \true]];
    }
}
