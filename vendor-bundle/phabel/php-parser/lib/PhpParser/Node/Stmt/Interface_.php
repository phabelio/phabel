<?php

namespace Phabel\PhpParser\Node\Stmt;

use Phabel\PhpParser\Node;
class Interface_ extends ClassLike
{
    /** @var Node\Name[] Extended interfaces */
    public $extends;
    /**
     * Constructs a class node.
     *
     * @param string|Node\Identifier $name Name
     * @param array  $subNodes   Array of the following optional subnodes:
     *                           'extends'    => array(): Name of extended interfaces
     *                           'stmts'      => array(): Statements
     *                           'attrGroups' => array(): PHP attribute groups
     * @param array  $attributes Additional attributes
     */
    public function __construct($name, array $subNodes = [], array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->name = \is_string($name) ? new Node\Identifier($name) : $name;
        $this->extends = isset($subNodes['extends']) ? $subNodes['extends'] : [];
        $this->stmts = isset($subNodes['stmts']) ? $subNodes['stmts'] : [];
        $this->attrGroups = isset($subNodes['attrGroups']) ? $subNodes['attrGroups'] : [];
    }
    public function getSubNodeNames()
    {
        $phabelReturn = ['attrGroups', 'name', 'extends', 'stmts'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getType()
    {
        $phabelReturn = 'Stmt_Interface';
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
