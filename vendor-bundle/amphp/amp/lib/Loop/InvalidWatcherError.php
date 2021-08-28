<?php

namespace Phabel\Amp\Loop;

/**
 * MUST be thrown if any operation (except disable() and cancel()) is attempted with an invalid watcher identifier.
 *
 * An invalid watcher identifier is any identifier that is not yet emitted by the driver or cancelled by the user.
 */
class InvalidWatcherError extends \Error
{
    /** @var string */
    private $watcherId;
    /**
     * @param string $watcherId The watcher identifier.
     * @param string $message The exception message.
     */
    public function __construct($watcherId, $message)
    {
        if (!\is_string($watcherId)) {
            if (!(\is_string($watcherId) || \is_object($watcherId) && \method_exists($watcherId, '__toString') || (\is_bool($watcherId) || \is_numeric($watcherId)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($watcherId) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($watcherId) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $watcherId = (string) $watcherId;
            }
        }
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        $this->watcherId = $watcherId;
        parent::__construct($message);
    }
    /**
     * @return string The watcher identifier.
     */
    public function getWatcherId()
    {
        return $this->watcherId;
    }
}
