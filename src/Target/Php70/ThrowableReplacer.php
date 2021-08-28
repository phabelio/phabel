<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintReplacer;
use Phabel\Target\Php71\MultipleCatchReplacer;
use Phabel\PhpParser\Node;
use Phabel\PhpParser\Node\Expr\BinaryOp\BooleanOr;
use Phabel\PhpParser\Node\Expr\Instanceof_;
use Phabel\PhpParser\Node\Name;
use Phabel\PhpParser\Node\Name\FullyQualified;
use Phabel\PhpParser\Node\Param;
use Phabel\PhpParser\Node\Stmt\TryCatch;
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
    public function shouldRunFile($file)
    {
        if (!\is_string($file)) {
            if (!(\is_string($file) || \is_object($file) && \method_exists($file, '__toString') || (\is_bool($file) || \is_numeric($file)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($file) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $file = (string) $file;
        }
        $phabelReturn = !\str_ends_with($file, 'src/Target/Php70/ThrowableReplacer.php');
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Check if type string is \Throwable or Throwable.
     *
     * @param string $type Type string
     *
     * @return boolean
     */
    private static function isThrowable($type)
    {
        if (!\is_string($type)) {
            if (!(\is_string($type) || \is_object($type) && \method_exists($type, '__toString') || (\is_bool($type) || \is_numeric($type)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($type) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $type = (string) $type;
        }
        $phabelReturn = $type === \Throwable::class || $type === '\\Throwable';
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Check if is a throwable.
     *
     * @param mixed $obj
     * @param mixed $class
     * @return boolean
     */
    public static function isInstanceofThrowable($obj, $class)
    {
        if (\is_string($class) && self::isThrowable($class)) {
            $phabelReturn = $obj instanceof \Exception || $obj instanceof \Error;
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (bool) $phabelReturn;
            }
            return $phabelReturn;
        }
        $phabelReturn = $obj instanceof $class;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
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
    public function enterTryCatch(TryCatch $node)
    {
        foreach ($node->catches as $catch) {
            $alreadyHasError = \false;
            $next = \false;
            foreach ($catch->types as &$type) {
                if ($type instanceof FullyQualified && $type->getLast() === "Error") {
                    $alreadyHasError = \true;
                }
                if ($this->isThrowable($type->toString())) {
                    $next = \true;
                    $type = new FullyQualified('Exception');
                }
            }
            if ($next && !$alreadyHasError) {
                $catch->types[] = new FullyQualified('Error');
            }
        }
    }
    public static function withPrevious(array $config)
    {
        $phabelReturn = [TypeHintReplacer::class => ['type' => [\Throwable::class]], MultipleCatchReplacer::class => []];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
