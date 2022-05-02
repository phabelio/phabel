<?php

namespace Phabel\Cli;

use PhabelVendor\Symfony\Component\Console\Formatter\OutputFormatter;
use PhabelVendor\Symfony\Component\Console\Formatter\OutputFormatterStyle;
class Formatter
{
    public const BANNER = "<bold>＊＊＊＊＊＊＊＊＊</>\n<bold>＊</bold><phabel> Ｐｈａｂｅｌ </><bold>＊</bold>\n<bold>＊＊＊＊＊＊＊＊＊</>\n\n<phabel>PHP transpiler - Write and deploy modern PHP 8 code, today: https://phabel.io</phabel>";
    private static ?OutputFormatter $instance = null;
    public static function getFormatter() : OutputFormatter
    {
        if (!self::$instance) {
            self::$instance = new OutputFormatter(\true, ['bold' => new OutputFormatterStyle('white', 'default', ['bold']), 'phabel' => new OutputFormatterStyle('blue', 'default', ['bold']), 'error' => new OutputFormatterStyle('red', 'default', ['bold'])]);
        }
        return self::$instance;
    }
    public static function banner() : string
    {
        return self::getFormatter()->format(self::BANNER);
    }
}
