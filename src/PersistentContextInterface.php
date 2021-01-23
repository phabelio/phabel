<?php

namespace Phabel;

/**
 * Persistent context interface.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
interface PersistentContextInterface
{
    /**
     * Merge context with another, previously generated context.
     *
     * @param static $other
     * @return void
     */
    public function merge($other): void;
    /**
     * Finished traversing all files, execute final shutdown operations.
     *
     * @return void
     */
    public function finish(): void;
}
