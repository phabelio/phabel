<?php

namespace Phabel\Target;

use Phabel\Plugin;
use Phabel\Tools;
use Phabel\PhpParser\Node;
use Phabel\PhpParser\Node\Expr\ClassConstFetch;
use Phabel\PhpParser\Node\Expr\Error;
use Phabel\PhpParser\Node\Expr\FuncCall;
use Phabel\PhpParser\Node\Expr\StaticCall;
use Phabel\PhpParser\Node\Name;
use ReflectionClass;
use ReflectionMethod;
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Polyfill extends Plugin
{
    private $functions = [];
    public static function mergeConfigs(array ...$configs) : array
    {
        $configs = \array_merge(...$configs);
        \krsort($configs, \SORT_STRING);
        $constants = [];
        $functions = [];
        foreach ($configs as $polyfill => $_) {
            $class = new ReflectionClass($polyfill);
            if ($class->hasConstant('CONSTANTS')) {
                $constants = \array_merge_recursive($constants, $class->getConstant('CONSTANTS'));
            }
            foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_STATIC) as $method) {
                if ($method->getDeclaringClass()->getName() === $polyfill) {
                    $functions[$method->getName()] = [$polyfill, $method->getName()];
                }
            }
        }
        return [['constants' => $constants, 'functions' => $functions]];
    }
    public function shouldRunFile(string $file) : bool
    {
        if (\preg_match(':Target/Php(\\d\\d)/Polyfill.php:', $file, $matches)) {
            $version = \Phabel\Target\Php::normalizeVersion($matches[1]);
            $version = \Phabel\Target\Php::class . $version . '\\Polyfill';
            $this->functions = \Phabel\Target\Php74\Polyfill::array_filter($this->getConfig('functions', []), function ($s) use($version) {
                return $s[0] !== $version;
            });
        } else {
            $this->functions = $this->getConfig('functions', []);
        }
        return \true;
    }
    public function enterFunc(FuncCall $call)
    {
        if (!$call->name instanceof Name) {
            $phabelReturn = null;
            if (!($phabelReturn instanceof StaticCall || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?StaticCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $replacement = $this->functions[\strtolower(Tools::getFqdn($call->name, $call->name->toLowerString()))] ?? null;
        if ($replacement) {
            $phabelReturn = Tools::call($replacement, ...$call->args);
            if (!($phabelReturn instanceof StaticCall || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?StaticCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = null;
        if (!($phabelReturn instanceof StaticCall || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?StaticCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function enterClassConstant(ClassConstFetch $fetch)
    {
        if ($fetch->name instanceof Error || !$fetch->class instanceof Name) {
            $phabelReturn = null;
            if (!($phabelReturn instanceof Node || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $constants = $this->getConfig('constants', []);
        if (isset($constants[Tools::getFqdn($fetch->class)][$fetch->name->name])) {
            $phabelReturn = Tools::fromLiteral($constants[self::getFqdn($fetch->class)][$fetch->name->name]);
            if (!($phabelReturn instanceof Node || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = null;
        if (!($phabelReturn instanceof Node || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
