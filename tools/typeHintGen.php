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
function escapeRegex($in)
{
    if (!\is_string($in)) {
        if (!(\is_string($in) || \is_object($in) && \method_exists($in, '__toString') || (\is_bool($in) || \is_numeric($in)))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($in) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($in) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $in = (string) $in;
    }
    $phabelReturn = \var_export('~' . \str_replace(['\\', '(', ')', '$', '?', '|'], ['\\\\', '\\(', '\\)', '\\$', '\\?', '\\|'], $in) . '~', true);
    if (!\is_string($phabelReturn)) {
        if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $phabelReturn = (string) $phabelReturn;
    }
    return $phabelReturn;
}
function getErrorMessage($scalarParam, $scalar, $scalarSane, $wrongVal, $to)
{
    if (!\is_string($scalarParam)) {
        if (!(\is_string($scalarParam) || \is_object($scalarParam) && \method_exists($scalarParam, '__toString') || (\is_bool($scalarParam) || \is_numeric($scalarParam)))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($scalarParam) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($scalarParam) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $scalarParam = (string) $scalarParam;
    }
    if (!\is_string($scalar)) {
        if (!(\is_string($scalar) || \is_object($scalar) && \method_exists($scalar, '__toString') || (\is_bool($scalar) || \is_numeric($scalar)))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($scalar) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($scalar) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $scalar = (string) $scalar;
    }
    if (!\is_string($scalarSane)) {
        if (!(\is_string($scalarSane) || \is_object($scalarSane) && \method_exists($scalarSane, '__toString') || (\is_bool($scalarSane) || \is_numeric($scalarSane)))) {
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($scalarSane) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($scalarSane) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $scalarSane = (string) $scalarSane;
    }
    if (!\is_string($to)) {
        if (!(\is_string($to) || \is_object($to) && \method_exists($to, '__toString') || (\is_bool($to) || \is_numeric($to)))) {
            throw new \TypeError(__METHOD__ . '(): Argument #5 ($to) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($to) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $to = (string) $to;
    }
    try {
        $f = eval("return new class { public function r() { return fn ({$scalarParam} \$data): {$scalar} => \$data; }};");
        $phabel_5c41897c31d84027 = $f->r();
        $phabel_5c41897c31d84027(eval("return {$wrongVal};"));
    } catch (\Exception $e) {
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
    } catch (\Error $e) {
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
    $phabelReturn = [escapeRegex($closureMessage), escapeRegex($methodMessage), escapeRegex($funcMessage)];
    if (!\is_array($phabelReturn)) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
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
        list($closureMessage, $methodMessage, $funcMessage) = getErrorMessage($scalar, $scalar, $scalarSane, $wrongVal, "to");
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
        list($closureMessage, $methodMessage, $funcMessage) = getErrorMessage("", $scalar, "Ret{$scalarSane}", $wrongVal, "value of");
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
