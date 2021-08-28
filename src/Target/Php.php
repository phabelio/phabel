<?php

namespace Phabel\Target;

use Phabel\Plugin;
use Phabel\Plugin\ComposerSanitizer;
use Phabel\Plugin\NewFixer;
use Phabel\Plugin\StmtExprWrapper;
use Phabel\PluginInterface;
/**
 * Makes changes necessary to polyfill syntaxes of various PHP versions.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Php extends Plugin
{
    /**
     * PHP versions.
     *
     * @var int[]
     */
    const VERSIONS = [56, 70, 71, 72, 73, 74, 80];
    /**
     * Default target.
     */
    const DEFAULT_TARGET = \PHP_MAJOR_VERSION . \PHP_MINOR_VERSION;
    /**
     * Ignore target.
     */
    const TARGET_IGNORE = 1000;
    /**
     * Polyfill versions.
     */
    const POLYFILL_VERSIONS = ['symfony/polyfill-php56' => '^1.19', 'symfony/polyfill-php70' => '^1.19', 'symfony/polyfill-php71' => '^1.19', 'symfony/polyfill-php72' => '^1.23', 'symfony/polyfill-php73' => '^1.23', 'symfony/polyfill-php74' => '^1.23', 'symfony/polyfill-php80' => '^1.23', 'symfony/polyfill-php81' => '^1.23'];
    /**
     * Normalize target version string.
     *
     * @param string $target
     * @return integer
     */
    public static function normalizeVersion($target)
    {
        if (!\is_string($target)) {
            if (!(\is_string($target) || \is_object($target) && \method_exists($target, '__toString') || (\is_bool($target) || \is_numeric($target)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($target) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($target) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $target = (string) $target;
        }
        if ($target === 'auto') {
            $phabelReturn = (int) self::DEFAULT_TARGET;
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (int) $phabelReturn;
            }
            return $phabelReturn;
        }
        if (\preg_match(":^\\D*(\\d+\\.\\d+)\\..*:", $target, $matches)) {
            $target = $matches[1];
        }
        $target = \str_replace('.', '', $target);
        $phabelReturn = (int) (\in_array($target, self::VERSIONS) ? $target : self::DEFAULT_TARGET);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (int) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Unnormalize version string.
     *
     * @param int $target
     * @return string
     */
    public static function unnormalizeVersion($target)
    {
        if (!\is_int($target)) {
            if (!(\is_bool($target) || \is_numeric($target))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($target) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($target) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $target = (int) $target;
        }
        $target = (string) $target;
        $phabelReturn = $target[0] . '.' . $target[1];
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Get PHP version range to target.
     *
     * @param int $target
     * @return int[]
     */
    private static function getRange($target)
    {
        if (!\is_int($target)) {
            if (!(\is_bool($target) || \is_numeric($target))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($target) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($target) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $target = (int) $target;
        }
        $key = \array_search($target, self::VERSIONS);
        $phabelReturn = $key === \false ? self::getRange((int) self::DEFAULT_TARGET) : \array_slice(self::VERSIONS, 1 + $key);
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public static function getComposerRequires(array $config)
    {
        $target = \Phabel\Target\Php::normalizeVersion(isset($config['target']) ? $config['target'] : self::DEFAULT_TARGET);
        $res = ['php' => '>=' . \Phabel\Target\Php::unnormalizeVersion($target) . ' <' . \Phabel\Target\Php::unnormalizeVersion($target + 1)];
        foreach (self::getRange($target) as $version) {
            $version = "symfony/polyfill-php{$version}";
            $res[$version] = self::POLYFILL_VERSIONS[$version];
        }
        $phabelReturn = $res;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public static function previous(array $config)
    {
        $classes = [ComposerSanitizer::class => []];
        foreach (self::getRange((int) (isset($config['target']) ? $config['target'] : self::DEFAULT_TARGET)) as $version) {
            if (!\file_exists($dir = __DIR__ . "/Php{$version}")) {
                continue;
            }
            foreach (\scandir($dir) as $file) {
                if (\substr($file, -4) !== '.php') {
                    continue;
                }
                if (\str_ends_with($file, 'ExpressionFixer.php')) {
                    continue;
                }
                /** @var class-string<PluginInterface> */
                $class = self::class . $version . '\\' . \basename($file, '.php');
                /** @var array */
                $classes[$class] = isset($config[$class]) ? $config[$class] : [];
            }
        }
        $phabelReturn = $classes;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public static function next(array $config)
    {
        $classes = [StmtExprWrapper::class => isset($config[StmtExprWrapper::class]) ? $config[StmtExprWrapper::class] : [], NewFixer::class => []];
        foreach (self::getRange((int) (isset($config['target']) ? $config['target'] : self::DEFAULT_TARGET)) as $version) {
            if (!\file_exists(__DIR__ . "/Php{$version}")) {
                continue;
            }
            foreach (['Nested', 'Isset'] as $t) {
                /** @var class-string<PluginInterface> */
                $class = self::class . $version . "\\{$t}" . "ExpressionFixer";
                /** @var array */
                $classes[$class] = isset($config[$class]) ? $config[$class] : [];
            }
        }
        $phabelReturn = $classes;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
