<?php

namespace Phabel\Target;

use Phabel\Plugin;
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
    const VERSIONS = [
        56,
        70,
        71,
        72,
        73,
        74,
        80,
    ];
    /**
     * Default target.
     */
    const DEFAULT_TARGET = PHP_MAJOR_VERSION.PHP_MINOR_VERSION;
    /**
     * Ignore target.
     */
    const TARGET_IGNORE = 1000;
    /**
     * Normalize target version string.
     *
     * @param string $target
     * @return integer
     */
    public static function normalizeVersion(string $target): int
    {
        if ($target === 'auto') {
            return (int) self::DEFAULT_TARGET;
        }
        if (\preg_match(":^\D*(\d+\.\d+)\..*:", $target, $matches)) {
            $target = $matches[1];
        }
        $target = \str_replace('.', '', $target);
        return (int) (\in_array($target, self::VERSIONS) ? $target : self::DEFAULT_TARGET);
    }
    /**
     * Unnormalize version string.
     *
     * @param int $target
     * @return string
     */
    public static function unnormalizeVersion(int $target): string
    {
        $target = (string) $target;
        return $target[0].'.'.$target[1];
    }
    /**
     * Get PHP version range to target.
     *
     * @param int $target
     * @return int[]
     */
    private static function getRange(int $target): array
    {
        $key = \array_search($target, self::VERSIONS);
        return $key === false
            ? self::getRange((int) self::DEFAULT_TARGET)
            : \array_slice(
                self::VERSIONS,
                1 + $key
            );
    }
    public function getComposerRequires(): array
    {
        return \array_fill_keys(
            \array_map(fn (int $version): string => "symfony/polyfill-php$version", self::getRange((int) $this->getConfig('target', self::DEFAULT_TARGET))),
            '*'
        );
    }
    public static function previous(array $config): array
    {
        $classes = [];
        foreach (self::getRange((int) ($config['target'] ?? self::DEFAULT_TARGET)) as $version) {
            if (!\file_exists($dir = __DIR__."/Php$version")) {
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
                $class = self::class.$version.'\\'.\basename($file, '.php');
                /** @var array */
                $classes[$class] = $config[$class] ?? [];
            }
        }
        return $classes;
    }
    public static function next(array $config): array
    {
        $classes = [
            StmtExprWrapper::class => $config[StmtExprWrapper::class] ?? [],
            NewFixer::class => []
        ];
        foreach (self::getRange((int) ($config['target'] ?? self::DEFAULT_TARGET)) as $version) {
            if (!\file_exists($dir = __DIR__."/Php$version")) {
                continue;
            }
            foreach (['Nested', 'Isset'] as $t) {
                /** @var class-string<PluginInterface> */
                $class = self::class.$version."\\$t"."ExpressionFixer";
                /** @var array */
                $classes[$class] = $config[$class] ?? [];
            }
        }
        return $classes;
    }
}
