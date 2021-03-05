<?php

namespace Phabel\Target\Php71;

use Phabel\Plugin;
use PhpParser\Node\Stmt\TryCatch;

/**
 * Replace compound catches.
 */
class MultipleCatchReplacer extends Plugin
{
    /**
     * Replace compound catches.
     *
     * Do this while leaving to avoid re-iterating uselessly on duplicated code.
     *
     * @param TryCatch $node Catch stmt
     *
     * @return void
     */
    public function leave(TryCatch $node): void
    {
        $catches = [];
        foreach ($node->catches as $catch) {
            if (\count($catch->types) === 1) {
                $catches[] = $catch;
            } else {
                foreach ($catch->types as $type) {
                    $ncatch = clone $catch;
                    $ncatch->types = [$type];
                    $catches[] = $ncatch;
                }
            }
        }
        $node->catches = $catches;
    }
}
