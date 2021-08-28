<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use Phabel\Target\Php;
use Phabel\PhpParser\Node\Name;
use Phabel\PhpParser\Node\Scalar\String_;
/**
 * Replace phabel test namespaces with appropriate version.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 */
class PhabelTestGenerator extends Plugin
{
    private function tryReplace($in)
    {
        if (!\is_string($in)) {
            if (!(\is_string($in) || \is_object($in) && \method_exists($in, '__toString') || (\is_bool($in) || \is_numeric($in)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($in) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($in) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $in = (string) $in;
        }
        $phabelReturn = \preg_replace("~PhabelTest(\\\\+)Target\\d*~", 'PhabelTest$1Target' . $this->getConfig('target', ''), $in);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
        }
        return $phabelReturn;
    }
    public function enter(Name $name)
    {
        if (\preg_match("~PhabelTest\\\\+Target\\d*~", $name->toString())) {
            $class = \get_class($name);
            $phabelReturn = new $class($this->tryReplace($name->toString()));
            if (!($phabelReturn instanceof Name || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Name, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = null;
        if (!($phabelReturn instanceof Name || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Name, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function enterLiteral(String_ $str)
    {
        if (\preg_match("~PhabelTest\\\\+Target\\d*~", $str->value)) {
            $phabelReturn = new String_($this->tryReplace($str->value));
            if (!($phabelReturn instanceof String_ || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?String_, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = null;
        if (!($phabelReturn instanceof String_ || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?String_, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public static function previous(array $config)
    {
        $phabelReturn = [Php::class => ['target' => $config['target'] % 1000], \Phabel\Plugin\StringConcatOptimizer::class => []];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
