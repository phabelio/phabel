<?php

namespace Phabel\PhpParser\Builder;

use Phabel\PhpParser\Builder;
use Phabel\PhpParser\BuilderHelpers;
use Phabel\PhpParser\Node;
use Phabel\PhpParser\Node\Stmt;
class Use_ implements Builder
{
    protected $name;
    protected $type;
    protected $alias = null;
    /**
     * Creates a name use (alias) builder.
     *
     * @param Node\Name|string $name Name of the entity (namespace, class, function, constant) to alias
     * @param int              $type One of the Stmt\Use_::TYPE_* constants
     */
    public function __construct($name, $type)
    {
        if (!\is_int($type)) {
            if (!(\is_bool($type) || \is_numeric($type))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($type) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $type = (int) $type;
            }
        }
        $this->name = BuilderHelpers::normalizeName($name);
        $this->type = $type;
    }
    /**
     * Sets alias for used name.
     *
     * @param string $alias Alias to use (last component of full name by default)
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function as($alias)
    {
        if (!\is_string($alias)) {
            if (!(\is_string($alias) || \is_object($alias) && \method_exists($alias, '__toString') || (\is_bool($alias) || \is_numeric($alias)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($alias) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($alias) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $alias = (string) $alias;
            }
        }
        $this->alias = $alias;
        return $this;
    }
    /**
     * Returns the built node.
     *
     * @return Stmt\Use_ The built node
     */
    public function getNode()
    {
        $phabelReturn = new Stmt\Use_([new Stmt\UseUse($this->name, $this->alias)], $this->type);
        if (!$phabelReturn instanceof Node) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
