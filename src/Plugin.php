<?php

namespace Phabel;

use Phabel\PluginGraph\PackageContext;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Expression;
use PhpParser\ParserFactory;
use ReflectionClass;

/**
 * Plugin
 * 
 * @author Daniil Gentili <daniil@daniil.it>
 */
abstract class Plugin implements PluginInterface
{
    /**
     * Configuration array.
     */
    private array $config = [];
    /**
     * Package context.
     */
    private PackageContext $ctx;
    /**
     * Set configuration array.
     *
     * @param array $config
     * @return void
     */
    public function setConfigArray(array $config): void
    {
        $this->config = $config;
    }
    /**
     * Set package context.
     *
     * @param PackageContext $ctx Ctx
     *
     * @return void
     */
    public function setPackageContext(PackageContext $ctx): void
    {
        $this->ctx = $ctx;
    }
    /**
     * Get package context.
     *
     * @return PackageContext
     */
    public function getPackageContext(): PackageContext
    {
        return $this->ctx;
    }
    /**
     * Check if plugin should run.
     *
     * @param string $package Package name
     *
     * @return boolean
     */
    public function shouldRun(string $package): bool
    {
        return $this->ctx->has($package);
    }
    /**
     * Check if plugin should run.
     *
     * @param string $file File name
     *
     * @return boolean
     */
    public function shouldRunFile(string $file): bool
    {
        return true;
    }
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
     * @return Node
     */
    public static function replaceType(Node $node, string $class, array $propertyMap = []): Node
    {
        if ($propertyMap) {
            /** @var Node */
            $nodeNew = (new ReflectionClass($class))->newInstanceWithoutConstructor();
            foreach ($propertyMap as $old => $new) {
                $nodeNew->{$new} = $node->{$old};
            }
            $nodeNew->setAttributes($node->getAttributes());
            return $nodeNew;
        }
        return new $class(
            ...\array_map(fn (string $name) => $node->{$name}, $node->getSubNodeNames()),
            $node->getAttributes()
        );
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
    public static function replaceTypeInPlace(Node &$node, string $class, array $propertyMap = []): void
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
    public static function assign(Variable $name, Expr $expression): Expression
    {
        return new Expression(
            new Assign(
                $name,
                $expression
            )
        );
    }
    /**
     * Call function.
     *
     * @param class-string|array{0: class-string, 1: string}|callable-string $name          Function name
     * @param Expr|Arg                                                       ...$parameters Parameters
     *
     * @return FuncCall|StaticCall
     */
    public static function call($name, ...$parameters)
    {
        $parameters = \array_map(fn ($data) => $data instanceof Arg ? $data : new Arg($data), $parameters);
        return \is_array($name) ? new StaticCall(new Name($name[0]), $name[1], $parameters) : new FuncCall(new Name($name), $parameters);
    }
    /**
     * Call polyfill function from current plugin.
     *
     * @param string   $name          Function name
     * @param Expr|Arg ...$parameters Parameters
     *
     * @return StaticCall
     */
    protected static function callPoly(string $name, ...$parameters): StaticCall
    {
        return self::call([static::class, $name], ...$parameters);
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
    public static function callMethod(Expr $name, string $method, ...$parameters): MethodCall
    {
        $parameters = \array_map(fn ($data) => $data instanceof Arg ? $data : new Arg($data), $parameters);
        return new MethodCall($name, $method, $parameters);
    }
    /**
     * Convert array, int or other literal to node.
     *
     * @param mixed $data Data to convert
     *
     * @return Node
     */
    public static function toLiteral($data): Node
    {
        return self::toNode(\var_export($data, true));
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
    public static function toNode(string $code): Node
    {
        $res = (new ParserFactory)->create(ParserFactory::PREFER_PHP7)->parse('<?php '.$code);
        if ($res === null || empty($res) || !$res[0] instanceof Expression || !isset($res[0]->expr)) {
            throw new \RuntimeException('Invalid code was provided!');
        }
        return $res[0]->expr;
    }
    /**
     * {@inheritDoc}
     */
    public function getConfig(string $key, $default)
    {
        return $this->config[$key] ?? $default;
    }
    /**
     * {@inheritDoc}
     */
    public function setConfig(string $key, $value): void
    {
        $this->config[$key] = $value;
    }
    /**
     * {@inheritDoc}
     */
    public static function mergeConfigs(array ...$configs): array
    {
        return $configs;
    }
    /**
     * {@inheritDoc}
     */
    public static function splitConfig(array $config): array
    {
        return [$config];
    }
    /**
     * {@inheritDoc}
     */
    public static function composerRequires(): array
    {
        return [];
    }
    /**
     * {@inheritDoc}
     */
    public static function runAfter(): array
    {
        return [];
    }
    /**
     * {@inheritDoc}
     */
    public static function runBefore(): array
    {
        return [];
    }
    /**
     * {@inheritDoc}
     */
    public static function runWithBefore(): array
    {
        return [];
    }
    /**
     * {@inheritDoc}
     */
    public static function runWithAfter(): array
    {
        return [];
    }
}
