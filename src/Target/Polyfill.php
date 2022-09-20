<?php

namespace Phabel\Target;

use Phabel\Plugin;
use Phabel\Tools;
use PhabelVendor\PhpParser\Node;
use PhabelVendor\PhpParser\Node\Expr\ClassConstFetch;
use PhabelVendor\PhpParser\Node\Expr\Error;
use PhabelVendor\PhpParser\Node\Expr\FuncCall;
use PhabelVendor\PhpParser\Node\Expr\StaticCall;
use PhabelVendor\PhpParser\Node\Name;
use ReflectionClass;
use ReflectionMethod;
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Polyfill extends Plugin
{
    /**
     * @var array $functions
     */
    private $functions = [];
    /**
     *
     */
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
    /**
     *
     */
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
        return !\str_contains($file, 'vendor/composer/');
    }
    /**
     *
     */
    public function enterFunc(FuncCall $call) : ?StaticCall
    {
        if (!$call->name instanceof Name) {
            return null;
        }
        $replacement = $this->functions[\strtolower(Tools::getFqdn($call->name, $call->name->toLowerString()))] ?? null;
        if ($replacement) {
            return Tools::call($replacement, ...$call->args);
        }
        return null;
    }
    /**
     *
     */
    public function enterClassConstant(ClassConstFetch $fetch) : ?Node
    {
        if ($fetch->name instanceof Error || !$fetch->class instanceof Name) {
            return null;
        }
        $constants = $this->getConfig('constants', []);
        if (isset($constants[Tools::getFqdn($fetch->class)][$fetch->name->name])) {
            return Tools::fromLiteral($constants[self::getFqdn($fetch->class)][$fetch->name->name]);
        }
        return null;
    }
}
