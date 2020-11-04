<?php

namespace Phabel\Target;

use Phabel\Plugin;

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
    private const VERSIONS = [
        '55',
        '56',
        '70',
        '71',
        '72',
        '73',
        '74',
        '80',
    ];
    /**
     * Default target.
     */
    private const DEFAULT_TARGET = '70';
    /**
     * Get PHP version range to target.
     *
     * @param array $config
     * @return array
     */
    private static function getRange(array $config): array
    {
        $target = $config['target'] ?? PHP_MAJOR_VERSION.PHP_MINOR_VERSION;
        if (\preg_match(":^\D*(\d+\.\d+)\..*:", $config['target'], $matches)) {
            $target = $matches[1];
        }
        $key = \array_search(\str_replace('.', '', $target), self::VERSIONS);
        return \array_slice(
            self::VERSIONS,
            $key === false ? self::DEFAULT_TARGET : $key
        );
    }
    public static function composerRequires(array $config): array
    {
        return \array_fill_keys(
            \array_map(fn (string $version): string => "symfony/polyfill-$version", self::getRange($config)),
            '*'
        );
    }
    public static function runWithAfter(array $config): array
    {
        $classes = [];
        foreach (self::getRange($config) as $version) {
            foreach (\scandir(__DIR__."/Php$version") as $file) {
                if (\substr($file, -4) !== '.php') {
                    continue;
                }
                $class = \basename($version, '.php');
                $classes[$class] = $config[$class] ?? [];
            }
        }
        return $classes;
    }

    /**
     * {@inheritDoc}
     *
    public static function mergeConfigs(array ...$configs): array
    {
        $configsByTarget = [];
        foreach ($configs as $config) {
            $configsByTarget[$config['target'] ?? PHP_MAJOR_VERSION.PHP_MINOR_VERSION] = [
                $config
            ];
        }

        return $configs;
    }
    /**
     * {@inheritDoc}
     *
    public static function splitConfig(array $config): array
    {
        $target = $config['target'] = $config['target'] ?? PHP_MAJOR_VERSION.PHP_MINOR_VERSION;
        unset($config['target']);
        $chunks = array_chunk($target, 1, true);
        foreach ($chunks as $k => $chunk) {
            $chunks[$k]['target'] = $target;
        }
        return $chunks;
    }*/
}
