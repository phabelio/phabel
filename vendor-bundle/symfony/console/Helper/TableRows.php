<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Helper;

/**
 * @internal
 */
class TableRows implements \IteratorAggregate
{
    private $generator;
    public function __construct(callable $generator)
    {
        $this->generator = $generator;
    }
    public function getIterator()
    {
        $g = $this->generator;
        $phabelReturn = $g();
        if (!$phabelReturn instanceof \Traversable) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Traversable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
