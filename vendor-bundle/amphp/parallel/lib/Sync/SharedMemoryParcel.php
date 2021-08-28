<?php

namespace Phabel\Amp\Parallel\Sync;

use Phabel\Amp\Promise;
use Phabel\Amp\Serialization\NativeSerializer;
use Phabel\Amp\Serialization\Serializer;
use Phabel\Amp\Sync\Lock;
use Phabel\Amp\Sync\PosixSemaphore;
use Phabel\Amp\Sync\SyncException;
use function Phabel\Amp\call;
/**
 * A container object for sharing a value across contexts.
 *
 * A shared object is a container that stores an object inside shared memory.
 * The object can be accessed and mutated by any thread or process. The shared
 * object handle itself is serializable and can be sent to any thread or process
 * to give access to the value that is shared in the container.
 *
 * Because each shared object uses its own shared memory segment, it is much
 * more efficient to store a larger object containing many values inside a
 * single shared container than to use many small shared containers.
 *
 * Note that accessing a shared object is not atomic. Access to a shared object
 * should be protected with a mutex to preserve data integrity.
 *
 * When used with forking, the object must be created prior to forking for both
 * processes to access the synchronized object.
 *
 * @see http://php.net/manual/en/book.shmop.php The shared memory extension.
 * @see http://man7.org/linux/man-pages/man2/shmctl.2.html How shared memory works on Linux.
 * @see https://msdn.microsoft.com/en-us/library/ms810613.aspx How shared memory works on Windows.
 */
final class SharedMemoryParcel implements Parcel
{
    /** @var int The byte offset to the start of the object data in memory. */
    const MEM_DATA_OFFSET = 7;
    // A list of valid states the object can be in.
    const STATE_UNALLOCATED = 0;
    const STATE_ALLOCATED = 1;
    const STATE_MOVED = 2;
    const STATE_FREED = 3;
    /** @var string */
    private $id;
    /** @var int The shared memory segment key. */
    private $key;
    /** @var PosixSemaphore A semaphore for synchronizing on the parcel. */
    private $semaphore;
    /** @var resource|null An open handle to the shared memory segment. */
    private $handle;
    /** @var int */
    private $initializer = 0;
    /** @var Serializer */
    private $serializer;
    /**
     * @param string $id
     * @param mixed $value
     * @param int $size The initial size in bytes of the shared memory segment. It will automatically be expanded as
     *     necessary.
     * @param int $permissions Permissions to access the semaphore. Use file permission format specified as 0xxx.
     * @param Serializer|null $serializer
     *
     * @return self
     *
     * @throws SharedMemoryException
     * @throws SyncException
     * @throws \Error If the size or permissions are invalid.
     */
    public static function create($id, $value, $size = 8192, $permissions = 0600, $serializer = null)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        if (!\is_int($size)) {
            if (!(\is_bool($size) || \is_numeric($size))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($size) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($size) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $size = (int) $size;
            }
        }
        if (!\is_int($permissions)) {
            if (!(\is_bool($permissions) || \is_numeric($permissions))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($permissions) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($permissions) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $permissions = (int) $permissions;
            }
        }
        if (!($serializer instanceof Serializer || \is_null($serializer))) {
            throw new \TypeError(__METHOD__ . '(): Argument #5 ($serializer) must be of type ?Serializer, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($serializer) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $parcel = new self($id, $serializer);
        $parcel->init($value, $size, $permissions);
        $phabelReturn = $parcel;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param string $id
     * @param Serializer|null $serializer
     *
     * @return self
     *
     * @throws SharedMemoryException
     */
    public static function use_($id, $serializer = null)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        if (!($serializer instanceof Serializer || \is_null($serializer))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($serializer) must be of type ?Serializer, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($serializer) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $parcel = new self($id, $serializer);
        $parcel->open();
        $phabelReturn = $parcel;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @param string $id
     * @param Serializer|null $serializer
     */
    private function __construct($id, $serializer = null)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        if (!($serializer instanceof Serializer || \is_null($serializer))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($serializer) must be of type ?Serializer, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($serializer) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\extension_loaded("shmop")) {
            throw new \Error(__CLASS__ . " requires the shmop extension");
        }
        $this->id = $id;
        $this->key = self::makeKey($this->id);
        $this->serializer = isset($serializer) ? $serializer : new NativeSerializer();
    }
    /**
     * @param mixed $value
     * @param int   $size
     * @param int   $permissions
     *
     * @throws SharedMemoryException
     * @throws SyncException
     * @throws \Error If the size or permissions are invalid.
     */
    private function init($value, $size = 8192, $permissions = 0600)
    {
        if (!\is_int($size)) {
            if (!(\is_bool($size) || \is_numeric($size))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($size) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($size) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $size = (int) $size;
            }
        }
        if (!\is_int($permissions)) {
            if (!(\is_bool($permissions) || \is_numeric($permissions))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($permissions) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($permissions) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $permissions = (int) $permissions;
            }
        }
        if ($size <= 0) {
            throw new \Error('The memory size must be greater than 0');
        }
        if ($permissions <= 0 || $permissions > 0777) {
            throw new \Error('Invalid permissions');
        }
        $this->semaphore = PosixSemaphore::create($this->id, 1);
        $this->initializer = \getmypid();
        $this->memOpen($this->key, 'n', $permissions, $size + self::MEM_DATA_OFFSET);
        $this->setHeader(self::STATE_ALLOCATED, 0, $permissions);
        $this->wrap($value);
    }
    private function open()
    {
        $this->semaphore = PosixSemaphore::use_($this->id);
        $this->memOpen($this->key, 'w', 0, 0);
    }
    /**
     * Checks if the object has been freed.
     *
     * Note that this does not check if the object has been destroyed; it only
     * checks if this handle has freed its reference to the object.
     *
     * @return bool True if the object is freed, otherwise false.
     */
    private function isFreed()
    {
        // If we are no longer connected to the memory segment, check if it has
        // been invalidated.
        if ($this->handle !== null) {
            $this->handleMovedMemory();
            $header = $this->getHeader();
            $phabelReturn = $header['state'] === static::STATE_FREED;
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (bool) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = \true;
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
     * {@inheritdoc}
     */
    public function unwrap()
    {
        $phabelReturn = call(function () {
            $lock = (yield $this->semaphore->acquire());
            \assert($lock instanceof Lock);
            try {
                return $this->getValue();
            } finally {
                $lock->release();
            }
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return mixed
     *
     * @throws SharedMemoryException
     * @throws SerializationException
     */
    private function getValue()
    {
        if ($this->isFreed()) {
            throw new SharedMemoryException('The object has already been freed');
        }
        $header = $this->getHeader();
        // Make sure the header is in a valid state and format.
        if ($header['state'] !== self::STATE_ALLOCATED || $header['size'] <= 0) {
            throw new SharedMemoryException('Shared object memory is corrupt');
        }
        // Read the actual value data from memory and unserialize it.
        $data = $this->memGet(self::MEM_DATA_OFFSET, $header['size']);
        return $this->serializer->unserialize($data);
    }
    /**
     * If the value requires more memory to store than currently allocated, a
     * new shared memory segment will be allocated with a larger size to store
     * the value in. The previous memory segment will be cleaned up and marked
     * for deletion. Other processes and threads will be notified of the new
     * memory segment on the next read attempt. Once all running processes and
     * threads disconnect from the old segment, it will be freed by the OS.
     */
    private function wrap($value)
    {
        if ($this->isFreed()) {
            throw new SharedMemoryException('The object has already been freed');
        }
        $serialized = $this->serializer->serialize($value);
        $size = \strlen($serialized);
        $header = $this->getHeader();
        /* If we run out of space, we need to allocate a new shared memory
              segment that is larger than the current one. To coordinate with other
              processes, we will leave a message in the old segment that the segment
              has moved and along with the new key. The old segment will be discarded
              automatically after all other processes notice the change and close
              the old handle.
           */
        if (\shmop_size($this->handle) < $size + self::MEM_DATA_OFFSET) {
            $this->key = $this->key < 0xffffffff ? $this->key + 1 : \random_int(0x10, 0xfffffffe);
            $this->setHeader(self::STATE_MOVED, $this->key, 0);
            $this->memDelete();
            \shmop_close($this->handle);
            $this->memOpen($this->key, 'n', $header['permissions'], $size * 2);
        }
        // Rewrite the header and the serialized value to memory.
        $this->setHeader(self::STATE_ALLOCATED, $size, $header['permissions']);
        $this->memSet(self::MEM_DATA_OFFSET, $serialized);
    }
    /**
     * {@inheritdoc}
     */
    public function synchronized(callable $callback)
    {
        $phabelReturn = call(function () use($callback) {
            $lock = (yield $this->semaphore->acquire());
            \assert($lock instanceof Lock);
            try {
                $result = (yield call($callback, $this->getValue()));
                if ($result !== null) {
                    $this->wrap($result);
                }
            } finally {
                $lock->release();
            }
            return $result;
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Frees the shared object from memory.
     *
     * The memory containing the shared value will be invalidated. When all
     * process disconnect from the object, the shared memory block will be
     * destroyed by the OS.
     */
    public function __destruct()
    {
        if ($this->initializer === 0 || $this->initializer !== \getmypid()) {
            return;
        }
        if ($this->isFreed()) {
            return;
        }
        // Invalidate the memory block by setting its state to FREED.
        $this->setHeader(static::STATE_FREED, 0, 0);
        // Request the block to be deleted, then close our local handle.
        $this->memDelete();
        \shmop_close($this->handle);
        $this->handle = null;
        $this->semaphore = null;
    }
    /**
     * Private method to prevent cloning.
     */
    private function __clone()
    {
    }
    /**
     * Private method to prevent serialization.
     */
    private function __sleep()
    {
    }
    /**
     * Updates the current memory segment handle, handling any moves made on the
     * data.
     *
     * @throws SharedMemoryException
     */
    private function handleMovedMemory()
    {
        // Read from the memory block and handle moved blocks until we find the
        // correct block.
        while (\true) {
            $header = $this->getHeader();
            // If the state is STATE_MOVED, the memory is stale and has been moved
            // to a new location. Move handle and try to read again.
            if ($header['state'] !== self::STATE_MOVED) {
                break;
            }
            \shmop_close($this->handle);
            $this->key = $header['size'];
            $this->memOpen($this->key, 'w', 0, 0);
        }
    }
    /**
     * Reads and returns the data header at the current memory segment.
     *
     * @return array An associative array of header data.
     *
     * @throws SharedMemoryException
     */
    private function getHeader()
    {
        $data = $this->memGet(0, self::MEM_DATA_OFFSET);
        $phabelReturn = \unpack('Cstate/Lsize/Spermissions', $data);
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Sets the header data for the current memory segment.
     *
     * @param int $state       An object state.
     * @param int $size        The size of the stored data, or other value.
     * @param int $permissions The permissions mask on the memory segment.
     *
     * @throws SharedMemoryException
     */
    private function setHeader($state, $size, $permissions)
    {
        if (!\is_int($state)) {
            if (!(\is_bool($state) || \is_numeric($state))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($state) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($state) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $state = (int) $state;
            }
        }
        if (!\is_int($size)) {
            if (!(\is_bool($size) || \is_numeric($size))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($size) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($size) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $size = (int) $size;
            }
        }
        if (!\is_int($permissions)) {
            if (!(\is_bool($permissions) || \is_numeric($permissions))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($permissions) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($permissions) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $permissions = (int) $permissions;
            }
        }
        $header = \pack('CLS', $state, $size, $permissions);
        $this->memSet(0, $header);
    }
    /**
     * Opens a shared memory handle.
     *
     * @param int    $key         The shared memory key.
     * @param string $mode        The mode to open the shared memory in.
     * @param int    $permissions Process permissions on the shared memory.
     * @param int    $size        The size to crate the shared memory in bytes.
     *
     * @throws SharedMemoryException
     */
    private function memOpen($key, $mode, $permissions, $size)
    {
        if (!\is_int($key)) {
            if (!(\is_bool($key) || \is_numeric($key))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($key) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($key) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $key = (int) $key;
            }
        }
        if (!\is_string($mode)) {
            if (!(\is_string($mode) || \is_object($mode) && \method_exists($mode, '__toString') || (\is_bool($mode) || \is_numeric($mode)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($mode) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($mode) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $mode = (string) $mode;
            }
        }
        if (!\is_int($permissions)) {
            if (!(\is_bool($permissions) || \is_numeric($permissions))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($permissions) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($permissions) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $permissions = (int) $permissions;
            }
        }
        if (!\is_int($size)) {
            if (!(\is_bool($size) || \is_numeric($size))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($size) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($size) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $size = (int) $size;
            }
        }
        $handle = @\shmop_open($key, $mode, $permissions, $size);
        if ($handle === \false) {
            $error = \error_get_last();
            throw new SharedMemoryException('Failed to create shared memory block: ' . (isset($error['message']) ? $error['message'] : 'unknown error'));
        }
        $this->handle = $handle;
    }
    /**
     * Reads binary data from shared memory.
     *
     * @param int $offset The offset to read from.
     * @param int $size   The number of bytes to read.
     *
     * @return string The binary data at the given offset.
     *
     * @throws SharedMemoryException
     */
    private function memGet($offset, $size)
    {
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $offset = (int) $offset;
            }
        }
        if (!\is_int($size)) {
            if (!(\is_bool($size) || \is_numeric($size))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($size) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($size) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $size = (int) $size;
            }
        }
        $data = \shmop_read($this->handle, $offset, $size);
        if ($data === \false) {
            $error = \error_get_last();
            throw new SharedMemoryException('Failed to read from shared memory block: ' . (isset($error['message']) ? $error['message'] : 'unknown error'));
        }
        $phabelReturn = $data;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Writes binary data to shared memory.
     *
     * @param int    $offset The offset to write to.
     * @param string $data   The binary data to write.
     *
     * @throws SharedMemoryException
     */
    private function memSet($offset, $data)
    {
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $offset = (int) $offset;
            }
        }
        if (!\is_string($data)) {
            if (!(\is_string($data) || \is_object($data) && \method_exists($data, '__toString') || (\is_bool($data) || \is_numeric($data)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($data) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($data) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $data = (string) $data;
            }
        }
        if (!\shmop_write($this->handle, $data, $offset)) {
            $error = \error_get_last();
            throw new SharedMemoryException('Failed to write to shared memory block: ' . (isset($error['message']) ? $error['message'] : 'unknown error'));
        }
    }
    /**
     * Requests the shared memory segment to be deleted.
     *
     * @throws SharedMemoryException
     */
    private function memDelete()
    {
        if (!\shmop_delete($this->handle)) {
            $error = \error_get_last();
            throw new SharedMemoryException('Failed to discard shared memory block' . (isset($error['message']) ? $error['message'] : 'unknown error'));
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
