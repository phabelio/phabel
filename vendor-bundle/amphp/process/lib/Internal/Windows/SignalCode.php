<?php

namespace PhabelVendor\Amp\Process\Internal\Windows;

/**
 * @internal
 * @codeCoverageIgnore Windows only.
 */
final class SignalCode
{
    const HANDSHAKE = 0x1;
    const HANDSHAKE_ACK = 0x2;
    const CHILD_PID = 0x3;
    const EXIT_CODE = 0x4;
    /**
     *
     */
    private function __construct()
    {
        // empty to prevent instances of this class
    }
}
