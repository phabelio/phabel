<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use Phabel\Target\Php74\ArrowClosure;
use Phabel\Traverser;
use PhpParser\Builder\FunctionLike;

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
        $this->traverser = Traverser::fromPlugin(new ReGeneratorInternal());
    }
    public function enter(FunctionLike $function)
    {
        if (!$function->getAttribute(self::SHOULD_ATTRIBUTE, false)) {
            return;
        }
        $this->traverser->traverseAst($function);
    }
    public static function previous(array $config)
    {
        $phabelReturn = [ArrowClosure::class];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
