<?php

namespace Phabel;

use PhabelVendor\PhpParser\Node;
use PhabelVendor\PhpParser\Node\Arg;
use PhabelVendor\PhpParser\Node\Expr;
use PhabelVendor\PhpParser\Node\Expr\ArrayDimFetch;
use PhabelVendor\PhpParser\Node\Expr\Assign;
use PhabelVendor\PhpParser\Node\Expr\AssignOp;
use PhabelVendor\PhpParser\Node\Expr\AssignRef;
use PhabelVendor\PhpParser\Node\Expr\Cast\String_;
use PhabelVendor\PhpParser\Node\Expr\Clone_;
use PhabelVendor\PhpParser\Node\Expr\Eval_;
use PhabelVendor\PhpParser\Node\Expr\FuncCall;
use PhabelVendor\PhpParser\Node\Expr\Include_;
use PhabelVendor\PhpParser\Node\Expr\List_;
use PhabelVendor\PhpParser\Node\Expr\MethodCall;
use PhabelVendor\PhpParser\Node\Expr\New_;
use PhabelVendor\PhpParser\Node\Expr\NullsafeMethodCall;
use PhabelVendor\PhpParser\Node\Expr\NullsafePropertyFetch;
use PhabelVendor\PhpParser\Node\Expr\PostDec;
use PhabelVendor\PhpParser\Node\Expr\PostInc;
use PhabelVendor\PhpParser\Node\Expr\PreDec;
use PhabelVendor\PhpParser\Node\Expr\PreInc;
use PhabelVendor\PhpParser\Node\Expr\PropertyFetch;
use PhabelVendor\PhpParser\Node\Expr\ShellExec;
use PhabelVendor\PhpParser\Node\Expr\StaticCall;
use PhabelVendor\PhpParser\Node\Expr\Variable;
use PhabelVendor\PhpParser\Node\Expr\Yield_;
use PhabelVendor\PhpParser\Node\Expr\YieldFrom;
use PhabelVendor\PhpParser\Node\Name;
use PhabelVendor\PhpParser\Node\Name\FullyQualified;
use PhabelVendor\PhpParser\Node\Stmt\Expression;
use PhabelVendor\PhpParser\ParserFactory;
use ReflectionClass;
/**
 * Various tools.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
abstract class Tools
{
    /**
     * Replace node of one type with another.
     *
     * @param Node   $node        Original node
     * @param string $class       Class of new node
     * @param array  $propertyMap Property map between old and new objects
     *
     * @psalm-param class-string<Node>    $class       Class of new node
     * @psalm-param array<string, string> $propertyMap Property map between old and new objects
     *
     * @psalm-suppress MissingClosureReturnType
     *
     * @return Node
     */
    public static function replaceType(Node $node, string $class, array $propertyMap = []) : Node
    {
        if ($propertyMap) {
            $nodeNew = (new ReflectionClass($class))->newInstanceWithoutConstructor();
            foreach ($propertyMap as $old => $new) {
                $nodeNew->{$new} = $node->{$old};
            }
            $nodeNew->setAttributes($node->getAttributes());
            return $nodeNew;
        }
        return new $class(...[...\array_map(fn(string $name) => $node->{$name}, $node->getSubNodeNames()), $node->getAttributes()]);
    }
    /**
     * Replace type in-place.
     *
     * @param Node   &$node       Original node
     * @param string $class       Class of new node
     * @param array  $propertyMap Property map between old and new objects
     *
     * @psalm-param class-string<Node> $class Class of new node
     * @psalm-param array<string, string> $propertyMap Property map between old and new objects
     *
     * @param-out Node &$node
     *
     * @return void
     */
    public static function replaceTypeInPlace(Node &$node, string $class, array $propertyMap = []) : void
    {
        $node = self::replaceType($node, $class, $propertyMap);
    }
    /**
     * Create variable assignment.
     *
     * @param Variable $name       Variable
     * @param Expr     $expression Expression
     *
     * @return Expression
     */
    public static function assign(Variable $name, Expr $expression) : Expression
    {
        return new Expression(new Assign($name, $expression));
    }
    /**
     * Call function.
     *
     * @param array{0: class-string, 1: string}|callable-string $name          Function name
     * @param Expr|Arg                                          ...$parameters Parameters
     *
     * @return FuncCall|StaticCall
     *
     * @template T as array{0: class-string, 1: string}|callable-string
     * @psalm-param T $name
     *
     * @psalm-return (T is callable-string ? FuncCall : StaticCall)
     */
    public static function call($name, ...$parameters)
    {
        $parameters = \array_map(fn($data) => $data instanceof Arg ? $data : new Arg($data), $parameters);
        return \is_array($name) ? new StaticCall(new FullyQualified($name[0]), $name[1], $parameters) : new FuncCall(new FullyQualified($name), $parameters);
    }
    /**
     * Call method of object.
     *
     * @param Expr     $name          Object name
     * @param string   $method        Method
     * @param Expr|Arg ...$parameters Parameters
     *
     * @return MethodCall
     */
    public static function callMethod(Expr $name, string $method, ...$parameters) : MethodCall
    {
        $parameters = \array_map(fn($data) => $data instanceof Arg ? $data : new Arg($data), $parameters);
        return new MethodCall($name, $method, $parameters);
    }
    /**
     * Convert array, int or other literal to node.
     *
     * @param mixed $data Data to convert
     *
     * @return Node
     */
    public static function fromLiteral($data) : Node
    {
        return self::toNode(\var_export($data, \true) . ';');
    }
    /**
     * Convert code to node.
     *
     * @param string $code Code
     *
     * @memoize $code
     *
     * @return Node
     */
    public static function toNode(string $code) : Node
    {
        $res = (new ParserFactory())->create(ParserFactory::PREFER_PHP7)->parse('<?php ' . $code);
        if ($res === null || empty($res) || !$res[0] instanceof Expression || !isset($res[0]->expr)) {
            throw new \RuntimeException('Invalid code was provided!');
        }
        return $res[0]->expr;
    }
    /**
     * Check if this node or any child node have any side effects (like calling other methods, or assigning variables).
     *
     * @param ?Expr $node Node
     *
     * @return bool
     */
    public static function hasSideEffects(?Expr $node) : bool
    {
        if (!$node) {
            return \false;
        }
        if ($node->hasAttribute('hasSideEffects') || $node instanceof String_ || $node instanceof ArrayDimFetch || $node instanceof Assign || $node instanceof AssignOp || $node instanceof AssignRef || $node instanceof Clone_ || $node instanceof Eval_ || $node instanceof FuncCall || $node instanceof Include_ || $node instanceof List_ || $node instanceof MethodCall || $node instanceof New_ || $node instanceof NullsafeMethodCall || $node instanceof NullsafePropertyFetch || $node instanceof PostDec || $node instanceof PostInc || $node instanceof PreDec || $node instanceof PreInc || $node instanceof PropertyFetch || $node instanceof StaticCall || $node instanceof Yield_ || $node instanceof YieldFrom || $node instanceof ShellExec) {
            $node->setAttribute('hasSideEffects', \true);
            return \true;
        }
        /** @var string */
        foreach ($node->getSubNodeNames() as $name) {
            if ($node->{$name} instanceof Expr) {
                if (self::hasSideEffects($node->{$name})) {
                    $node->setAttribute('hasSideEffects', \true);
                    return \true;
                }
            } elseif (\is_array($node->{$name})) {
                /** @var Node|Node[]|string */
                foreach ($node->{$name} as $var) {
                    if ($var instanceof Expr && self::hasSideEffects($var)) {
                        $node->setAttribute('hasSideEffects', \true);
                        return \true;
                    }
                }
            }
        }
        return \false;
    }
    /**
     * Get fully qualified name.
     *
     * @param Node $node
     * @param class-string $alt Alternative name
     *
     * @return class-string
     */
    public static function getFqdn(Node $node, string $alt = '') : string
    {
        if ($node instanceof FullyQualified) {
            return (string) $node;
        }
        if (isset($node->namespacedName)) {
            return (string) $node->namespacedName;
        }
        if (!$node->getAttribute('resolvedName')) {
            if ($alt) {
                return $alt;
            }
            throw new \Phabel\UnresolvedNameException();
        }
        return (string) $node->getAttribute('resolvedName', $node->getAttribute('namespacedName'));
    }
    /**
     * Create a new object extended from this object, with the specified additional trait + interface.
     *
     * @param object $obj
     * @param string $trait
     *
     * @template T as object
     * @psalm-param T $obj
     *
     * @return object
     */
    public static function cloneWithTrait(object $obj, string $trait) : object
    {
        /** @psalm-var int */
        static $count = 0;
        /** @psalm-var array<string, class-string<T>> $memoized */
        static $memoized = [];
        $reflect = new ReflectionClass($obj);
        $r = $reflect;
        while ($r && $r->isAnonymous()) {
            $r = $r->getParentClass();
        }
        $extend = "extends \\" . $r->getName();
        if (isset($memoized["{$trait} {$extend}"])) {
            /** @psalm-suppress MixedMethodCall */
            $newObj = ($phabel_059673bb811365c4 = $memoized["{$trait} {$extend}"]) || \true ? new $phabel_059673bb811365c4() : \false;
        } else {
            $memoized["{$trait} {$extend}"] = "phabelTmpClass{$count}";
            $eval = "class phabelTmpClass{$count} {$extend} {\n                use \\{$trait};\n                public function __construct() {}\n            }\n            return new phabelTmpClass{$count};";
            $count++;
            /** @var object */
            $newObj = eval($eval);
        }
        $reflectNew = new ReflectionClass($newObj);
        do {
            if ($tmp = $reflectNew->getParentClass()) {
                $reflectNew = $tmp;
            }
            foreach ($reflect->getProperties() as $prop) {
                if ($reflectNew->hasProperty($prop->getName())) {
                    $propNew = $reflectNew->getProperty($prop->getName());
                    $propNew->setAccessible(\true);
                    $prop->setAccessible(\true);
                    $propNew->setValue($newObj, $prop->getValue($obj));
                }
            }
        } while ($reflect = $reflect->getParentClass());
        return $newObj;
    }
    /**
     * Checks private property exists in an object.
     *
     * @param object $obj Object
     * @param string $var Attribute name
     *
     * @psalm-suppress InvalidScope
     * @psalm-suppress PossiblyInvalidFunctionCall
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedPropertyFetch
     * @psalm-suppress MixedInferredReturnType
     *
     * @return bool
     * @access public
     */
    public static function hasVar(object $obj, string $var) : bool
    {
        return \Closure::bind(function () use($var) : bool {
            return isset($this->{$var});
        }, $obj, \get_class($obj))->__invoke();
    }
    /**
     * Accesses a private variable from an object.
     *
     * @param object $obj Object
     * @param string $var Attribute name
     *
     * @psalm-suppress InvalidScope
     * @psalm-suppress PossiblyInvalidFunctionCall
     * @psalm-suppress MixedPropertyFetch
     *
     * @return mixed
     * @access public
     */
    public static function &getVar($obj, string $var)
    {
        return \Closure::bind(
            /** @return mixed */
            function &() use($var) {
                return $this->{$var};
            },
            $obj,
            \get_class($obj)
        )->__invoke();
    }
    /**
     * Sets a private variable in an object.
     *
     * @param object $obj Object
     * @param string $var Attribute name
     * @param mixed  $val Attribute value
     *
     * @psalm-suppress InvalidScope
     * @psalm-suppress PossiblyInvalidFunctionCall
     *
     * @return void
     *
     * @access public
     */
    public static function setVar($obj, string $var, &$val) : void
    {
        \Closure::bind(function () use($var, &$val) {
            $this->{$var} =& $val;
        }, $obj, \get_class($obj))->__invoke();
    }
    /**
     * Adapted from https://gist.github.com/divinity76/01ef9ca99c111565a72d3a8a6e42f7fb
     * returns number of cpu cores
     * Copyleft 2018, license: WTFPL.
     * @throws \RuntimeException
     * @throws \LogicException
     * @psalm-suppress ForbiddenCode
     */
    public static function getCpuCount() : int
    {
        static $result = -1;
        if ($result !== -1) {
            return $result;
        }
        if (\defined('PHP_WINDOWS_VERSION_MAJOR')) {
            /*
            $str = trim((string) shell_exec('wmic cpu get NumberOfCores 2>&1'));
            if (!preg_match('/(\d+)/', $str, $matches)) {
                throw new \RuntimeException('wmic failed to get number of cpu cores on windows!');
            }
            return ((int) $matches [1]);
            */
            return $result = 1;
        }
        if (self::ini_get('pcre.jit') === '1' && \PHP_OS === 'Darwin' && \version_compare(\PHP_VERSION, '7.3.0') >= 0 && \version_compare(\PHP_VERSION, '7.4.0') < 0) {
            return $result = 1;
        }
        if (!\extension_loaded('pcntl')) {
            return $result = 1;
        }
        $has_nproc = \trim((string) @\shell_exec('command -v nproc'));
        if ($has_nproc) {
            $ret = @\shell_exec('nproc');
            if (\is_string($ret)) {
                $ret = \trim($ret);
                $tmp = \filter_var($ret, \FILTER_VALIDATE_INT);
                if (\is_int($tmp)) {
                    return $result = $tmp;
                }
            }
        }
        $ret = @\shell_exec('sysctl -n hw.ncpu');
        if (\is_string($ret)) {
            $ret = \trim($ret);
            $tmp = \filter_var($ret, \FILTER_VALIDATE_INT);
            if (\is_int($tmp)) {
                return $result = $tmp;
            }
        }
        if (\is_readable('/proc/cpuinfo')) {
            $cpuinfo = \file_get_contents('/proc/cpuinfo');
            $count = \substr_count($cpuinfo, 'processor');
            if ($count > 0) {
                return $result = $count;
            }
        }
        return $result = 1;
    }
    /**
     * Safely get value from php.ini.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function ini_get(string $key, $default = null)
    {
        try {
            if (\function_exists('ini_get')) {
                return @\ini_get($key);
            }
        } catch (\Throwable $e) {
        }
        return $default;
    }
    /**
     * Recursively copy a directory to another, calling a callback for files.
     *
     * @param string $input
     * @param string $output
     * @param (callable(SplFileInfo, string): bool)|null $cb
     * @return void
     */
    public static function traverseCopy(string $input, string $output, ?callable $cb = null) : void
    {
        if (!\file_exists($output)) {
            \mkdir($output, 0777, \true);
        }
        $output = \realpath($output);
        $input = \realpath($input);
        $it = new \RecursiveDirectoryIterator($input, \RecursiveDirectoryIterator::SKIP_DOTS);
        $ri = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::SELF_FIRST);
        /** @var \SplFileInfo $file */
        foreach ($ri as $file) {
            $rel = $ri->getSubPathname();
            $targetPath = $output . \DIRECTORY_SEPARATOR . $rel;
            if ($file->isDir()) {
                if (!\file_exists($targetPath)) {
                    \mkdir($targetPath, $file->getPerms(), \true);
                }
            } elseif ($file->isLink()) {
                $dest = $file->getRealPath();
                if ($dest !== \false && \str_starts_with($dest, $input)) {
                    $dest = \trim(\substr($dest, \strlen($input)), \DIRECTORY_SEPARATOR);
                    $dest = \str_repeat('..' . \DIRECTORY_SEPARATOR, \substr_count($rel, \DIRECTORY_SEPARATOR)) . $dest;
                    $link = $output . \DIRECTORY_SEPARATOR . $rel;
                    if (\file_exists($link)) {
                        \unlink($link);
                    }
                    \symlink($dest, $link);
                }
            } elseif ($file->isFile()) {
                if ($cb && $cb($file, $targetPath)) {
                    // All done!
                } elseif (\realpath($targetPath) !== $file->getRealPath()) {
                    \copy($file->getRealPath(), $targetPath);
                    \chmod($targetPath, $file->getPerms());
                }
            }
        }
    }
}
