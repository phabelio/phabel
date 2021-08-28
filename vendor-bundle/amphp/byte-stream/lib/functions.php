<?php

namespace Phabel\Amp\ByteStream;

use Phabel\Amp\Iterator;
use Phabel\Amp\Loop;
use Phabel\Amp\Producer;
use Phabel\Amp\Promise;
use function Phabel\Amp\call;
// @codeCoverageIgnoreStart
if (\strlen('…') !== 3) {
    throw new \Error('The mbstring.func_overload ini setting is enabled. It must be disabled to use the stream package.');
}
// @codeCoverageIgnoreEnd
if (!\defined('STDOUT')) {
    \define('STDOUT', \fopen('php://stdout', 'w'));
}
if (!\defined('STDERR')) {
    \define('STDERR', \fopen('php://stderr', 'w'));
}
/**
 * @param \Amp\ByteStream\InputStream  $source
 * @param \Amp\ByteStream\OutputStream $destination
 *
 * @return \Amp\Promise
 */
function pipe(InputStream $source, OutputStream $destination)
{
    $phabelReturn = call(function () use($source, $destination) {
        $written = 0;
        while (($chunk = (yield $source->read())) !== null) {
            $written += \strlen($chunk);
            $writePromise = $destination->write($chunk);
            $chunk = null;
            // free memory
            (yield $writePromise);
        }
        return $written;
    });
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * @param \Amp\ByteStream\InputStream $source
 *
 * @return \Amp\Promise
 */
function buffer(InputStream $source)
{
    $phabelReturn = call(function () use($source) {
        $buffer = "";
        while (($chunk = (yield $source->read())) !== null) {
            $buffer .= $chunk;
            $chunk = null;
            // free memory
        }
        return $buffer;
    });
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * The php://input input buffer stream for the process associated with the currently active event loop.
 *
 * @return ResourceInputStream
 */
function getInputBufferStream()
{
    static $key = InputStream::class . '\\input';
    $stream = Loop::getState($key);
    if (!$stream) {
        $stream = new ResourceInputStream(\fopen('php://input', 'rb'));
        Loop::setState($key, $stream);
    }
    $phabelReturn = $stream;
    if (!$phabelReturn instanceof ResourceInputStream) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type ResourceInputStream, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * The php://output output buffer stream for the process associated with the currently active event loop.
 *
 * @return ResourceOutputStream
 */
function getOutputBufferStream()
{
    static $key = OutputStream::class . '\\output';
    $stream = Loop::getState($key);
    if (!$stream) {
        $stream = new ResourceOutputStream(\fopen('php://output', 'wb'));
        Loop::setState($key, $stream);
    }
    $phabelReturn = $stream;
    if (!$phabelReturn instanceof ResourceOutputStream) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type ResourceOutputStream, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * The STDIN stream for the process associated with the currently active event loop.
 *
 * @return ResourceInputStream
 */
function getStdin()
{
    static $key = InputStream::class . '\\stdin';
    $stream = Loop::getState($key);
    if (!$stream) {
        $stream = new ResourceInputStream(\STDIN);
        Loop::setState($key, $stream);
    }
    $phabelReturn = $stream;
    if (!$phabelReturn instanceof ResourceInputStream) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type ResourceInputStream, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * The STDOUT stream for the process associated with the currently active event loop.
 *
 * @return ResourceOutputStream
 */
function getStdout()
{
    static $key = OutputStream::class . '\\stdout';
    $stream = Loop::getState($key);
    if (!$stream) {
        $stream = new ResourceOutputStream(\STDOUT);
        Loop::setState($key, $stream);
    }
    $phabelReturn = $stream;
    if (!$phabelReturn instanceof ResourceOutputStream) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type ResourceOutputStream, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * The STDERR stream for the process associated with the currently active event loop.
 *
 * @return ResourceOutputStream
 */
function getStderr()
{
    static $key = OutputStream::class . '\\stderr';
    $stream = Loop::getState($key);
    if (!$stream) {
        $stream = new ResourceOutputStream(\STDERR);
        Loop::setState($key, $stream);
    }
    $phabelReturn = $stream;
    if (!$phabelReturn instanceof ResourceOutputStream) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type ResourceOutputStream, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
function parseLineDelimitedJson(InputStream $stream, $assoc = \false, $depth = 512, $options = 0)
{
    if (!\is_bool($assoc)) {
        if (!(\is_bool($assoc) || \is_numeric($assoc) || \is_string($assoc))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($assoc) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($assoc) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        } else {
            $assoc = (bool) $assoc;
        }
    }
    if (!\is_int($depth)) {
        if (!(\is_bool($depth) || \is_numeric($depth))) {
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($depth) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($depth) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        } else {
            $depth = (int) $depth;
        }
    }
    if (!\is_int($options)) {
        if (!(\is_bool($options) || \is_numeric($options))) {
            throw new \TypeError(__METHOD__ . '(): Argument #4 ($options) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($options) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        } else {
            $options = (int) $options;
        }
    }
    $phabelReturn = new Producer(static function (callable $emit) use($stream, $assoc, $depth, $options) {
        $reader = new LineReader($stream);
        while (null !== ($line = (yield $reader->readLine()))) {
            $line = \trim($line);
            if ($line === '') {
                continue;
            }
            /** @noinspection PhpComposerExtensionStubsInspection */
            $data = \json_decode($line, $assoc, $depth, $options);
            /** @noinspection PhpComposerExtensionStubsInspection */
            $error = \json_last_error();
            /** @noinspection PhpComposerExtensionStubsInspection */
            if ($error !== \JSON_ERROR_NONE) {
                /** @noinspection PhpComposerExtensionStubsInspection */
                throw new StreamException('Failed to parse JSON: ' . \json_last_error_msg(), $error);
            }
            (yield $emit($data));
        }
    });
    if (!$phabelReturn instanceof Iterator) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Iterator, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
