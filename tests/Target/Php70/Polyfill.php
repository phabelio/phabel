<?php

namespace PhabelTest\Target\Php70;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class NullCoalesceReplacerTest extends TestCase
{
    public function test()
    {
        $this->assertEquals('a', dirname("a/b/c", 2));
        $this->assertEquals('', substr('abc', 3));
        $this->assertEquals('5aa4f64242f6a45a068195438bd45e40405ed48b43958106', bin2hex(pack('gGeE', 123.321, 123.321, 123.321, 123.321)));
    }
}
