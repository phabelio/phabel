<?php

namespace PhabelVendor\Amp\Process\Internal\Windows;

/**
 * @internal
 * @codeCoverageIgnore Windows only.
 */
final class SignalCode
{
    const HANDSHAKE = 1;
    const HANDSHAKE_ACK = 2;
    const CHILD_PID = 3;
    const EXIT_CODE = 4;
    /**
     *
     */
    private function __construct()
    {
        // empty to prevent instances of this class
    }
}
