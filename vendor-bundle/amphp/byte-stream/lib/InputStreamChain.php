<?php

namespace Phabel\Amp\ByteStream;

use Phabel\Amp\Promise;
use Phabel\Amp\Success;
use function Phabel\Amp\call;
final class InputStreamChain implements InputStream
{
    /** @var InputStream[] */
    private $streams;
    /** @var bool */
    private $reading = \false;
    public function __construct(InputStream ...$streams)
    {
        $this->streams = $streams;
    }
    /** @inheritDoc */
    public function read()
    {
        if ($this->reading) {
            throw new PendingReadError();
        }
        if (!$this->streams) {
            $phabelReturn = new Success(null);
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = call(function () {
            $this->reading = \true;
            try {
                while ($this->streams) {
                    $chunk = (yield $this->streams[0]->read());
                    if ($chunk === null) {
                        \array_shift($this->streams);
                        continue;
                    }
                    return $chunk;
                }
                return null;
            } finally {
                $this->reading = \false;
            }
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
