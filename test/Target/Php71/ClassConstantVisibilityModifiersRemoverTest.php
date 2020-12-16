<?php

namespace PhabelTest\Target\Php71;

use PHPUnit\Framework\TestCase;

/**
 * Removes the class constant visibility modifiers (PHP 7.1).
 */
class ClassConstantVisibilityModifiersRemoverTest extends TestCase
{
    private const A = 'owo';
    protected const B = 'uwu';
    public const C = 'pony';
    public function test()
    {
        $this->assertEquals(self::A, 'owo');
        $this->assertEquals(self::B, 'uwu');
        $this->assertEquals(self::C, 'pony');

        $class = new class extends ClassConstantVisibilityModifiersRemoverTest {
            private const AA = 'owo1';
            protected const BB = 'uwu1';
            public const CC = 'pony1';
            public function return(): array
            {
                return [
                    'AA' => self::AA,
                    'BB' => self::BB,
                    'CC' => self::CC,
                    'B' => parent::B,
                    'C' => parent::C,
                ];
            }
        };

        $ret = $class->return();

        $this->assertEquals($ret['AA'], 'owo1');
        $this->assertEquals($ret['BB'], 'uwu1');
        $this->assertEquals($ret['CC'], 'pony1');
        $this->assertEquals($ret['B'], 'uwu');
        $this->assertEquals($ret['C'], 'pony');
    }
}
