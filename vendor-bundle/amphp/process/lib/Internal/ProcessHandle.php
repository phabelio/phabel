<?php

namespace Phabel\Amp\Process\Internal;

use Phabel\Amp\Deferred;
use Phabel\Amp\Process\ProcessInputStream;
use Phabel\Amp\Process\ProcessOutputStream;
use Phabel\Amp\Struct;
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
