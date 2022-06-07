<?php

namespace PhabelVendor\Amp\Process\Internal\Windows;

/**
 * @internal
 * @codeCoverageIgnore Windows only.
 */
final class HandshakeStatus
{
    const SUCCESS = 0;
    const SIGNAL_UNEXPECTED = 1;
    const INVALID_STREAM_ID = 2;
    const INVALID_PROCESS_ID = 3;
    const DUPLICATE_STREAM_ID = 4;
    const INVALID_CLIENT_TOKEN = 5;
    /**
     *
     */
    private function __construct()
    {
        // empty to prevent instances of this class
    }
}
