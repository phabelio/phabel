<?php

namespace Phabel\Amp\Parallel\Worker;

use Phabel\Amp\Loop;
use Phabel\Amp\Struct;
final class BasicEnvironment implements Environment
{
    /** @var array */
    private $data = [];
    /** @var \SplPriorityQueue */
    private $queue;
    /** @var string */
    private $timer;
    public function __construct()
    {
        $this->queue = $queue = new \SplPriorityQueue();
        $data =& $this->data;
        $this->timer = Loop::repeat(1000, static function ($watcherId) use($queue, &$data) {
            if (!\is_string($watcherId)) {
                if (!(\is_string($watcherId) || \is_object($watcherId) && \method_exists($watcherId, '__toString') || (\is_bool($watcherId) || \is_numeric($watcherId)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcherId) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcherId) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $watcherId = (string) $watcherId;
                }
            }
            $time = \time();
            while (!$queue->isEmpty()) {
                list($key, $expiration) = $queue->top();
                if (!isset($data[$key])) {
                    // Item removed.
                    $queue->extract();
                    continue;
                }
                $struct = $data[$key];
                if ($struct->expire === 0) {
                    // Item was set again without a TTL.
                    $queue->extract();
                    continue;
                }
                if ($struct->expire !== $expiration) {
                    // Expiration changed or TTL updated.
                    $queue->extract();
                    continue;
                }
                if ($time < $struct->expire) {
                    // Item at top has not expired, break out of loop.
                    break;
                }
                unset($data[$key]);
                $queue->extract();
            }
            if ($queue->isEmpty()) {
                Loop::disable($watcherId);
            }
        });
        Loop::disable($this->timer);
        Loop::unreference($this->timer);
    }
    /**
     * @param string $key
     *
     * @return bool
     */
    public function exists($key)
    {
        if (!\is_string($key)) {
            if (!(\is_string($key) || \is_object($key) && \method_exists($key, '__toString') || (\is_bool($key) || \is_numeric($key)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($key) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($key) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $key = (string) $key;
            }
        }
        $phabelReturn = isset($this->data[$key]);
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
     * @param string $key
     *
     * @return mixed|null Returns null if the key does not exist.
     */
    public function get($key)
    {
        if (!\is_string($key)) {
            if (!(\is_string($key) || \is_object($key) && \method_exists($key, '__toString') || (\is_bool($key) || \is_numeric($key)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($key) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($key) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $key = (string) $key;
            }
        }
        if (!isset($this->data[$key])) {
            return null;
        }
        $struct = $this->data[$key];
        if ($struct->ttl !== null) {
            $expire = \time() + $struct->ttl;
            if ($struct->expire < $expire) {
                $struct->expire = $expire;
                $this->queue->insert([$key, $struct->expire], -$struct->expire);
            }
        }
        return $struct->data;
    }
    /**
     * @param string $key
     * @param mixed $value Using null for the value deletes the key.
     * @param int $ttl Number of seconds until data is automatically deleted. Use null for unlimited TTL.
     *
     * @throws \Error If the time-to-live is not a positive integer.
     */
    public function set($key, $value, $ttl = null)
    {
        if (!\is_string($key)) {
            if (!(\is_string($key) || \is_object($key) && \method_exists($key, '__toString') || (\is_bool($key) || \is_numeric($key)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($key) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($key) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $key = (string) $key;
            }
        }
        if (!\is_null($ttl)) {
            if (!\is_int($ttl)) {
                if (!(\is_bool($ttl) || \is_numeric($ttl))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($ttl) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($ttl) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $ttl = (int) $ttl;
                }
            }
        }
        if ($value === null) {
            $this->delete($key);
            return;
        }
        if ($ttl !== null && $ttl <= 0) {
            throw new \Error("The time-to-live must be a positive integer or null");
        }
        $struct = new PhabelAnonymousClasse71fe13e24c4713755e8bf2ab3b37a7a959c11bfef39233caed7484e3f9fd4bc5();
        $struct->data = $value;
        if ($ttl !== null) {
            $struct->ttl = $ttl;
            $struct->expire = \time() + $ttl;
            $this->queue->insert([$key, $struct->expire], -$struct->expire);
            Loop::enable($this->timer);
        }
        $this->data[$key] = $struct;
    }
    /**
     * @param string $key
     */
    public function delete($key)
    {
        if (!\is_string($key)) {
            if (!(\is_string($key) || \is_object($key) && \method_exists($key, '__toString') || (\is_bool($key) || \is_numeric($key)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($key) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($key) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $key = (string) $key;
            }
        }
        unset($this->data[$key]);
    }
    /**
     * Alias of exists().
     *
     * @param $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        $phabelReturn = $this->exists($key);
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
     * Alias of get().
     *
     * @param string $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }
    /**
     * Alias of set() with $ttl = null.
     *
     * @param string $key
     * @param mixed $value
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }
    /**
     * Alias of delete().
     *
     * @param string $key
     */
    public function offsetUnset($key)
    {
        $this->delete($key);
    }
    /**
     * Removes all values.
     */
    public function clear()
    {
        $this->data = [];
        Loop::disable($this->timer);
        $this->queue = new \SplPriorityQueue();
    }
}
if (!\class_exists(PhabelAnonymousClasse71fe13e24c4713755e8bf2ab3b37a7a959c11bfef39233caed7484e3f9fd4bc5::class)) {
    class PhabelAnonymousClasse71fe13e24c4713755e8bf2ab3b37a7a959c11bfef39233caed7484e3f9fd4bc5 implements \Phabel\Target\Php70\AnonymousClass\AnonymousClassInterface
    {
        use Struct;
        public $data;
        public $expire = 0;
        public $ttl;
        public static function getPhabelOriginalName()
        {
            return 'class@anonymous';
        }
    }
}
