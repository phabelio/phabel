<?php

namespace PhabelTest\Target\Php70;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestCase\TypeHintStripper;
use Phabel\Target\Php71\MultipleCatchReplacer;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\TryCatch;

/**
 * Replace \Throwable usages.
 */
class ThrowableReplacer extends TestCase
{
    /**
     * Throwable test
     *
     * @param \Throwable $input Exception
     *
     * @dataProvider exceptionProvider
     */
    public function test(\Throwable $input)
    {
        $this->assertTrue($input instanceof \Throwable);

        try {
            throw $input;
        } catch (\Throwable $e) {
            $this->assertEquals($input, $e);
        }
    }
    public function exceptionProvider(): array
    {
        return [
            [new \Exception],
            [new \Error],
            [new \RuntimeException],
        ];
    }
}
