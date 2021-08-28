<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\SignalRegistry;

final class SignalRegistry
{
    private $signalHandlers = [];
    public function __construct()
    {
        if (\function_exists('pcntl_async_signals')) {
            \pcntl_async_signals(\true);
        }
    }
    public function register($signal, callable $signalHandler)
    {
        if (!\is_int($signal)) {
            if (!(\is_bool($signal) || \is_numeric($signal))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($signal) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($signal) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $signal = (int) $signal;
            }
        }
        if (!isset($this->signalHandlers[$signal])) {
            $previousCallback = \pcntl_signal_get_handler($signal);
            if (\is_callable($previousCallback)) {
                $this->signalHandlers[$signal][] = $previousCallback;
            }
        }
        $this->signalHandlers[$signal][] = $signalHandler;
        \pcntl_signal($signal, [$this, 'handle']);
    }
    public static function isSupported()
    {
        if (!\function_exists('pcntl_signal')) {
            $phabelReturn = \false;
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (bool) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        if (\in_array('pcntl_signal', \explode(',', \ini_get('disable_functions')))) {
            $phabelReturn = \false;
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (bool) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = \true;
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
     * @internal
     */
    public function handle($signal)
    {
        if (!\is_int($signal)) {
            if (!(\is_bool($signal) || \is_numeric($signal))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($signal) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($signal) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $signal = (int) $signal;
            }
        }
        $count = \count($this->signalHandlers[$signal]);
        foreach ($this->signalHandlers[$signal] as $i => $signalHandler) {
            $hasNext = $i !== $count - 1;
            $signalHandler($signal, $hasNext);
        }
    }
}
