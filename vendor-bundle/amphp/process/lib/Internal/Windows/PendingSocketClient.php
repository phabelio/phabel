<?php

namespace PhabelVendor\Amp\Process\Internal\Windows;

use PhabelVendor\Amp\Struct;
/**
 * @internal
 * @codeCoverageIgnore Windows only.
 */
final class PendingSocketClient
{
    use Struct;
    public $readWatcher;
    public $timeoutWatcher;
    public $receivedDataBuffer = '';
    public $pid;
    public $streamId;
}
