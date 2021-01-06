<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use Phabel\Target\Php;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;

/**
 * Replace phabel test namespaces with appropriate version.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 */
class PhabelTestGenerator extends Plugin
{
    public function enter(Name $name): ?Name
    {
        if ($name->toString() === \PhabelTest\Target::class) {
            return new Name($name->toString().$this->getConfig('target', ''));
        }
        return null;
    }
    public function enterLiteral(String_ $str): ?String_
    {
        if (str_contains($str->value, "\\Target\\")) {
            return new String_(\str_replace($str->value, "\\Target\\", "\\Target".$this->getConfig('target', '')."\\"));
        }
        return null;
    }

    public static function runAfter(array $config): array
    {
        return [
            Php::class => ['target' => $config['target'] % 1000],
            StringConcatOptimizer::class => [],
        ];
    }
}
