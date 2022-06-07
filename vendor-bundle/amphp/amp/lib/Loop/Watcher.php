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
    const IO = 3;
    const READABLE = 1;
    const WRITABLE = 2;
    const DEFER = 4;
    const TIMER = 24;
    const DELAY = 8;
    const REPEAT = 16;
    const SIGNAL = 32;
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
