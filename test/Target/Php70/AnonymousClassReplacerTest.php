<?php

namespace PhabelTest\Target\Php70;

use PHPUnit\Framework\TestCase;

if (true) {
    $anonClass = (new class($data) {
        private string $data;
        public function __construct(string $data)
        {
            $this->data = $data;
        }
        public function getData(): string
        {
            return $this->data;
        }
    });
}

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class AnonymousClassReplacerTest extends TestCase
{
    /**
     * Test anonymous classes.
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

        $data3 = (fn () => ((new class($data) {
            private string $data;
            public function __construct(string $data)
            {
                $this->data = $data;
            }
            public function getData(): string
            {
                return $this->data;
            }
        })->getData()))();

        $this->assertEquals($data, $data3);

        $this->assertEquals($data, $GLOBALS['anonClass']->getData());
    }
}
