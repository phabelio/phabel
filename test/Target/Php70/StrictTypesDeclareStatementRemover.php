<?php
declare(strict_types=1);

namespace PhabelTest\Target\Php70;

use PHPUnit\Framework\TestCase;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\DeclareDeclare;
use PhpParser\Node\Stmt\Nop;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class StrictTypesDeclareStatementRemover extends TestCase
{
    public function test() {
        $this->assertTrue(true);
    }
}
