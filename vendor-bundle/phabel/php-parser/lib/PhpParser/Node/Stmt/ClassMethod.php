<?php

namespace Phabel\PhpParser\Node\Stmt;

use Phabel\PhpParser\Node;
use Phabel\PhpParser\Node\FunctionLike;
class ClassMethod extends Node\Stmt implements FunctionLike
{
    /** @var int Flags */
    public $flags;
    /** @var bool Whether to return by reference */
    public $byRef;
    /** @var Node\Identifier Name */
    public $name;
    /** @var Node\Param[] Parameters */
    public $params;
    /** @var null|Node\Identifier|Node\Name|Node\NullableType|Node\UnionType Return type */
    public $returnType;
    /** @var Node\Stmt[]|null Statements */
    public $stmts;
    /** @var Node\AttributeGroup[] PHP attribute groups */
    public $attrGroups;
    private static $magicNames = ['__construct' => \true, '__destruct' => \true, '__call' => \true, '__callstatic' => \true, '__get' => \true, '__set' => \true, '__isset' => \true, '__unset' => \true, '__sleep' => \true, '__wakeup' => \true, '__tostring' => \true, '__set_state' => \true, '__clone' => \true, '__invoke' => \true, '__debuginfo' => \true];
    /**
     * Constructs a class method node.
     *
     * @param string|Node\Identifier $name Name
     * @param array $subNodes   Array of the following optional subnodes:
     *                          'flags       => MODIFIER_PUBLIC: Flags
     *                          'byRef'      => false          : Whether to return by reference
     *                          'params'     => array()        : Parameters
     *                          'returnType' => null           : Return type
     *                          'stmts'      => array()        : Statements
     *                          'attrGroups' => array()        : PHP attribute groups
     * @param array $attributes Additional attributes
     */
    public function __construct($name, array $subNodes = [], array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->flags = isset($subNodes['flags']) ? $subNodes['flags'] : (isset($subNodes['type']) ? $subNodes['type'] : 0);
        $this->byRef = isset($subNodes['byRef']) ? $subNodes['byRef'] : \false;
        $this->name = \is_string($name) ? new Node\Identifier($name) : $name;
        $this->params = isset($subNodes['params']) ? $subNodes['params'] : [];
        $returnType = isset($subNodes['returnType']) ? $subNodes['returnType'] : null;
        $this->returnType = \is_string($returnType) ? new Node\Identifier($returnType) : $returnType;
        $this->stmts = \array_key_exists('stmts', $subNodes) ? $subNodes['stmts'] : [];
        $this->attrGroups = isset($subNodes['attrGroups']) ? $subNodes['attrGroups'] : [];
    }
    public function getSubNodeNames()
    {
        $phabelReturn = ['attrGroups', 'flags', 'byRef', 'name', 'params', 'returnType', 'stmts'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function returnsByRef()
    {
        $phabelReturn = $this->byRef;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function getParams()
    {
        $phabelReturn = $this->params;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getReturnType()
    {
        return $this->returnType;
    }
    public function getStmts()
    {
        return $this->stmts;
    }
    public function getAttrGroups()
    {
        $phabelReturn = $this->attrGroups;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Whether the method is explicitly or implicitly public.
     *
     * @return bool
     */
    public function isPublic()
    {
        $phabelReturn = ($this->flags & Class_::MODIFIER_PUBLIC) !== 0 || ($this->flags & Class_::VISIBILITY_MODIFIER_MASK) === 0;
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
     * Whether the method is protected.
     *
     * @return bool
     */
    public function isProtected()
    {
        $phabelReturn = (bool) ($this->flags & Class_::MODIFIER_PROTECTED);
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
     * Whether the method is private.
     *
     * @return bool
     */
    public function isPrivate()
    {
        $phabelReturn = (bool) ($this->flags & Class_::MODIFIER_PRIVATE);
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
     * Whether the method is abstract.
     *
     * @return bool
     */
    public function isAbstract()
    {
        $phabelReturn = (bool) ($this->flags & Class_::MODIFIER_ABSTRACT);
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
     * Whether the method is final.
     *
     * @return bool
     */
    public function isFinal()
    {
        $phabelReturn = (bool) ($this->flags & Class_::MODIFIER_FINAL);
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
     * Whether the method is static.
     *
     * @return bool
     */
    public function isStatic()
    {
        $phabelReturn = (bool) ($this->flags & Class_::MODIFIER_STATIC);
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
     * Whether the method is magic.
     *
     * @return bool
     */
    public function isMagic()
    {
        $phabelReturn = isset(self::$magicNames[$this->name->toLowerString()]);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function getType()
    {
        $phabelReturn = 'Stmt_ClassMethod';
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
