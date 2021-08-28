<?php

namespace Phabel\Amp\Parallel\Sync;

use Phabel\Amp\ByteStream\ResourceInputStream;
use Phabel\Amp\ByteStream\ResourceOutputStream;
use Phabel\Amp\Promise;
use Phabel\Amp\Serialization\Serializer;
final class ChannelledSocket implements Channel
{
    /** @var ChannelledStream */
    private $channel;
    /** @var ResourceInputStream */
    private $read;
    /** @var ResourceOutputStream */
    private $write;
    /**
     * @param resource $read Readable stream resource.
     * @param resource $write Writable stream resource.
     * @param Serializer|null $serializer
     *
     * @throws \Error If a stream resource is not given for $resource.
     */
    public function __construct($read, $write, $serializer = null)
    {
        if (!($serializer instanceof Serializer || \is_null($serializer))) {
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($serializer) must be of type ?Serializer, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($serializer) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $this->channel = new ChannelledStream($this->read = new ResourceInputStream($read), $this->write = new ResourceOutputStream($write), $serializer);
    }
    /**
     * {@inheritdoc}
     */
    public function receive()
    {
        $phabelReturn = $this->channel->receive();
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function send($data)
    {
        $phabelReturn = $this->channel->send($data);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function unreference()
    {
        $this->read->unreference();
    }
    public function reference()
    {
        $this->read->reference();
    }
    /**
     * Closes the read and write resource streams.
     */
    public function close()
    {
        $this->read->close();
        $this->write->close();
    }
}
