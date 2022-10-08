<?php

namespace PhabelTest\Target\Php80;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class NullSafeTransformerTest extends TestCase
{
    public function test()
    {
        $this->assertTrue((new class { public $a = true; })?->a);
        $this->assertTrue((new class { public function a() { return true; }})?->a());
        $a = fn () => null;
        $this->assertNull($a()?->a);
        $this->assertNull($a()?->a());
    }
}
