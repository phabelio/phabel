<?php
/**
 * This file generates an array containing all possible expression nodes, generated using default parameters.
 * Then, for each expression that accepts another expression as subnode, it tries to use all the expressions generated in the previous step,
 *  for each subnode, to test compatibility with various versions of the PHP lexer.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */

use HaydenPierce\ClassFinder\ClassFinder;
use Phabel\Plugin\IssetExpressionFixer;
use Phabel\Plugin\NestedExpressionFixer;
use PhpParser\Builder\Class_;
use PhpParser\Builder\Method;
use PhpParser\Internal\PrintableNewAnonClassNode;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Error;
use PhpParser\Node\Expr\Exit_;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\EncapsedStringPart;
use PhpParser\Node\Stmt\Class_ as StmtClass_;
use PhpParser\Node\VarLikeIdentifier;

require 'vendor/autoload.php';

function format(Node $code): string
{
    static $count = 0;
    $count++;
    $code = (new Class_("lmao$count"))->addStmt(
        (new Method("te"))
            ->addStmt($code)
            ->getNode()
    )->getNode();
    $prettyPrinter = new PhpParser\PrettyPrinter\Standard(['shortArraySyntax' => true]);
    return $prettyPrinter->prettyPrintFile([$code]);
}
function readUntilPrompt($resource): string
{
    $data = '';
    while (\substr($data, -6) !== 'php > ') {
        $data .= \fread($resource, 1);
    }
    return \substr($data, 0, -6);
}
function checkSyntaxVersion(int $version, string $code): bool
{
    $hasPrompt = $version < 80;
    $code = \str_replace(["\n", '<?php'], '', $code)."\n";
    static $processes = [];
    static $pipes = [];
    if (!isset($processes[$version])) {
        $cmd = "php$version -a 2>&1";
        $processes[$version] = \proc_open($cmd, [0 => ['pipe', 'r'], 1 => ['pipe', 'w']], $pipes[$version]);
        if ($hasPrompt) {
            readUntilPrompt($pipes[$version][1]);
        } else {
            \fgets($pipes[$version][1]);
            \fgets($pipes[$version][1]);
        }
    }
    if (!$hasPrompt) {
        $code .= "echo 'php > ';\n";
    }
    \fputs($pipes[$version][0], $code);
    if ($hasPrompt) {
        $result = \str_replace(['{', '}'], '', \substr(\preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', readUntilPrompt($pipes[$version][1])), \strlen($code)));
    } else {
        $result = readUntilPrompt($pipes[$version][1]);
    }
    //var_dump($code, "Result for $version is: " .trim($result));
    $result = \trim($result);
    return \strlen($result) === 0;
}
function checkSyntax(string $code, int $startFrom = 56): int
{
    if (!$startFrom) {
        return $startFrom;
    }

    foreach ([56, 70, 73, 74, 80] as $version) {
        if ($version < $startFrom) {
            continue;
        }
        if (checkSyntaxVersion($version, $code)) {
            return $version;
        }
    }
    return 0;
}

/** @var ReflectionClass[] */
$expressions = [];
foreach (ClassFinder::getClassesInNamespace('PhpParser', ClassFinder::RECURSIVE_MODE) as $class) {
    $class = new ReflectionClass($class);
    if ($class->isSubclassOf(Expr::class) && !$class->isAbstract()
        && $class->getName() !== PrintableNewAnonClassNode::class
        && $class->getName() !== ArrowFunction::class
        && $class->getName() !== Error::class
        && $class->getName() !== List_::class
        && $class->getName() !== ArrayItem::class
        && $class->getName() !== EncapsedStringPart::class
        && $class->getName() !== Exit_::class
        && $class->getName() !== List_::class) {
        $expressions []= $class;
    }
}

$instanceArgs = [];
$instanceArgNames = [];
$instanceArgTypes = [];

$exprInstances = [];
foreach ($expressions as $expr) {
    $class = $expr->getName();
    $method = $expr->getMethod('__construct');
    if ($method->getNumberOfParameters() === 1) {
        $exprInstances[$class] = $expr->newInstance();
        continue; // Is a magic constant or such
    }
    \preg_match_all('/@param (?<type>\S+) +\$(?<name>\S+)/', $method->getDocComment(), $matches);
    $types = \array_combine($matches['name'], $matches['type']);
    foreach ($types as &$type) {
        $type = \explode("|", $type);
        foreach ($type as $key => &$subtype) {
            if (str_starts_with($subtype, 'Node')) {
                $subtype = 'PhpParser\\'.$subtype;
            } elseif ($subtype === 'Error') {
                unset($type[$key]);
            } elseif ($subtype === 'Identifier') {
                $subtype = Identifier::class;
            } elseif ($subtype === 'Name') {
                $subtype = Name::class;
            } elseif ($subtype === 'Expr') {
                $subtype = Expr::class;
            } elseif ($subtype === 'VarLikeIdentifier') {
                $subtype = VarLikeIdentifier::class;
            }
        }
    }
    $params = $method->getParameters();
    $hasExpr = false;
    $arguments = [];
    $argNames = [];
    $argTypes = [];
    foreach ($params as $key => $param) {
        $paramStr = (string) $param->getType();
        $argNames[] = $param->getName();
        switch ($paramStr) {
            case Expr::class:
                $argTypes[$key] = [false, [Expr::class]];
                $arguments[] = new Variable("test");
                break;
            case Variable::class:
                $arguments[] = new Variable("test");
                break;
            case Name::class:
                $arguments[] = new Name('self');
                break;
            case 'array':
                if (\in_array('Expr[]', $types[$param->getName()] ?? [])) {
                    $argTypes[$key] = [true, [Expr::class]];
                    $arguments[] = [new Variable('test')];
                } else {
                    $arguments[] = [];
                }
                break;
            case 'bool':
                $arguments[] = false;
                break;
            case 'float':
            case 'int':
                $arguments[] = 0;
                break;
            case 'string':
                $arguments[] = 'test';
                break;
            default:
                $argTypes[$key] = [false, $types[$param->getName()]];
                $arguments[] = new Variable("test");
                break;
        }
    }
    $exprInstances[$class] = $expr->newInstanceArgs($arguments);
    if (\count($argTypes)) {
        $instanceArgs[$class] = $arguments;
        $instanceArgNames[$class] = $argNames;
        $instanceArgTypes[$class] = $argTypes;
    }
}

$versionMap = [];

$result = [
    'main' => [],     // Needs adaptation for nested expressions
    'isset' => [],    // Needs adaptation for nested expressions in isset
];

$newInstances = [];
foreach ($exprInstances as $class => $instance) {
    $version = checkSyntax(format($prev = $instance));
    $versionMap[$class] = $version ?: 1000;
}

foreach ($instanceArgTypes as $class => $argTypes) {
    $baseArgs = $instanceArgs[$class];
    foreach ($argTypes as $key => [$isArray, $types]) {
        $name = $instanceArgNames[$class][$key];
        $possibleValues = [];
        foreach ($types as $type) {
            switch ($type) {
                case Expr::class:
                    $possibleValues = \array_merge($possibleValues, $exprInstances);
                    break;
                case Name::class:
                    $possibleValues[] = new Name('self');
                    break;
                case Variable::class:
                    $possibleValues[] = new Variable("test");
                    break;
                case Identifier::class:
                    $possibleValues[] = new Identifier('test');
                    break;
                case VarLikeIdentifier::class:
                    $possibleValues[] = new Identifier('test');
                    break;
                case StmtClass_::class:
                    // Avoid using anonymous classes
                    //$possibleValues[] = new StmtClass_(null);
                    break;
                case 'string':
                    $possibleValues[] = 'test';
                    break;
                case 'null':
                    $possibleValues[] = null;
                    break;
                default:
                    throw new Exception($type);
            }
        }
        foreach ($possibleValues as $arg) {
            $subVersion = \max($versionMap[\get_debug_type($arg)] ?? 0, $versionMap[$class]);

            $arguments = $baseArgs;
            $arguments[$key] = $isArray ? [$arg] : $arg;

            $code = format($prev = new $class(...$arguments));
            $curVersion = checkSyntax($code, $subVersion);
            if ($curVersion && $curVersion !== $subVersion) {
                $result['main'][$curVersion][$class][$name][\get_debug_type($arg)] = true;
                echo "Min $curVersion for $code\n";
            }

            $code = format(new Isset_([$prev]));
            $curVersion = checkSyntax($code, $subVersion);
            if ($curVersion && $curVersion !== $subVersion) {
                $result['isset'][$curVersion][$class][$name][\get_debug_type($arg)] = true;
                echo "Min $curVersion for $code\n";
            }
        }
    }
}

$keys = [];
foreach ($result['main'] as $version) {
    $keys = \array_merge_recursive($keys, $version);
}
foreach ($keys as &$values) {
    $values = \array_keys($values);
}
foreach ([56, 70, 71, 72, 73, 74, 80] as $version) {
    foreach (['NestedExpressionFixer', 'IssetExpressionFixer'] as $name) {
        $code = <<< PHP
        <?php

        namespace Phabel\Target\Php$version;

        use Phabel\Plugin;

        class $name extends Plugin
        {
        }
        PHP;
        \file_put_contents("src/Target/Php$version/$name.php", $code);
    }
}
\var_dump($keys);
foreach ($result as $type => $results) {
    $name = $type === 'main' ? 'NestedExpressionFixer' : 'IssetExpressionFixer';
    $type = $type === 'main' ? NestedExpressionFixer::class : IssetExpressionFixer::class;
    foreach ($results as $version => $config) {
        $config = \var_export($config, true);
        $code = <<< PHP
        <?php

        namespace Phabel\Target\Php$version;

        use Phabel\Plugin;
        use $type as fixer;

        class $name extends Plugin
        {
            /**
             * Expression fixer for PHP $version
             *
             * @return array
             */
            public static function runAfter(): array
            {
                return [
                    fixer::class => $config
                ];
            }
        }
        PHP;
        \file_put_contents("src/Target/Php$version/$name.php", $code);
    }
}
\file_put_contents('result.php', '<?php $result = '.\var_export($result, true).";");

$ckeys = \array_fill_keys(\array_map(fn ($a) => $a->getname(), $expressions), true);
foreach ($result as &$type) {
    foreach ($type as &$version) {
        foreach ($version as &$class) {
            foreach ($class as &$arguments) {
                $arguments = \array_diff_key($ckeys, $arguments);
            }
        }
    }
}
\file_put_contents('resultReverse.php', '<?php $result = '.\var_export($result, true).";");