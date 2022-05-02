<?php

namespace PhabelVendor\Amp\Parallel\Sync;

use PhabelVendor\Amp\ByteStream\ResourceInputStream;
use PhabelVendor\Amp\ByteStream\ResourceOutputStream;
use PhabelVendor\Amp\Promise;
use PhabelVendor\Amp\Serialization\Serializer;
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
    public function __construct($read, $write, ?Serializer $serializer = null)
    {
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
    public function unreference() : void
    {
        $this->read->unreference();
    }
    public function reference() : void
    {
        $this->read->reference();
    }
    /**
     * Closes the read and write resource streams.
     */
    public function close() : void
    {
        $this->read->close();
        $this->write->close();
    }
}
