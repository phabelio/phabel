<?php

/**
 * This file generates a set of functions, closures and arrow functions with specific type hints, to test the typehint replacer.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
if (PHP_MAJOR_VERSION < 8) {
    echo "This generator can only run on PHP 8.0+" . PHP_EOL;
    die(1);
}
const CLAZZ = "PhabelTest\\Target\\TypeHintReplacerTest";
function escapeRegex(string $in): string
{
    return \var_export('~' . \str_replace(['\\', '(', ')', '$', '?', '|'], ['\\\\', '\\(', '\\)', '\\$', '\\?', '\\|'], $in) . '~', true);
}
function getErrorMessage(string $scalarParam, string $scalar, string $scalarSane, $wrongVal, string $to): array
{
    try {
        $f = eval("return new class { public function r() { return fn ({$scalarParam} \$data): {$scalar} => \$data; }};");
        $f->r()(eval("return {$wrongVal};"));
    } catch (\Throwable $e) {
        $message = $e->getMessage();
        $message = \str_replace("self", CLAZZ, $message);
        $message = \preg_replace(["/called in .*/", "/.*: /"], ".*", $message);
        if (\preg_match_all("/must be of type (\\S+)/", $message, $matches)) {
            foreach ($matches[1] as $match) {
                $message = \str_replace($match, \str_replace("class@anonymous", CLAZZ, $match), $message);
            }
        }
        $closureMessage = \str_replace("{$to} class@anonymous::{closure}", "{$to} " . CLAZZ . "::PhabelTest\\Target\\{closure}", $message);
        $methodMessage = \str_replace("{$to} class@anonymous::{closure}", "{$to} " . CLAZZ . "::test{$scalarSane}", $message);
        $funcMessage = \str_replace("{$to} class@anonymous::{closure}", "{$to} PhabelTest\\Target\\test{$scalarSane}", $message);
    }
    return [escapeRegex($closureMessage), escapeRegex($methodMessage), escapeRegex($funcMessage)];
}
$SCALARS = ['callable' => ['"is_null"', 'fn (): int => 0', '[$this, "noop"]', '[self::class, "noop"]'], 'array' => ["['lmao']", 'array()'], 'bool' => ["true", "false", '0', '1', '"0"', '"1"', '""', '"aaaa"'], 'iterable' => ["['lmao']", 'array()', '(fn (): \\Generator => yield)()'], 'float' => ["123", "-1", "123.123", "1e3", "true", "false", "'123'", '"123.123"'], 'object' => ["new class{}", '$this'], 'string' => ["'lmao'", 'new class{public function __toString() { return "lmao"; }}', "123", "-1", "123.123", "1e3", "true", "false"], 'self' => ['$this'], 'int' => ["123", "-1", "123.0", "1e3", "true", "false", "'123'", "'123.0'"], '\\' . CLAZZ => ['$this'], '\\Generator' => ['(fn (): \\Generator => yield)()']];
$count = \count($SCALARS);
$k = 0;
foreach ($SCALARS as $scalar => $val) {
    $SCALARS["?{$scalar}"] = \array_merge($val, ['null']);
    $k = ($k + 1) % $count;
    $nextScalar = \array_keys($SCALARS)[$k];
    $nextVal = $SCALARS[$nextScalar];
    $SCALARS["{$scalar}|{$nextScalar}"] = \array_merge($val, $nextVal);
}
foreach (\glob("testsGenerated/Target/TypeHintReplacer*") as $file) {
    \unlink($file);
}
$closures = [];
$closuresRet = [];
$classFuncs = '';
$funcs = '';
$k = 0;
$count = 0;
foreach ($SCALARS as $scalar => $vals) {
    foreach ($vals as $val) {
        $self = \strpos($scalar, 'self') !== false;
        $scalarSane = $k++ . \preg_replace("~[^A-Za-z]*~", "", $scalar);
        $wrongVal = \strpos($scalar, 'object') !== false ? 0 : 'new class{}';
        if ($scalar === 'float|object' || $scalar === 'object|string') {
            $wrongVal = 'null';
        }
        [$closureMessage, $methodMessage, $funcMessage] = getErrorMessage($scalar, $scalar, $scalarSane, $wrongVal, "to");
        $closures[] = "[fn ({$scalar} \$data): {$scalar} => \$data, {$val}, {$wrongVal}, {$closureMessage}]";
        $closures[] = "[function ({$scalar} \$data): {$scalar} { return \$data; }, {$val}, {$wrongVal}, {$closureMessage}]";
        $closures[] = "[[\$this, 'test{$scalarSane}'], {$val}, {$wrongVal}, {$methodMessage}]";
        $closures[] = "[[self::class, 'test{$scalarSane}'], {$val}, {$wrongVal}, {$methodMessage}]";
        if (!$self) {
            $closures[] = "['PhabelTest\\Target\\test{$scalarSane}', {$val}, {$wrongVal}, {$funcMessage}]";
        }
        $classFuncs .= "private static function test{$scalarSane}({$scalar} \$data): {$scalar} { return \$data; }\n";
        if (!$self) {
            $funcs .= "function test{$scalarSane}({$scalar} \$data): {$scalar} { return \$data; }\n";
        }
        [$closureMessage, $methodMessage, $funcMessage] = getErrorMessage("", $scalar, "Ret{$scalarSane}", $wrongVal, "value of");
        $closuresRet[] = "[fn (\$data): {$scalar} => \$data, {$val}, {$wrongVal}, {$closureMessage}]";
        $closuresRet[] = "[function (\$data): {$scalar} { return \$data; }, {$val}, {$wrongVal}, {$closureMessage}]";
        $closuresRet[] = "[[\$this, 'testRet{$scalarSane}'], {$val}, {$wrongVal}, {$methodMessage}]";
        $closuresRet[] = "[[self::class, 'testRet{$scalarSane}'], {$val}, {$wrongVal}, {$methodMessage}]";
        if (!$self) {
            $closuresRet[] = "['PhabelTest\\Target\\testRet{$scalarSane}', {$val}, {$wrongVal}, {$funcMessage}]";
        }
        $classFuncs .= "private static function testRet{$scalarSane}(\$data): {$scalar} { return \$data; }\n";
        if (!$self) {
            $funcs .= "function testRet{$scalarSane}(\$data): {$scalar} { return \$data; }\n";
        }
        if (!($count++ % 5)) {
            $i = ($count - 1) / 5;
            $i .= "Test";
            $provider = "[\n" . \implode(",\n", $closures) . "];\n";
            $providerRet = "[\n" . \implode(",\n", $closuresRet) . "];\n";
            $template = <<<EOF
<?php

namespace PhabelTest\\Target;

use PHPUnit\\Framework\\TestCase;

{$funcs}

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer{$i} extends TestCase
{
    /**
     * @dataProvider returnDataProvider
     */
    public function testRet(callable \$c, \$data, \$wrongData, string \$exception) {
        \$this->assertTrue(\$data == \$c(\$data));

        \$this->expectExceptionMessageMatches(\$exception);
        \$c(\$wrongData);
    }
    public function returnDataProvider(): array
    {
        return {$providerRet};
    }
    /**
     * @dataProvider paramDataProvider
     */
    public function test(callable \$c, \$data, \$wrongData, string \$exception) {
        \$this->assertTrue(\$data == \$c(\$data));

        \$this->expectExceptionMessageMatches(\$exception);
        \$c(\$wrongData);
    }
    public function paramDataProvider(): array
    {
        return {$provider};
    }
    
    public static function noop() {}
    
    {$classFuncs}
}
EOF;
            $template = \str_replace("TypeHintReplacerTest", "TypeHintReplacer{$i}", $template);
            $classFuncs = '';
            $funcs = '';
            $closures = [];
            $closuresRet = [];
            \file_put_contents("testsGenerated/Target/TypeHintReplacer{$i}.php", $template);
        }
    }
}
