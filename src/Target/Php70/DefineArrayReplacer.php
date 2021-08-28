<?php

namespace Phabel\Target\Php70;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\RootNode;
use PhpParser\Node;
use PhpParser\Node\Expr\Eval_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Const_;

/**
 * Converts define() arrays into const arrays.
 */
class DefineArrayReplacer extends Plugin
{
    /**
     * Convert define() arrays into const arrays.
     *
     * @param FuncCall $node Node
     *
     * @return Const_|Eval_|null
     */
    public function enter(FuncCall $node, Context $context)
    {
        if (!$node->name instanceof Name || $node->name->toString() != 'define') {
            return null;
        }
        $nameNode = $node->args[0]->value;
        $valueNode = $node->args[1]->value;
        if (!$valueNode instanceof Node\Expr\Array_) {
            return null;
        }
        if (!$context->parents->top() instanceof RootNode) {
            return self::callPoly('defineMe', ...$node->args);
        }
        $constNode = new Node\Const_($nameNode->value, $valueNode);
        return new Node\Stmt\Const_([$constNode]);
    }
    /**
     * Define a constant.
     *
     * @param string $name
     * @param array $value
     * @return void
     */
    public static function defineMe($name, array $value)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $name = (string) $name;
        }
        $name = \preg_replace("/[^A-Za-z0-9_]/", '', $name);
        $value = \var_export($value, true);
        eval("const {$name} = {$value};");
    }
}
