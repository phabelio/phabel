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
use Phabel\Target\Php;
use Phabel\Tools;
use PhpParser\Builder\Class_;
use PhpParser\Builder\Method;
use PhpParser\Builder\Namespace_;
use PhpParser\Builder\Use_;
use PhpParser\Internal\PrintableNewAnonClassNode;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\LogicalAnd;
use PhpParser\Node\Expr\BinaryOp\LogicalOr;
use PhpParser\Node\Expr\BinaryOp\LogicalXor;
use PhpParser\Node\Expr\BitwiseNot;
use PhpParser\Node\Expr\Cast\Unset_;
use PhpParser\Node\Expr\Error;
use PhpParser\Node\Expr\Exit_;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Print_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Expr\UnaryPlus;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\EncapsedStringPart;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Class_ as StmtClass_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Use_ as StmtUse_;
use PhpParser\Node\VarLikeIdentifier;
use PhpParser\PrettyPrinter\Standard;

require_once 'vendor/autoload.php';

foreach (Php::VERSIONS as $version) {
    if (empty(\shell_exec("which php$version 2>&1"))) {
        echo("Could not find PHP $version!".PHP_EOL);
        die(1);
    }
}

class ExpressionGenerator
{
    private Standard $printer;
    private function format(Node $code)
    {
        static $count = 0;
        $count++;
        $code = (new Class_("lmao{$count}"))->addStmt(
            (new Method("te"))
            ->addStmt($code)
            ->getNode()
        )->getNode();
        return $this->printer->prettyPrintFile([$code]);
    }
    private function readUntilPrompt($resource)
    {
        $data = \fread($resource, 6);
        while (!\str_ends_with($data, "php > ")) {
            $data .= \fread($resource, 1);
        }
        return \substr($data, 0, -6);
    }
    private array $robin = [];
    private array $processes = [];
    private array $pipes = [];
    private function checkSyntaxVersion(int $version, string $code)
    {
        $code = \str_replace(["\n", '<?php'], '', $code)."\n";

        $x = $this->robin[$version];
        $this->robin[$version]++;
        $this->robin[$version] %= \count($this->pipes[$version]);

        \fputs($this->pipes[$version][$x][0], $code);

        $result = $this->readUntilPrompt($this->pipes[$version][$x][1]);
        $result = \str_replace(['{', '}'], '', \substr(\preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $result), \strlen($code)));
        $result = \trim($result);
        //var_dump($code, "Result for $version is: $result");
        return \strlen($result) === 0;
    }
    private function checkSyntax(string $code, int $startFrom = 56)
    {
        if (!$startFrom) {
            return $startFrom;
        }

        foreach (Php::VERSIONS as $version) {
            if ($version < $startFrom) {
                continue;
            }
            if ($this->checkSyntaxVersion($version, $code)) {
                return $version;
            }
        }
        return 0;
    }

    private $result = [
        'main' => [],     // Needs adaptation for nested expressions
        'isset' => [],    // Needs adaptation for nested expressions in isset
    ];
    /** @psalm-var array<int, array<int, Node>> */
    private array $tests = [];
    private $versionMap = [];


    private function checkPossibleValue($arg, $name, $key, $class, $baseArgs, $isArray)
    {
        $subVersion = \max($this->versionMap[\get_debug_type($arg)] ?? 0, $this->versionMap[$class]);

        $arguments = $baseArgs;
        $arguments[$key] = $isArray ? [$arg] : $arg;

        $code = $this->format($prev = new $class(...$arguments));
        $curVersion = $this->checkSyntax($code, $subVersion);
        if ($curVersion && $curVersion !== $subVersion) {
            $this->result['main'][$curVersion][$class][$name][\get_debug_type($arg)] = true;
            echo "Min $curVersion for $code\n";
        }
        if ($curVersion
            && !($class === AssignRef::class && $arg instanceof New_)
            && !($class === Yield_::class && $name === 'key' && ($arg instanceof LogicalAnd || $arg instanceof LogicalOr || $arg instanceof LogicalXor))
            && !(\in_array($class, [MethodCall::class, StaticCall::class]) && $name === 'name' && ($arg instanceof Array_ || $arg instanceof Print_))
            && !(\in_array($class, [UnaryPlus::class, UnaryMinus::class, BitwiseNot::class]) && $arg instanceof Array_)
            && !(\in_array($class, [Variable::class, StaticPropertyFetch::class, PropertyFetch::class]) && $arg instanceof Array_)
        ) {
            $this->tests[] = $prev;
        }

        $code = $this->format(new Isset_([$prev]));
        $curVersion = $this->checkSyntax($code, $subVersion);
        if ($curVersion && $curVersion !== $subVersion) {
            $this->result['isset'][$curVersion][$class][$name][\get_debug_type($arg)] = true;
            echo "Min $curVersion for $code\n";
        }
        if ($curVersion
            && !(\in_array($class, [Variable::class, StaticPropertyFetch::class, PropertyFetch::class]) && $arg instanceof Array_)
        ) {
            $this->tests[] = new Isset_([$prev]);
        }
    }

    public function run()
    {
        $this->printer = new Standard(['shortArraySyntax' => true]);
        foreach (Php::VERSIONS as $version) {
            $cmd = "php$version -a 2>&1";
            $this->pipes[$version] = [];
            $this->processes[$version] = [];
            $this->robin[$version] = 0;
            for ($x = 0; $x < 15; $x++) {
                $this->processes[$version][$x] = \proc_open($cmd, [0 => ['pipe', 'r'], 1 => ['pipe', 'w']], $this->pipes[$version][$x]);
                $this->readUntilPrompt($this->pipes[$version][$x][1]);
            }
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
                && $class->getName() !== Unset_::class) {
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
                    if (\str_starts_with($subtype, 'Node')) {
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


        $disallowedIssetExprs = [];
        foreach ($exprInstances as $expr) {
            if (!$this->checkSyntaxVersion(56, $this->format(new Isset_([$expr])))) {
                $disallowedIssetExprs[\get_class($expr)] = true;
            }
        }
        $disallowedIssetExprs = \var_export($disallowedIssetExprs, true);
        $disallowedIssetExprs = <<< PHP
<?php

namespace Phabel\Target\Php70\NullCoalesce;

use Phabel\Plugin;

abstract class DisallowedExpressions extends Plugin
{
    const EXPRESSIONS = $disallowedIssetExprs;
}
PHP;
        \file_put_contents("src/Target/Php70/NullCoalesce/DisallowedExpressions.php", $disallowedIssetExprs);

        foreach ($exprInstances as $class => $instance) {
            $this->versionMap[$class] = $this->checkSyntax($this->format($instance)) ?: 1000;
        }

        $wait = [];
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
                    $this->checkPossibleValue($arg, $name, $key, $class, $baseArgs, $isArray);
                }
            }
        }

        $keys = [];
        foreach ($this->result['main'] as $version) {
            $keys = \array_merge_recursive($keys, $version);
        }
        foreach ($keys as &$values) {
            $values = \array_keys($values);
        }
        foreach (Php::VERSIONS as $version) {
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
        foreach ($this->result as $type => $results) {
            $name = $type === 'main' ? 'NestedExpressionFixer' : 'IssetExpressionFixer';
            $type = $type === 'main' ? NestedExpressionFixer::class : IssetExpressionFixer::class;
            foreach ($results as $version => $config) {
                $config = \var_export($config, true);
                $code = <<< PHP
<?php

namespace Phabel\Target\Php$version;

use Phabel\Plugin;
use $type as fixer;

/**
 * Expression fixer for PHP $version
 */
class $name extends Plugin
{
    /**
     * {@inheritDoc}
     */
    public static function next(array \$config): array
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

        $comment = <<< PHP
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
PHP;
        foreach (\glob("testsGenerated/Target/Expression*") as $file) {
            \unlink($file);
        }
        $prettyPrinter = new PhpParser\PrettyPrinter\Standard(['shortArraySyntax' => true]);

        foreach (\array_chunk($this->tests, 100) as $stmts) {
            $i = \hash('sha256', $prettyPrinter->prettyPrintFile($stmts))."Test";

            $sortedStmts = [];
            foreach ($stmts as $stmt) {
                $method = 'test'.\hash('sha256', $prettyPrinter->prettyPrintFile([$stmt]));
                $sortedStmts[$method] = (new Method($method))
                    ->addStmt(
                        new MethodCall(
                            new Variable('this'),
                            'assertTrue',
                            [new Arg(Tools::fromLiteral(true))]
                        )
                    )
                    ->addStmt(
                        new Expression(new ArrowFunction(
                            ['expr' => $stmt]
                        ))
                    )
                    ->getNode();
            }
            \ksort($sortedStmts);

            $class = (new Class_("Expression$i"))
                ->extend("TestCase")
                ->setDocComment($comment)
                ->addStmts(\array_values($sortedStmts))
                ->getNode();

            $class = (new Namespace_(PhabelTest\Target::class))
                ->addStmt(new Use_(\PHPUnit\Framework\TestCase::class, StmtUse_::TYPE_NORMAL))
                ->addStmt($class)
                ->getNode();

            $class = $prettyPrinter->prettyPrintFile([$class]);

            if (\file_exists("testsGenerated/Target/Expression$i.php")) {
                throw new \RuntimeException("Expression$i.php already exists!");
            }
            \file_put_contents("testsGenerated/Target/Expression$i.php", $class);
        }
    }
}

(new ExpressionGenerator)->run();
