<?php

namespace Phabel\Amp\Parallel\Worker\Internal;

use Phabel\Amp\Parallel\Worker\Task;
/** @internal */
final class Job
{
    /** @var string */
    private $id;
    /** @var Task */
    private $task;
    public function __construct(Task $task)
    {
        static $id = 'a';
        $this->task = $task;
        $this->id = $id++;
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
    public function getTask()
    {
        // Classes that cannot be autoloaded will be unserialized as an instance of __PHP_Incomplete_Class.
        if ($this->task instanceof \__PHP_Incomplete_Class) {
            throw new \Error(\sprintf("Classes implementing %s must be autoloadable by the Composer autoloader", Task::class));
        }
        $phabelReturn = $this->task;
        if (!$phabelReturn instanceof Task) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Task, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
