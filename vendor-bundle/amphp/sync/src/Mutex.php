<?php

namespace Phabel\Amp\Sync;

use Phabel\Amp\Promise;
/**
 * A non-blocking synchronization primitive that can be used for mutual exclusion across contexts.
 *
 * Objects that implement this interface should guarantee that all operations are atomic. Implementations do not have to
 * guarantee that acquiring a lock is first-come, first serve.
 */
interface Mutex extends Semaphore
{
}
