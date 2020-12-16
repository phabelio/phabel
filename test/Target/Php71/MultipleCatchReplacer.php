<?php

namespace PhabelTest\Target\Php71;

use PHPUnit\Framework\TestCase;
use PhpParser\Node\Stmt\TryCatch;

/**
 * Replace compound catches.
 */
class MultipleCatchReplacer extends TestCase
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
        try {
            throw $input;
        } catch (\Exception|\Error|\RuntimeException $e) {
            $this->assertEquals($input, $e);
        }
    }
    public function exceptionProvider(): array
    {
        return [
            new \Exception,
            new \Error,
            new \RuntimeException,
        ];
    }
}
