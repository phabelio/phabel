<?php

namespace PhabelVendor\Amp\Process\Internal;

use PhabelVendor\Amp\Deferred;
use PhabelVendor\Amp\Process\ProcessInputStream;
use PhabelVendor\Amp\Process\ProcessOutputStream;
use PhabelVendor\Amp\Struct;
abstract class ProcessHandle
{
    use Struct;
    /** @var ProcessOutputStream */
    public $stdin;
    /** @var ProcessInputStream */
    public $stdout;
    /** @var ProcessInputStream */
    public $stderr;
    /** @var Deferred */
    public $pidDeferred;
    /** @var int */
    public $status = ProcessStatus::STARTING;
}
