<?php

namespace PhabelTest\Target\Php71;

use PHPUnit\Framework\TestCase;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Stmt\Foreach_;

/**
 * Replaces [] array list syntax.
 */
class ArrayList extends TestCase
{
    public function testForeach()
    {
        $input = [['uwu', ['uwuVal', 'a', ['k' => 'pony']]], ['owo', ['owoVal', 'test', ['k' => 'pony2']]]];
        foreach ($input as $i => [$k, [$val1, ['k' => $val2]]]) {
            $this->assertEquals($input[$i][0], $k);
            $this->assertEquals($input[$i][1][0], $val1);
            $this->assertEquals($input[$i][1][1]['k'], $val2);
        }
        foreach ($input as $i => $v) {
            [$k, [$val1, ['k' => $val2]]] = $v;
            $this->assertEquals($input[$i][0], $k);
            $this->assertEquals($input[$i][1][0], $val1);
            $this->assertEquals($input[$i][1][1]['k'], $val2);
        }
    }
}
