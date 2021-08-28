<?php

namespace Phabel\Amp\Parallel\Worker\Internal;

use Phabel\Amp\ByteStream;
use Phabel\Amp\Parallel\Context\Context;
use Phabel\Amp\Parallel\Context\Process;
use Phabel\Amp\Promise;
use function Phabel\Amp\call;
class WorkerProcess implements Context
{
    /** @var Process */
    private $process;
    public function __construct($script, array $env = [], $binary = null)
    {
        if (!\is_null($binary)) {
            if (!\is_string($binary)) {
                if (!(\is_string($binary) || \is_object($binary) && \method_exists($binary, '__toString') || (\is_bool($binary) || \is_numeric($binary)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($binary) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($binary) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $binary = (string) $binary;
                }
            }
        }
        $this->process = new Process($script, null, $env, $binary);
    }
    public function receive()
    {
        $phabelReturn = $this->process->receive();
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function send($data)
    {
        $phabelReturn = $this->process->send($data);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function isRunning()
    {
        $phabelReturn = $this->process->isRunning();
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function start()
    {
        $phabelReturn = call(function () {
            $result = (yield $this->process->start());
            $stdout = $this->process->getStdout();
            $stdout->unreference();
            $stderr = $this->process->getStderr();
            $stderr->unreference();
            ByteStream\pipe($stdout, ByteStream\getStdout());
            ByteStream\pipe($stderr, ByteStream\getStderr());
            return $result;
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function kill()
    {
        if ($this->process->isRunning()) {
            $this->process->kill();
        }
    }
    public function join()
    {
        $phabelReturn = $this->process->join();
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
