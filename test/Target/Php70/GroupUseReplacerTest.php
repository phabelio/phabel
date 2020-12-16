<?php

namespace PhabelTest\Target\Php70;

use PHPUnit\Framework\TestCase;
use PhpParser\Node\Stmt\{GroupUse, Use_, UseUse as uwu};

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class GroupUseReplacer extends TestCase
{
    public function test()
    {
        $this->assertTrue(class_exists(uwu::class));
        $this->assertTrue(class_exists(GroupUse::class));
        $this->assertTrue(class_exists(Use_::class));
    }
}
