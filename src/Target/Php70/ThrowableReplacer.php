<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintStripper;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\TryCatch;

/**
 * Replace \Throwable usages.
 */
class ThrowableReplacer extends Plugin
{
    /**
     * Check if type string is \Throwable or Throwable.
     *
     * @param string $type Type string
     *
     * @return boolean
     */
    private function isThrowable(string $type): bool
    {
        // Make this less ugly when we implement a namespace context
        return $type === \Throwable::class || $type === 'Throwable';
    }
    /**
     * Split instance of \Throwable.
     *
     * @param Instanceof_ $node
     *
     * @return ?BooleanOr
     */
    public function enterInstanceOf(Instanceof_ $node): ?BooleanOr
    {
        if (!$this->isThrowable($node->class)) {
            return null;
        }
        return new BooleanOr(
            new Instanceof_($node->expr, new FullyQualified('Exception')),
            new Instanceof_($node->expr, new FullyQualified('Error'))
        );
    }
    /**
     * Substitute try-catch.
     *
     * @param TryCatch $node TryCatch node
     *
     * @return void
     */
    public function enterTryCatch(TryCatch $node): void
    {
        foreach ($node->catches as $catch) {
            $alreadyHasError = false;
            $needs = false;
            foreach ($catch->types as &$type) {
                if ($type instanceof FullyQualified &&
                    $type->getLast() === "Error") {
                    $alreadyHasError = true;
                }
                if ($this->isThrowable($type)) {
                    $needs = true;
                    $type = new FullyQualified('Exception');
                }
            }
            if ($needs && !$alreadyHasError) {
                $catch->types[] = new FullyQualified('Error');
            }
        }
    }

    /**
     * Other transforms.
     *
     * @return array
     */
    public function extends(): array
    {
        return [
            TypeHintStripper::class => [
                'type' => [
                    \Throwable::class,
                    'Throwable'
                ]
            ]
        ];
    }
}
