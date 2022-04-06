<?php

namespace PhabelVendor\Amp\Sync;

use PhabelVendor\Amp\Promise;
/**
 * A non-blocking synchronization primitive that can be used for mutual exclusion across contexts based on keys.
 *
 * Objects that implement this interface should guarantee that all operations are atomic. Implementations do not have to
 * guarantee that acquiring a lock is first-come, first serve.
 */
interface KeyedMutex extends KeyedSemaphore
{
}
