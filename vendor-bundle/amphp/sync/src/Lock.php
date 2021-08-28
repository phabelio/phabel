<?php

namespace Phabel\Amp\Sync;

/**
 * A handle on an acquired lock from a synchronization object.
 *
 * This object is not thread-safe; after acquiring a lock from a mutex or
 * semaphore, the lock should reside in the same thread or process until it is
 * released.
 */
class Lock
{
    /** @var callable|null The function to be called on release or null if the lock has been released. */
    private $releaser;
    /** @var int */
    private $id;
    /**
     * Creates a new lock permit object.
     *
     * @param int $id The lock identifier.
     * @param callable(self): void $releaser A function to be called upon release.
     */
    public function __construct($id, callable $releaser)
    {
        if (!\is_int($id)) {
            if (!(\is_bool($id) || \is_numeric($id))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (int) $id;
            }
        }
        $this->id = $id;
        $this->releaser = $releaser;
    }
    /**
     * Checks if the lock has already been released.
     *
     * @return bool True if the lock has already been released, otherwise false.
     */
    public function isReleased()
    {
        $phabelReturn = !$this->releaser;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * @return int Lock identifier.
     */
    public function getId()
    {
        $phabelReturn = $this->id;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Releases the lock. No-op if the lock has already been released.
     */
    public function release()
    {
        if (!$this->releaser) {
            return;
        }
        // Invoke the releaser function given to us by the synchronization source
        // to release the lock.
        $releaser = $this->releaser;
        $this->releaser = null;
        $releaser($this);
    }
    /**
     * Releases the lock when there are no more references to it.
     */
    public function __destruct()
    {
        if (!$this->isReleased()) {
            $this->release();
        }
    }
}
