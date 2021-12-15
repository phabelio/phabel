<?php

namespace PhabelVendor\Amp\ByteStream;

use PhabelVendor\Amp\Deferred;
use PhabelVendor\Amp\Promise;
use PhabelVendor\Amp\Success;
class OutputBuffer implements OutputStream, Promise
{
    /** @var Deferred */
    private $deferred;
    /** @var string */
    private $contents = '';
    /** @var bool */
    private $closed = \false;
    /**
     *
     */
    public function __construct()
    {
        $this->deferred = new Deferred();
    }
    /**
     *
     */
    public function write(string $data) : Promise
    {
        if ($this->closed) {
            throw new ClosedException("The stream has already been closed.");
        }
        $this->contents .= $data;
        return new Success(\strlen($data));
    }
    /**
     *
     */
    public function end(string $finalData = "") : Promise
    {
        if ($this->closed) {
            throw new ClosedException("The stream has already been closed.");
        }
        $this->contents .= $finalData;
        $this->closed = \true;
        $this->deferred->resolve($this->contents);
        $this->contents = "";
        return new Success(\strlen($finalData));
    }
    /**
     *
     */
    public function onResolve(callable $onResolved)
    {
        $this->deferred->promise()->onResolve($onResolved);
    }
}
