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
    private function tryReplace(string $in): string
    {
        return \preg_replace("~PhabelTest\\Target\d*~", "PhabelTest\\Target".$this->getConfig('target', ''), $in);
    }
    public function enter(Name $name): ?Name
    {
        if (str_contains($name->toString(), "PhabelTest\\Target")) {
            return new Name($this->tryReplace($name->toString()));
        }
        return null;
    }
    public function enterLiteral(String_ $str): ?String_
    {
        if (str_contains($str->value, "PhabelTest\\Target")) {
            return new String_($this->tryReplace($str->value));
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
