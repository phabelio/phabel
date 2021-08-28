<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console;

class Terminal
{
    private static $width;
    private static $height;
    private static $stty;
    /**
     * Gets the terminal width.
     *
     * @return int
     */
    public function getWidth()
    {
        $width = \getenv('COLUMNS');
        if (\false !== $width) {
            return (int) \trim($width);
        }
        if (null === self::$width) {
            self::initDimensions();
        }
        return self::$width ?: 80;
    }
    /**
     * Gets the terminal height.
     *
     * @return int
     */
    public function getHeight()
    {
        $height = \getenv('LINES');
        if (\false !== $height) {
            return (int) \trim($height);
        }
        if (null === self::$height) {
            self::initDimensions();
        }
        return self::$height ?: 50;
    }
    /**
     * @internal
     *
     * @return bool
     */
    public static function hasSttyAvailable()
    {
        if (null !== self::$stty) {
            return self::$stty;
        }
        // skip check if exec function is disabled
        if (!\function_exists('exec')) {
            return \false;
        }
        \exec('stty 2>&1', $output, $exitcode);
        return self::$stty = 0 === $exitcode;
    }
    private static function initDimensions()
    {
        if ('\\' === \DIRECTORY_SEPARATOR) {
            if (\preg_match('/^(\\d+)x(\\d+)(?: \\((\\d+)x(\\d+)\\))?$/', \trim(\getenv('ANSICON')), $matches)) {
                // extract [w, H] from "wxh (WxH)"
                // or [w, h] from "wxh"
                self::$width = (int) $matches[1];
                self::$height = isset($matches[4]) ? (int) $matches[4] : (int) $matches[2];
            } elseif (!self::hasVt100Support() && self::hasSttyAvailable()) {
                // only use stty on Windows if the terminal does not support vt100 (e.g. Windows 7 + git-bash)
                // testing for stty in a Windows 10 vt100-enabled console will implicitly disable vt100 support on STDOUT
                self::initDimensionsUsingStty();
            } elseif (null !== ($dimensions = self::getConsoleMode())) {
                // extract [w, h] from "wxh"
                self::$width = (int) $dimensions[0];
                self::$height = (int) $dimensions[1];
            }
        } else {
            self::initDimensionsUsingStty();
        }
    }
    /**
     * Returns whether STDOUT has vt100 support (some Windows 10+ configurations).
     */
    private static function hasVt100Support()
    {
        $phabelReturn = \function_exists('sapi_windows_vt100_support') && \sapi_windows_vt100_support(\fopen('php://stdout', 'w'));
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Initializes dimensions using the output of an stty columns line.
     */
    private static function initDimensionsUsingStty()
    {
        if ($sttyString = self::getSttyColumns()) {
            if (\preg_match('/rows.(\\d+);.columns.(\\d+);/i', $sttyString, $matches)) {
                // extract [w, h] from "rows h; columns w;"
                self::$width = (int) $matches[2];
                self::$height = (int) $matches[1];
            } elseif (\preg_match('/;.(\\d+).rows;.(\\d+).columns/i', $sttyString, $matches)) {
                // extract [w, h] from "; h rows; w columns"
                self::$width = (int) $matches[2];
                self::$height = (int) $matches[1];
            }
        }
    }
    /**
     * Runs and parses mode CON if it's available, suppressing any error output.
     *
     * @return int[]|null An array composed of the width and the height or null if it could not be parsed
     */
    private static function getConsoleMode()
    {
        $info = self::readFromProcess('mode CON');
        if (null === $info || !\preg_match('/--------+\\r?\\n.+?(\\d+)\\r?\\n.+?(\\d+)\\r?\\n/', $info, $matches)) {
            $phabelReturn = null;
            if (!(\is_array($phabelReturn) || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = [(int) $matches[2], (int) $matches[1]];
        if (!(\is_array($phabelReturn) || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Runs and parses stty -a if it's available, suppressing any error output.
     */
    private static function getSttyColumns()
    {
        $phabelReturn = self::readFromProcess('stty -a | grep columns');
        if (!\is_null($phabelReturn)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
    private static function readFromProcess($command)
    {
        if (!\is_string($command)) {
            if (!(\is_string($command) || \is_object($command) && \method_exists($command, '__toString') || (\is_bool($command) || \is_numeric($command)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($command) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($command) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $command = (string) $command;
            }
        }
        if (!\function_exists('proc_open')) {
            $phabelReturn = null;
            if (!\is_null($phabelReturn)) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
            }
            return $phabelReturn;
        }
        $descriptorspec = [1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $process = \proc_open($command, $descriptorspec, $pipes, null, null, ['suppress_errors' => \true]);
        if (!\is_resource($process)) {
            $phabelReturn = null;
            if (!\is_null($phabelReturn)) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
            }
            return $phabelReturn;
        }
        $info = \stream_get_contents($pipes[1]);
        \fclose($pipes[1]);
        \fclose($pipes[2]);
        \proc_close($process);
        $phabelReturn = $info;
        if (!\is_null($phabelReturn)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
