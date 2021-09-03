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
    public function __construct(string $id)
    {
        $this->id = $id;
    }
    /**
     * @return string Task identifier.
     */
    public function getId() : string
    {
        return $this->id;
    }
    /**
     * @return Promise<mixed> Resolved with the task result or failure reason.
     */
    public abstract function promise() : Promise;
}
