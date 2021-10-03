<?php

namespace PhabelVendor\Amp\Loop;

use PhabelVendor\Amp\Struct;
/**
 * @template TValue as (int|resource|null)
 *
 * @psalm-suppress MissingConstructor
 */
class Watcher
{
    use Struct;
    const IO = 0b11;
    const READABLE = 0b1;
    const WRITABLE = 0b10;
    const DEFER = 0b100;
    const TIMER = 0b11000;
    const DELAY = 0b1000;
    const REPEAT = 0b10000;
    const SIGNAL = 0b100000;
    /** @var int */
    public $type;
    /** @var bool */
    public $enabled = \true;
    /** @var bool */
    public $referenced = \true;
    /** @var string */
    public $id;
    /** @var callable */
    public $callback;
    /**
     * Data provided to the watcher callback.
     *
     * @var mixed
     */
    public $data;
    /**
     * Watcher-dependent value storage. Stream for IO watchers, signal number for signal watchers, interval for timers.
     *
     * @var resource|int|null
     * @psalm-var TValue
     */
    public $value;
    /** @var int|null */
    public $expiration;
}
