<?php

/**
 * Run command, exiting with error code if it fails.
 *
 * @param string $cmd
 * @return void
 */
function r($cmd)
{
    if (!\is_string($cmd)) {
        throw new \TypeError(__METHOD__ . '(): Argument #1 ($cmd) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($cmd) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    \passthru($cmd, $ret);
    if ($ret) {
        die($ret);
    }
}
