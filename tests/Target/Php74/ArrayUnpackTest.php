<?php

namespace PhabelTest\Target\Php74;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ArrayUnpackTest extends TestCase
{
    public function test()
    {
        $b = ['bb'];
        $this->assertEquals(['a' => 'aa', 'bb', 'c' => 'cc'], ['a' => 'aa', ...$b, 'c' => 'cc']);
        $this->assertEquals(['a' => 'aa', 'bb', 'c' => 'cc'], ['a' => 'aa', ...(fn () => $b)(), 'c' => 'cc']);
    }
}
