<?php

namespace PhabelTest\Target\Php70;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class AnonymousClassReplacer extends TestCase
{
    /**
     * Test anonymous classes
     */
    public function test()
    {
        $data = 'uwu';
        $data2 = (new class($data) {
            private string $data;
            public function __construct(string $data)
            {
                $this->data = $data;
            }
            public function getData(): string
            {
                return $this->data;
            }
        })->getData();

        $this->assertEquals($data, $data2);
    }
}
