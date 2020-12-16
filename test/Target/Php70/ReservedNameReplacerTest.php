<?php

namespace PhabelTest\Target\Php70;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ReservedNameReplacerTest extends TestCase
{
    public function continue(string $data): string
    {
        return $data;
    }
    public function test()
    {
        $this->assertEquals('test', $this->continue('test'));
    }
}
