<?php

namespace Phabel;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Expression;
use PhpParser\ParserFactory;
use ReflectionClass;

abstract class Plugin implements PluginInterface
{
    /**
     * Configuration array.
     */
    private array $config = [];
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
     * Call function.
     *
     * @param class-string|array{0: class-string, 1: string} $name          Function name
     * @param Expr|Arg                                       ...$parameters Parameters
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
     * Convert array, int or other literal to node.
     *
     * @param mixed $data Data to convert
     *
     * @return Node
     */
    public static function toLiteral($data): Node
    {
        /** @var Node[] */
        static $cache = [];
        $data = \var_export($data, true);
        if (isset($cache[$data])) {
            return $cache[$data];
        }
        $res = (new ParserFactory)->create(ParserFactory::PREFER_PHP7)->parse('<?php '.$data);
        if ($res === null || empty($res) || !$res[0] instanceof Expression || !isset($res[0]->expr)) {
            throw new \RuntimeException('An invalid literal was provided!');
        }
        return $cache[$data] = $res[0]->expr;
    }
    /**
     * {@inheritDoc}
     */
    public function getConfig(string $key)
    {
        return $this->config[$key];
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
    public function needs()
    {
        return [];
    }
    /**
     * {@inheritDoc}
     */
    public function extends()
    {
        return [];
    }
}
