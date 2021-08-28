<?php

namespace Phabel\Amp\Parallel\Worker\Internal;

use Phabel\Amp\Failure;
use Phabel\Amp\Parallel\Worker\Task;
use Phabel\Amp\Promise;
use Phabel\Amp\Success;
/** @internal */
final class TaskSuccess extends TaskResult
{
    /** @var mixed Result of task. */
    private $result;
    public function __construct($id, $result)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        parent::__construct($id);
        $this->result = $result;
    }
    public function promise()
    {
        if ($this->result instanceof \__PHP_Incomplete_Class) {
            $phabelReturn = new Failure(new \Error(\sprintf("Class instances returned from %s::run() must be autoloadable by the Composer autoloader", Task::class)));
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = new Success($this->result);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
