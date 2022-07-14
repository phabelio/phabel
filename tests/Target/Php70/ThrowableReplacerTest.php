<?php

namespace PhabelTest\Target\Php70;

use PhpParser\Node\Param;
use PHPUnit\Framework\TestCase;

/**
 * Replace \Throwable usages.
 */
class ThrowableReplacerTest extends TestCase
{
    /**
     * Throwable test.
     *
     * @param \Throwable $input Exception
     *
     * @dataProvider exceptionProvider
     */
    public function test(\Throwable $input)
    {
        $this->assertTrue($input instanceof \Throwable);
        $this->assertTrue($input instanceof $input);

        $c1 = '\\Throwable';
        $c2 = \Throwable::class;
        $this->assertTrue($input instanceof $c1);
        $this->assertTrue($input instanceof $c2);
        

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
