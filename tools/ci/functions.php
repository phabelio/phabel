<?php

/**
 * Run command, exiting with error code if it fails
 *
 * @param string $cmd
 * @return void
 */
function r(string $cmd)
{
    \passthru($cmd, $ret);
    if ($ret) {
        die($ret);
    }
}
