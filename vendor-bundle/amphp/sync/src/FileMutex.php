<?php

namespace Phabel\Amp\Sync;

use Phabel\Amp\Coroutine;
use Phabel\Amp\Delayed;
use Phabel\Amp\Promise;
/**
 * A cross-platform mutex that uses exclusive files as the lock mechanism.
 *
 * This mutex implementation is not always atomic and depends on the operating
 * system's implementation of file creation operations. Use this implementation
 * only if no other mutex types are available.
 *
 * This implementation avoids using [flock()](https://www.php.net/flock)
 * because flock() is known to have some atomicity issues on some systems. In
 * addition, flock() does not work as expected when trying to lock a file
 * multiple times in the same process on Linux. Instead, exclusive file creation
 * is used to create a lock file, which is atomic on most systems.
 *
 * @see https://www.php.net/fopen
 */
class FileMutex implements Mutex
{
    const LATENCY_TIMEOUT = 10;
    /** @var string The full path to the lock file. */
    private $fileName;
    /**
     * @param string $fileName Name of temporary file to use as a mutex.
     */
    public function __construct($fileName)
    {
        if (!\is_string($fileName)) {
            if (!(\is_string($fileName) || \is_object($fileName) && \method_exists($fileName, '__toString') || (\is_bool($fileName) || \is_numeric($fileName)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($fileName) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($fileName) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $fileName = (string) $fileName;
            }
        }
        $this->fileName = $fileName;
    }
    /**
     * {@inheritdoc}
     */
    public function acquire()
    {
        $phabelReturn = new Coroutine($this->doAcquire());
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @coroutine
     *
     * @return \Generator
     */
    private function doAcquire()
    {
        // Try to create the lock file. If the file already exists, someone else
        // has the lock, so set an asynchronous timer and try again.
        while (($handle = @\fopen($this->fileName, 'x')) === \false) {
            (yield new Delayed(self::LATENCY_TIMEOUT));
        }
        // Return a lock object that can be used to release the lock on the mutex.
        $lock = new Lock(0, function () {
            $this->release();
        });
        \fclose($handle);
        return $lock;
    }
    /**
     * Releases the lock on the mutex.
     *
     * @throws SyncException If the unlock operation failed.
     */
    protected function release()
    {
        $success = @\unlink($this->fileName);
        if (!$success) {
            throw new SyncException('Failed to unlock the mutex file.');
        }
    }
}
