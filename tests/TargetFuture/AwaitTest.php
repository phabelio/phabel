<?php

namespace PhabelTest\TargetFuture;

use PHPUnit\Framework\TestCase;

use function Amp\call;
use function Amp\Promise\wait;

/**
 * Implement await
 */
class AwaitTest extends TestCase
{
    public function test()
    {
        [$contents, $true] = wait(call(function () {
            return await [
                // Executed in parallel, using https://amphp.org
                \Amp\File\read(__FILE__),
                \Amp\File\isDirectory(__DIR__),
            ];
        }));
        $this->assertEquals(file_get_contents(__FILE__), $contents);
        $this->assertTrue($true);
    }
}
