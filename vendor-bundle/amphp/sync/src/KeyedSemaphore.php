<?php

namespace PhabelVendor\Amp\Sync;

use PhabelVendor\Amp\Promise;
/**
 * A non-blocking counting semaphore based on keys.
 *
 * Objects that implement this interface should guarantee that all operations are atomic. Implementations do not have to
 * guarantee that acquiring a lock is first-come, first serve.
 */
interface KeyedSemaphore
{
}
