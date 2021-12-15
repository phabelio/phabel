<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintReplacer;
use Phabel\Target\Php71\MultipleCatchReplacer;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\TryCatch;

/**
 * Replace \Throwable usages.
 */
class ThrowableReplacer extends Plugin
{
    /**
     * Check if plugin should run.
     *
     * @param string $file File name
     *
     * @return boolean
     */
    public function shouldRunFile(string $file): bool
    {
        return !\str_ends_with($file, 'src/Target/Php70/ThrowableReplacer.php');
    }
    /**
     * Check if type string is \Throwable or Throwable.
     *
     * @param string $type Type string
     *
     * @return boolean
     */
    private static function isThrowable(string $type): bool
    {
        return $type === \Throwable::class || $type === '\\Throwable';
    }
    /**
     * Check if is a throwable.
     *
     * @param mixed $obj
     * @param mixed $class
     * @return boolean
     */
    public static function isInstanceofThrowable($obj, $class): bool
    {
        if (\is_string($class) && self::isThrowable($class)) {
            return $obj instanceof \Exception || $obj instanceof \Error;
        }
        return $obj instanceof $class;
    }
    /**
     * Split instance of \Throwable.
     *
     * @param Instanceof_ $node
     *
     * @return ?Node
     */
    public function enterInstanceOf(Instanceof_ $node)
    {
        if ($node->class instanceof Name) {
            if (!$this->isThrowable($node->class->toString())) {
                return null;
            }
            return new BooleanOr(new Instanceof_($node->expr, new FullyQualified('Exception')), new Instanceof_($node->expr, new FullyQualified('Error')));
        }
        return self::callPoly('isInstanceofThrowable', $node->expr, $node->class);
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
            $next = false;
            foreach ($catch->types as &$type) {
                if ($type instanceof FullyQualified && $type->getLast() === "Error") {
                    $alreadyHasError = true;
                }
                if ($this->isThrowable($type->toString())) {
                    $next = true;
                    $type = new FullyQualified('Exception');
                }
            }
            if ($next && !$alreadyHasError) {
                $catch->types[] = new FullyQualified('Error');
            }
        }
    }
    /**
     *
     */
    public static function withPrevious(array $config): array
    {
        return [TypeHintReplacer::class => ['type' => [\Throwable::class]], MultipleCatchReplacer::class => []];
    }
}
