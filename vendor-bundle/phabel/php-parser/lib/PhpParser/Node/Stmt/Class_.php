<?php

namespace Phabel\PhpParser\Node\Stmt;

use Phabel\PhpParser\Error;
use Phabel\PhpParser\Node;
class Class_ extends ClassLike
{
    const MODIFIER_PUBLIC = 1;
    const MODIFIER_PROTECTED = 2;
    const MODIFIER_PRIVATE = 4;
    const MODIFIER_STATIC = 8;
    const MODIFIER_ABSTRACT = 16;
    const MODIFIER_FINAL = 32;
    const VISIBILITY_MODIFIER_MASK = 7;
    // 1 | 2 | 4
    /** @var int Type */
    public $flags;
    /** @var null|Node\Name Name of extended class */
    public $extends;
    /** @var Node\Name[] Names of implemented interfaces */
    public $implements;
    /**
     * Constructs a class node.
     *
     * @param string|Node\Identifier|null $name Name
     * @param array       $subNodes   Array of the following optional subnodes:
     *                                'flags'       => 0      : Flags
     *                                'extends'     => null   : Name of extended class
     *                                'implements'  => array(): Names of implemented interfaces
     *                                'stmts'       => array(): Statements
     *                                '$attrGroups' => array(): PHP attribute groups
     * @param array       $attributes Additional attributes
     */
    public function __construct($name, array $subNodes = [], array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->flags = isset($subNodes['flags']) ? $subNodes['flags'] : (isset($subNodes['type']) ? $subNodes['type'] : 0);
        $this->name = \is_string($name) ? new Node\Identifier($name) : $name;
        $this->extends = isset($subNodes['extends']) ? $subNodes['extends'] : null;
        $this->implements = isset($subNodes['implements']) ? $subNodes['implements'] : [];
        $this->stmts = isset($subNodes['stmts']) ? $subNodes['stmts'] : [];
        $this->attrGroups = isset($subNodes['attrGroups']) ? $subNodes['attrGroups'] : [];
    }
    public function getSubNodeNames()
    {
        $phabelReturn = ['attrGroups', 'flags', 'name', 'extends', 'implements', 'stmts'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Whether the class is explicitly abstract.
     *
     * @return bool
     */
    public function isAbstract()
    {
        $phabelReturn = (bool) ($this->flags & self::MODIFIER_ABSTRACT);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Whether the class is final.
     *
     * @return bool
     */
    public function isFinal()
    {
        $phabelReturn = (bool) ($this->flags & self::MODIFIER_FINAL);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Whether the class is anonymous.
     *
     * @return bool
     */
    public function isAnonymous()
    {
        $phabelReturn = null === $this->name;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * @internal
     */
    public static function verifyModifier($a, $b)
    {
        if ($a & self::VISIBILITY_MODIFIER_MASK && $b & self::VISIBILITY_MODIFIER_MASK) {
            throw new Error('Multiple access type modifiers are not allowed');
        }
        if ($a & self::MODIFIER_ABSTRACT && $b & self::MODIFIER_ABSTRACT) {
            throw new Error('Multiple abstract modifiers are not allowed');
        }
        if ($a & self::MODIFIER_STATIC && $b & self::MODIFIER_STATIC) {
            throw new Error('Multiple static modifiers are not allowed');
        }
        if ($a & self::MODIFIER_FINAL && $b & self::MODIFIER_FINAL) {
            throw new Error('Multiple final modifiers are not allowed');
        }
        if ($a & 48 && $b & 48) {
            throw new Error('Cannot use the final modifier on an abstract class member');
        }
    }
    public function getType()
    {
        $phabelReturn = 'Stmt_Class';
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
