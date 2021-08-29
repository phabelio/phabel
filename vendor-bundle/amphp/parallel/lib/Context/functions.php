<?php

namespace Phabel\Amp\Parallel\Context;

use Phabel\Amp\Loop;
use Phabel\Amp\Promise;
const LOOP_FACTORY_IDENTIFIER = ContextFactory::class;
/**
 * @param string|string[] $script Path to PHP script or array with first element as path and following elements options
 *     to the PHP script (e.g.: ['bin/worker', 'Option1Value', 'Option2Value'].
 *
 * @return Context
 */
function create($script) : Context
{
    return factory()->create($script);
}
/**
 * Creates and starts a process based on installed extensions (a thread if ext-parallel is installed, otherwise a child
 * process).
 *
 * @param string|string[] $script Path to PHP script or array with first element as path and following elements options
 *     to the PHP script (e.g.: ['bin/worker', 'Option1Value', 'Option2Value'].
 *
 * @return Promise<Context>
 */
function run($script) : Promise
{
    return factory()->run($script);
}
/**
 * Gets or sets the global context factory.
 *
 * @param ContextFactory|null $factory
 *
 * @return ContextFactory
 */
function factory($factory = null) : ContextFactory
{
    if (!($factory instanceof ContextFactory || \is_null($factory))) {
        throw new \TypeError(__METHOD__ . '(): Argument #1 ($factory) must be of type ?ContextFactory, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($factory) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    if ($factory === null) {
        $factory = Loop::getState(LOOP_FACTORY_IDENTIFIER);
        if ($factory) {
            return $factory;
        }
        $factory = new DefaultContextFactory();
    }
    Loop::setState(LOOP_FACTORY_IDENTIFIER, $factory);
    return $factory;
}
