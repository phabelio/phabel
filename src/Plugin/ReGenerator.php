<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use Phabel\Target\Php74\ArrowClosure;
use Phabel\Traverser;
use Phabel\PhpParser\Builder\FunctionLike;
/**
 * Regenerator transformer.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ReGenerator extends Plugin
{
    const SHOULD_ATTRIBUTE = 'shouldRegenerate';
    /**
     * Custom traverser.
     */
    private $traverser;
    public function __construct()
    {
        $this->traverser = Traverser::fromPlugin(new \Phabel\Plugin\ReGeneratorInternal());
    }
    public function enter(FunctionLike $function)
    {
        if (!$function->getAttribute(self::SHOULD_ATTRIBUTE, \false)) {
            return;
        }
        $this->traverser->traverseAst($function);
    }
    public static function previous(array $config) : array
    {
        return [ArrowClosure::class];
    }
}
