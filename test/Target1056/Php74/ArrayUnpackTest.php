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
        $this->assertEquals(['a' => 'aa', 'bb', 'c' => 'cc'], \array_merge(array('a' => 'aa'), $b, array('c' => 'cc')));
        $phabel_a3b4da51dd7b4a98 = function () {
            return $b;
        };
        $this->assertEquals(['a' => 'aa', 'bb', 'c' => 'cc'], \array_merge(array('a' => 'aa'), $phabel_a3b4da51dd7b4a98(), array('c' => 'cc')));
    }
}