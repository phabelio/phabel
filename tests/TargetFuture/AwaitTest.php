<?php

namespace PhabelTest\TargetFuture;

use Amp\Success;
use PHPUnit\Framework\TestCase;

use function Amp\call;
use function Amp\delay;
use function Amp\Promise\wait;
// Fake function, doesn't exist and NOT required for await, just some unit testing
use function Amp\Promise\await as something;

/**
 * Implement await
 */
class AwaitTest extends TestCase
{
    public function test()
    {
        [$contents, $true] = wait(call(function () {
            await new Success();
            return await [
                // Executed in parallel, using https://amphp.org
                \Amp\File\read(__FILE__),
                \Amp\File\isDirectory(__DIR__),
            ];
        }));
        if (0) {
            function await(string $in): string {
                return $in;
            }
            $this->assertEquals("This is a function, ignore this", await("This is a function, ignore this"));
        }
        $this->assertEquals(file_get_contents(__FILE__), $contents);
        $this->assertTrue($true);
    }
}
