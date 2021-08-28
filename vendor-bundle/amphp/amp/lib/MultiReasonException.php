<?php

namespace Phabel\Amp;

class MultiReasonException extends \Exception
{
    /** @var \Throwable[] */
    private $reasons;
    /**
     * @param \Throwable[] $reasons Array of exceptions rejecting the promise.
     * @param string|null  $message
     */
    public function __construct(array $reasons, $message = null)
    {
        if (!\is_null($message)) {
            if (!\is_string($message)) {
                if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($message) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $message = (string) $message;
                }
            }
        }
        parent::__construct($message ?: "Multiple errors encountered; use " . self::class . "::getReasons() to retrieve the array of exceptions thrown");
        $this->reasons = $reasons;
    }
    /**
     * @return \Throwable[]
     */
    public function getReasons()
    {
        $phabelReturn = $this->reasons;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
