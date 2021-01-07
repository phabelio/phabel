<?php

namespace Phabel\Target;

use Phabel\Plugin;
use Phabel\Plugin\NestedExpressionFixer;
use Phabel\Plugin\StmtExprWrapper;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;

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
            return PHP_MAJOR_VERSION.PHP_MINOR_VERSION;
        }
        if (\preg_match(":^\D*(\d+\.\d+)\..*:", $target, $matches)) {
            $target = $matches[1];
        }
        $target = \str_replace('.', '', $target);
        return \in_array($target, self::VERSIONS) ? $target : self::DEFAULT_TARGET;
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
     * @return array
     */
    private static function getRange(int $target): array
    {
        $key = \array_search($target, self::VERSIONS);
        return \array_slice(
            self::VERSIONS,
            1 + ($key === false ? self::DEFAULT_TARGET : $key)
        );
    }
    public function getComposerRequires(): array
    {
        return \array_fill_keys(
            \array_map(fn (string $version): string => "symfony/polyfill-php$version", self::getRange($this->config['target'] ?? self::DEFAULT_TARGET)),
            '*'
        );
    }
    public static function runWithAfter(array $config): array
    {
        $classes = [];
        foreach (self::getRange($config['target'] ?? self::DEFAULT_TARGET) as $version) {
            if (!\file_exists($dir = __DIR__."/Php$version")) {
                continue;
            }
            foreach (\scandir($dir) as $file) {
                if (\substr($file, -4) !== '.php' || !(str_ends_with($file, "NestedExpressionFixer.php") || str_ends_with($file, "IssetExpressionFixer.php"))) {
                    continue;
                }
                $class = self::class.$version.'\\'.\basename($file, '.php');
                $classes[$class] = $config[$class] ?? [];
            }
        }
        return $classes;
    }
    public static function runBefore(array $config): array
    {
        $classes = [StmtExprWrapper::class => $config[StmtExprWrapper::class] ?? []];
        foreach (self::getRange($config['target'] ?? self::DEFAULT_TARGET) as $version) {
            if (!\file_exists($dir = __DIR__."/Php$version")) {
                continue;
            }
            foreach (\scandir($dir) as $file) {
                if (\substr($file, -4) !== '.php' || str_ends_with($file, "NestedExpressionFixer.php") || str_ends_with($file, "IssetExpressionFixer.php")) {
                    continue;
                }
                $class = self::class.$version.'\\'.\basename($file, '.php');
                $classes[$class] = $config[$class] ?? [];
            }
        }
        return $classes;
    }
}
