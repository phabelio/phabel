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
    public function receive() : Promise
    {
        return $this->channel->receive();
    }
    /**
     * {@inheritdoc}
     */
    public function send($data) : Promise
    {
        return $this->channel->send($data);
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
