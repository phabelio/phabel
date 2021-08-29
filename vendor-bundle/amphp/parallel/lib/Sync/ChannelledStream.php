<?php

namespace Phabel\Amp\Parallel\Sync;

use Phabel\Amp\ByteStream\InputStream;
use Phabel\Amp\ByteStream\OutputStream;
use Phabel\Amp\ByteStream\StreamException;
use Phabel\Amp\Promise;
use Phabel\Amp\Serialization\Serializer;
use function Phabel\Amp\call;
/**
 * An asynchronous channel for sending data between threads and processes.
 *
 * Supports full duplex read and write.
 */
final class ChannelledStream implements Channel
{
    /** @var InputStream */
    private $read;
    /** @var OutputStream */
    private $write;
    /** @var \SplQueue */
    private $received;
    /** @var ChannelParser */
    private $parser;
    /**
     * Creates a new channel from the given stream objects. Note that $read and $write can be the same object.
     *
     * @param InputStream $read
     * @param OutputStream $write
     * @param Serializer|null $serializer
     */
    public function __construct(InputStream $read, OutputStream $write, $serializer = null)
    {
        if (!($serializer instanceof Serializer || \is_null($serializer))) {
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($serializer) must be of type ?Serializer, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($serializer) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $this->read = $read;
        $this->write = $write;
        $this->received = new \SplQueue();
        $this->parser = new ChannelParser([$this->received, 'push'], $serializer);
    }
    /**
     * {@inheritdoc}
     */
    public function send($data) : Promise
    {
        return call(function () use($data) : \Generator {
            try {
                return (yield $this->write->write($this->parser->encode($data)));
            } catch (StreamException $exception) {
                throw new ChannelException("Sending on the channel failed. Did the context die?", 0, $exception);
            }
        });
    }
    /**
     * {@inheritdoc}
     */
    public function receive() : Promise
    {
        return call(function () : \Generator {
            while ($this->received->isEmpty()) {
                try {
                    $chunk = (yield $this->read->read());
                } catch (StreamException $exception) {
                    throw new ChannelException("Reading from the channel failed. Did the context die?", 0, $exception);
                }
                if ($chunk === null) {
                    throw new ChannelException("The channel closed unexpectedly. Did the context die?");
                }
                $this->parser->push($chunk);
            }
            return $this->received->shift();
        });
    }
}
