<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use Phabel\Traverser;
use Phabel\PhpParser\Node;
use Phabel\PhpParser\Node\Expr\ClosureUse;
use Phabel\PhpParser\Node\Expr\Variable;
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class VariableFinder extends Plugin
{
    /**
     * Singleton.
     */
    private static $singleton;
    /**
     * Traverser.
     */
    private static $singletonTraverser;
    /**
     * Get found closure uses.
     *
     * @param Node $ast Node tree
     * @param bool $byRef Whether to link by ref found variables
     *
     * @return array<string, ClosureUse>
     */
    public static function find(Node $ast, $byRef = \false)
    {
        if (!\is_bool($byRef)) {
            if (!(\is_bool($byRef) || \is_numeric($byRef) || \is_string($byRef))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($byRef) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($byRef) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $byRef = (bool) $byRef;
        }
        if (!isset(self::$singleton)) {
            self::$singleton = new self();
            self::$singletonTraverser = Traverser::fromPlugin(self::$singleton);
        }
        self::$singleton->setConfig('byRef', $byRef);
        self::$singletonTraverser->traverseAst($ast, null, \false);
        $phabelReturn = self::$singleton->getFound();
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Constructor.
     */
    private function __construct()
    {
    }
    /**
     * Found array.
     *
     * @var array<string, ClosureUse>
     */
    private $found = [];
    /**
     * Enter variable.
     *
     * @param Variable $var
     * @return void
     */
    public function enter(Variable $var)
    {
        if (\is_string($var->name) && $var->name !== 'this') {
            $this->found[$var->name] = new ClosureUse($var, $this->getConfig('byRef', \false));
        }
    }
    /**
     * Get found closure uses.
     *
     * @return array<string, ClosureUse>
     */
    private function getFound()
    {
        $found = $this->found;
        $this->found = [];
        $phabelReturn = $found;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
