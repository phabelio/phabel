<?php

namespace Phabel\Amp\Parallel\Worker;

use Phabel\Amp\Coroutine;
use Phabel\Amp\Parallel\Sync\Channel;
use Phabel\Amp\Parallel\Sync\SerializationException;
use Phabel\Amp\Promise;
use function Phabel\Amp\call;
final class TaskRunner
{
    /** @var Channel */
    private $channel;
    /** @var Environment */
    private $environment;
    public function __construct(Channel $channel, Environment $environment)
    {
        $this->channel = $channel;
        $this->environment = $environment;
    }
    /**
     * Runs the task runner, receiving tasks from the parent and sending the result of those tasks.
     *
     * @return \Amp\Promise
     */
    public function run()
    {
        $phabelReturn = new Coroutine($this->execute());
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
    private function execute()
    {
        $job = (yield $this->channel->receive());
        while ($job instanceof Internal\Job) {
            try {
                $result = (yield call([$job->getTask(), "run"], $this->environment));
                $result = new Internal\TaskSuccess($job->getId(), $result);
            } catch (\Exception $exception) {
                $result = new Internal\TaskFailure($job->getId(), $exception);
            } catch (\Error $exception) {
                $result = new Internal\TaskFailure($job->getId(), $exception);
            }
            $job = null;
            // Free memory from last job.
            try {
                (yield $this->channel->send($result));
            } catch (SerializationException $exception) {
                // Could not serialize task result.
                (yield $this->channel->send(new Internal\TaskFailure($result->getId(), $exception)));
            }
            $result = null;
            // Free memory from last result.
            $job = (yield $this->channel->receive());
        }
        return $job;
    }
}
