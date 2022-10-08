<?php

namespace PhabelTest\Target\Php80;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ConstructorPromotionTest extends TestCase
{
    public function test()
    {
        $this->assertEquals('test', (new class('test') {
            public function __construct(public string $test)
            {
                
            }
        })->test);
    }
}
