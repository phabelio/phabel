<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Process;

/**
 * Generic executable finder.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ExecutableFinder
{
    private $suffixes = ['.exe', '.bat', '.cmd', '.com'];
    /**
     * Replaces default suffixes of executable.
     */
    public function setSuffixes(array $suffixes)
    {
        $this->suffixes = $suffixes;
    }
    /**
     * Adds new possible suffix to check for executable.
     */
    public function addSuffix($suffix)
    {
        if (!\is_string($suffix)) {
            if (!(\is_string($suffix) || \is_object($suffix) && \method_exists($suffix, '__toString') || (\is_bool($suffix) || \is_numeric($suffix)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($suffix) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($suffix) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $suffix = (string) $suffix;
            }
        }
        $this->suffixes[] = $suffix;
    }
    /**
     * Finds an executable by name.
     *
     * @param string      $name      The executable name (without the extension)
     * @param string|null $default   The default to return if no executable is found
     * @param array       $extraDirs Additional dirs to check into
     *
     * @return string|null The executable path or default value
     */
    public function find($name, $default = null, array $extraDirs = [])
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!\is_null($default)) {
            if (!\is_string($default)) {
                if (!(\is_string($default) || \is_object($default) && \method_exists($default, '__toString') || (\is_bool($default) || \is_numeric($default)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($default) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($default) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $default = (string) $default;
                }
            }
        }
        if (\ini_get('open_basedir')) {
            $searchPath = \array_merge(\explode(\PATH_SEPARATOR, \ini_get('open_basedir')), $extraDirs);
            $dirs = [];
            foreach ($searchPath as $path) {
                // Silencing against https://bugs.php.net/69240
                if (@\is_dir($path)) {
                    $dirs[] = $path;
                } else {
                    if (\basename($path) == $name && @\is_executable($path)) {
                        return $path;
                    }
                }
            }
        } else {
            $dirs = \array_merge(\explode(\PATH_SEPARATOR, \getenv('PATH') ?: \getenv('Path')), $extraDirs);
        }
        $suffixes = [''];
        if ('\\' === \DIRECTORY_SEPARATOR) {
            $pathExt = \getenv('PATHEXT');
            $suffixes = \array_merge($pathExt ? \explode(\PATH_SEPARATOR, $pathExt) : $this->suffixes, $suffixes);
        }
        foreach ($suffixes as $suffix) {
            foreach ($dirs as $dir) {
                if (@\is_file($file = $dir . \DIRECTORY_SEPARATOR . $name . $suffix) && ('\\' === \DIRECTORY_SEPARATOR || @\is_executable($file))) {
                    return $file;
                }
            }
        }
        return $default;
    }
}
