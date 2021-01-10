<?php

namespace PhabelTest\Target\Php73;

use PHPUnit\Framework\TestCase;

/**
 * Polyfills list assignment by reference.
 */
class ListReferenceTest extends TestCase
{
    public function test()
    {
        $kK = 'k';
        $input = [
            ['uwu', [['uwuVal', 'a', ['k' => 'pony']]]], 
            ['owo', [['owoVal', 'test', ['k' => 'pony2']]]]
        ];
        $inputOrig = $input;
        foreach ($input as $i => [$k, [[&$val1, &$s, [$kK => &$val2]]]]) {
            $s = "m$s";
            $val1 = "m$val1";
            $val2 = "m$val2";
        }
        unset($k, $val1, $s, $val2);
        foreach ($input as $i => [$k, [[$val1, $s, [$kK => $val2]]]]) {
            $this->assertEquals($inputOrig[$i][0], $k);
            $this->assertEquals("m".$inputOrig[$i][1][0][0], $val1);
            $this->assertEquals("m".$inputOrig[$i][1][0][1], $s);
            $this->assertEquals("m".$inputOrig[$i][1][0][2]['k'], $val2);
        }

        foreach ($input as $i => &$v) {
            [$k, [[&$val1, &$s, [$kK => &$val2]]]] = $v;
            $s = "p$s";
            $val1 = "p$val1";
            $val2 = "p$val2";
        }
        unset($k, $val1, $s, $val2, $v);

        foreach ($input as $i => $v) {
            [$k, [[$val1,$s, [$kK => $val2]]]] = $v;
            $this->assertEquals($inputOrig[$i][0], $k);
            $this->assertEquals("pm".$inputOrig[$i][1][0][0], $val1);
            $this->assertEquals("pm".$inputOrig[$i][1][0][1], $s);
            $this->assertEquals("pm".$inputOrig[$i][1][0][2]['k'], $val2);
        }
    }
}
