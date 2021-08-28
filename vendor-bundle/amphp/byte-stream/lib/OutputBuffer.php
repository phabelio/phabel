<?php

namespace Phabel\Amp\ByteStream;

use Phabel\Amp\Deferred;
use Phabel\Amp\Promise;
use Phabel\Amp\Success;
class OutputBuffer implements OutputStream, Promise
{
    /** @var Deferred */
    private $deferred;
    /** @var string */
    private $contents = '';
    /** @var bool */
    private $closed = \false;
    public function __construct()
    {
        $this->deferred = new Deferred();
    }
    public function write($data)
    {
        if (!\is_string($data)) {
            if (!(\is_string($data) || \is_object($data) && \method_exists($data, '__toString') || (\is_bool($data) || \is_numeric($data)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($data) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($data) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $data = (string) $data;
            }
        }
        if ($this->closed) {
            throw new ClosedException("The stream has already been closed.");
        }
        $this->contents .= $data;
        $phabelReturn = new Success(\strlen($data));
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function end($finalData = "")
    {
        if (!\is_string($finalData)) {
            if (!(\is_string($finalData) || \is_object($finalData) && \method_exists($finalData, '__toString') || (\is_bool($finalData) || \is_numeric($finalData)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($finalData) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($finalData) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $finalData = (string) $finalData;
            }
        }
        if ($this->closed) {
            throw new ClosedException("The stream has already been closed.");
        }
        $this->contents .= $finalData;
        $this->closed = \true;
        $this->deferred->resolve($this->contents);
        $this->contents = "";
        $phabelReturn = new Success(\strlen($finalData));
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function onResolve(callable $onResolved)
    {
        $this->deferred->promise()->onResolve($onResolved);
    }
}
