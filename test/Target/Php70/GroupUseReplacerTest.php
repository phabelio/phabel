<?php

namespace PhabelTest\Target\Php70;

use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse as uwu;
use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class GroupUseReplacerTest extends TestCase
{
    public function test()
    {
        $this->assertTrue(\class_exists(uwu::class));
        $this->assertTrue(\class_exists(GroupUse::class));
        $this->assertTrue(\class_exists(Use_::class));
    }
}
