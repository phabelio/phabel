<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use Phabel\Traverser;
use PhpParser\Builder\FunctionLike;

class ReGenerator extends Plugin
{
    const SHOULD_ATTRIBUTE = 'shouldRegenerate';

    /**
     * Custom traverser.
     */
    private Traverser $traverser;
    public function __construct()
    {
        $this->traverser = Traverser::fromPlugin(new ReGeneratorInternal);
    }
    public function enter(FunctionLike $function)
    {
        if (!$function->getAttribute(self::SHOULD_ATTRIBUTE, false)) {
            return;
        }
        $this->traverser->traverseAst($function);
    }
    public function runAfter(): array
    {
        return [ArrowClosure::class];
    }
}
