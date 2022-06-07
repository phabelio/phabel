<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use Phabel\Target\Php;
use Phabel\Target\Php80\ConstantReplacer;
use PhabelVendor\PhpParser\Node\Name;
use PhabelVendor\PhpParser\Node\Scalar\String_;
/**
 * Replace phabel test namespaces with appropriate version.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 */
class PhabelTestGenerator extends Plugin
{
    private function tryReplace(string $in) : string
    {
        return \preg_replace("~PhabelTest(\\\\+)Target(?:Future)?\\d*~", 'PhabelTest$1Target' . $this->getConfig('target', ''), $in);
    }
    public function enter(Name $name) : ?Name
    {
        if (\preg_match("~PhabelTest\\\\+Target(?:Future)?\\d*~", $name->toString())) {
            $class = \get_class($name);
            return new $class($this->tryReplace($name->toString()));
        }
        return null;
    }
    public function enterLiteral(String_ $str) : ?String_
    {
        if (\preg_match("~PhabelTest\\\\+Target(?:Future)?\\d*~", $str->value)) {
            return new String_($this->tryReplace($str->value));
        }
        return null;
    }
    public static function previous(array $config) : array
    {
        return [Php::class => ['target' => $config['target'] % 1000], \Phabel\Plugin\StringConcatOptimizer::class => [], ConstantReplacer::class => []];
    }
}
