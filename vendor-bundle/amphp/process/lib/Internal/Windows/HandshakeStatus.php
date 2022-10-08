<?php

namespace PhabelVendor\Amp\Process\Internal\Windows;

/**
 * @internal
 * @codeCoverageIgnore Windows only.
 */
final class HandshakeStatus
{
    const SUCCESS = 0;
    const SIGNAL_UNEXPECTED = 0x1;
    const INVALID_STREAM_ID = 0x2;
    const INVALID_PROCESS_ID = 0x3;
    const DUPLICATE_STREAM_ID = 0x4;
    const INVALID_CLIENT_TOKEN = 0x5;
    private function __construct()
    {
        // empty to prevent instances of this class
    }
}
