<?php

namespace Phabel\Cli;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Formatter
{
    const BANNER = "<bold>＊＊＊＊＊＊＊＊＊</>\n<bold>＊</bold><phabel> Ｐｈａｂｅｌ </><bold>＊</bold>\n<bold>＊＊＊＊＊＊＊＊＊</>\n\n<phabel>PHP transpiler - Write and deploy modern PHP 8 code, today: https://phabel.io</phabel>";
    private static $instance = null;
    public static function getFormatter()
    {
        if (!self::$instance) {
            self::$instance = new OutputFormatter(true, ['bold' => new OutputFormatterStyle('white', 'default', ['bold']), 'phabel' => new OutputFormatterStyle('blue', 'default', ['bold']), 'error' => new OutputFormatterStyle('red', 'default', ['bold'])]);
        }
        $phabelReturn = self::$instance;
        if (!$phabelReturn instanceof OutputFormatter) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type OutputFormatter, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public static function banner()
    {
        $phabelReturn = self::getFormatter()->format(self::BANNER);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
        }
        return $phabelReturn;
    }
}
