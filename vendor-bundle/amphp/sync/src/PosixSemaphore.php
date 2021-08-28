<?php

namespace Phabel\Amp\Sync;

use Phabel\Amp\Coroutine;
use Phabel\Amp\Delayed;
use Phabel\Amp\Promise;
/**
 * A non-blocking, inter-process POSIX semaphore.
 *
 * Uses a POSIX message queue to store a queue of permits in a lock-free data structure. This semaphore implementation
 * is preferred over other implementations when available, as it provides the best performance.
 *
 * Not compatible with Windows.
 */
class PosixSemaphore implements Semaphore
{
    const LATENCY_TIMEOUT = 10;
    /** @var string */
    private $id;
    /** @var int The semaphore key. */
    private $key;
    /** @var int PID of the process that created the semaphore. */
    private $initializer = 0;
    /** @var resource A message queue of available locks. */
    private $queue;
    /**
     * Creates a new semaphore with a given ID and number of locks.
     *
     * @param string $id The unique name for the new semaphore.
     * @param int    $maxLocks The maximum number of locks that can be acquired from the semaphore.
     * @param int    $permissions Permissions to access the semaphore. Use file permission format specified as 0xxx.
     *
     * @return PosixSemaphore
     * @throws SyncException If the semaphore could not be created due to an internal error.
     */
    public static function create($id, $maxLocks, $permissions = 0600)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        if (!\is_int($maxLocks)) {
            if (!(\is_bool($maxLocks) || \is_numeric($maxLocks))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($maxLocks) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($maxLocks) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $maxLocks = (int) $maxLocks;
            }
        }
        if (!\is_int($permissions)) {
            if (!(\is_bool($permissions) || \is_numeric($permissions))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($permissions) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($permissions) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $permissions = (int) $permissions;
            }
        }
        if ($maxLocks < 1) {
            throw new \Error('Number of locks must be greater than 0');
        }
        $semaphore = new self($id);
        $semaphore->init($maxLocks, $permissions);
        $phabelReturn = $semaphore;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param string $id The unique name of the semaphore to use.
     *
     * @return \Amp\Sync\PosixSemaphore
     */
    public static function use_($id)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        $semaphore = new self($id);
        $semaphore->open();
        $phabelReturn = $semaphore;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param string $id
     *
     * @throws \Error If the sysvmsg extension is not loaded.
     */
    private function __construct($id)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        if (!\extension_loaded("sysvmsg")) {
            throw new \Error(__CLASS__ . " requires the sysvmsg extension.");
        }
        $this->id = $id;
        $this->key = self::makeKey($this->id);
    }
    /**
     * Private method to prevent cloning.
     */
    private function __clone()
    {
    }
    /**
     * Private to prevent serialization.
     */
    private function __sleep()
    {
    }
    public function getId()
    {
        $phabelReturn = $this->id;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    private function open()
    {
        if (!\msg_queue_exists($this->key)) {
            throw new SyncException('No semaphore with that ID found');
        }
        $this->queue = \msg_get_queue($this->key);
        if (!$this->queue) {
            throw new SyncException('Failed to open the semaphore.');
        }
    }
    /**
     * @param int $maxLocks    The maximum number of locks that can be acquired from the semaphore.
     * @param int $permissions Permissions to access the semaphore.
     *
     * @throws SyncException If the semaphore could not be created due to an internal error.
     */
    private function init($maxLocks, $permissions)
    {
        if (!\is_int($maxLocks)) {
            if (!(\is_bool($maxLocks) || \is_numeric($maxLocks))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($maxLocks) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($maxLocks) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $maxLocks = (int) $maxLocks;
            }
        }
        if (!\is_int($permissions)) {
            if (!(\is_bool($permissions) || \is_numeric($permissions))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($permissions) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($permissions) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $permissions = (int) $permissions;
            }
        }
        if (\msg_queue_exists($this->key)) {
            throw new SyncException('A semaphore with that ID already exists');
        }
        $this->queue = \msg_get_queue($this->key, $permissions);
        if (!$this->queue) {
            throw new SyncException('Failed to create the semaphore.');
        }
        $this->initializer = \getmypid();
        // Fill the semaphore with locks.
        while (--$maxLocks >= 0) {
            $this->release($maxLocks);
        }
    }
    /**
     * Gets the access permissions of the semaphore.
     *
     * @return int A permissions mode.
     */
    public function getPermissions()
    {
        $stat = \msg_stat_queue($this->queue);
        $phabelReturn = $stat['msg_perm.mode'];
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
     * Sets the access permissions of the semaphore.
     *
     * The current user must have access to the semaphore in order to change the permissions.
     *
     * @param int $mode A permissions mode to set.
     *
     * @throws SyncException If the operation failed.
     */
    public function setPermissions($mode)
    {
        if (!\is_int($mode)) {
            if (!(\is_bool($mode) || \is_numeric($mode))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($mode) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($mode) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $mode = (int) $mode;
            }
        }
        if (!\msg_set_queue($this->queue, ['msg_perm.mode' => $mode])) {
            throw new SyncException('Failed to change the semaphore permissions.');
        }
    }
    public function acquire()
    {
        $phabelReturn = new Coroutine($this->doAcquire());
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    private function doAcquire()
    {
        do {
            // Attempt to acquire a lock from the semaphore.
            if (@\msg_receive($this->queue, 0, $type, 1, $id, \false, \MSG_IPC_NOWAIT, $errno)) {
                // A free lock was found, so resolve with a lock object that can
                // be used to release the lock.
                return new Lock(\unpack("C", $id)[1], function (Lock $lock) {
                    $this->release($lock->getId());
                });
            }
            // Check for unusual errors.
            if ($errno !== \MSG_ENOMSG) {
                throw new SyncException(\sprintf('Failed to acquire a lock; errno: %d', $errno));
            }
        } while ((yield new Delayed(self::LATENCY_TIMEOUT, \true)));
    }
    /**
     * Removes the semaphore if it still exists.
     *
     * @throws SyncException If the operation failed.
     */
    public function __destruct()
    {
        if ($this->initializer === 0 || $this->initializer !== \getmypid()) {
            return;
        }
        if (!\is_resource($this->queue) || !\msg_queue_exists($this->key)) {
            return;
        }
        \msg_remove_queue($this->queue);
    }
    /**
     * Releases a lock from the semaphore.
     *
     * @param int $id Lock identifier.
     *
     * @throws SyncException If the operation failed.
     */
    protected function release($id)
    {
        if (!\is_int($id)) {
            if (!(\is_bool($id) || \is_numeric($id))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (int) $id;
            }
        }
        if (!$this->queue) {
            return;
            // Queue already destroyed.
        }
        // Call send in non-blocking mode. If the call fails because the queue
        // is full, then the number of locks configured is too large.
        if (!@\msg_send($this->queue, 1, \pack("C", $id), \false, \false, $errno)) {
            if ($errno === \MSG_EAGAIN) {
                throw new SyncException('The semaphore size is larger than the system allows.');
            }
            throw new SyncException('Failed to release the lock.');
        }
    }
    private static function makeKey($id)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        $phabelReturn = \abs(\unpack("l", \md5($id, \true))[1]);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
