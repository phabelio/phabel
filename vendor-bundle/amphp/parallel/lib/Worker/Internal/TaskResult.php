<?php

namespace Phabel\Amp\Parallel\Worker\Internal;

use Phabel\Amp\Promise;
/** @internal */
abstract class TaskResult
{
    /** @var string Task identifier. */
    private $id;
    /**
     * @param string $id Task identifier.
     */
    public function __construct($id)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        $this->id = $id;
    }
    /**
     * @return string Task identifier.
     */
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
    /**
     * @return Promise<mixed> Resolved with the task result or failure reason.
     */
    public abstract function promise();
}
