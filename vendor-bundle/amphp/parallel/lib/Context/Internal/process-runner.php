<?php

namespace Phabel\Amp\Parallel\Context\Internal;

use Phabel\Amp\Parallel\Context\Process;
use Phabel\Amp\Parallel\Sync;
use Phabel\Amp\Promise;
use function Phabel\Amp\call;
use function Phabel\Amp\getCurrentTime;
\define("AMP_CONTEXT", "process");
\define("AMP_CONTEXT_ID", \getmypid());
// Doesn't exist in phpdbg...
if (\function_exists("cli_set_process_title")) {
    @\cli_set_process_title("amp-process");
}
$phabel_352ce55953ac90f8 = function () {
    $paths = [\dirname(__DIR__, 5) . "/autoload.php", \dirname(__DIR__, 3) . "/vendor/autoload.php"];
    foreach ($paths as $path) {
        if (\file_exists($path)) {
            $autoloadPath = $path;
            break;
        }
    }
    if (!isset($autoloadPath)) {
        \trigger_error("Could not locate autoload.php in any of the following files: " . \implode(", ", $paths), \E_USER_ERROR);
        exit(1);
    }
    require $autoloadPath;
};
$phabel_352ce55953ac90f8();
$phabel_94a45e49c4fa39bc = function () use($argc, $argv) {
    // Remove this scripts path from process arguments.
    --$argc;
    \array_shift($argv);
    if (!isset($argv[0])) {
        \trigger_error("No socket path provided", \E_USER_ERROR);
        exit(1);
    }
    // Remove socket path from process arguments.
    --$argc;
    $uri = \array_shift($argv);
    $key = "";
    // Read random key from STDIN and send back to parent over IPC socket to authenticate.
    do {
        if (($chunk = \fread(\STDIN, Process::KEY_LENGTH)) === \false || \feof(\STDIN)) {
            \trigger_error("Could not read key from parent", \E_USER_ERROR);
            exit(1);
        }
        $key .= $chunk;
    } while (\strlen($key) < Process::KEY_LENGTH);
    $connectStart = getCurrentTime();
    while (!($socket = \stream_socket_client($uri, $errno, $errstr, 5, \STREAM_CLIENT_CONNECT))) {
        if (getCurrentTime() < $connectStart + 5000) {
            // try for 5 seconds, after that the parent times out anyway
            \trigger_error("Could not connect to IPC socket", \E_USER_ERROR);
            exit(1);
        }
        \usleep(50 * 1000);
    }
    $channel = new Sync\ChannelledSocket($socket, $socket);
    try {
        Promise\wait($channel->send($key));
    } catch (\Exception $exception) {
        \trigger_error("Could not send key to parent", \E_USER_ERROR);
        exit(1);
    } catch (\Error $exception) {
        \trigger_error("Could not send key to parent", \E_USER_ERROR);
        exit(1);
    }
    try {
        if (!isset($argv[0])) {
            throw new \Error("No script path given");
        }
        if (!\is_file($argv[0])) {
            throw new \Error(\sprintf("No script found at '%s' (be sure to provide the full path to the script)", $argv[0]));
        }
        try {
            $phabel_98cd3053bed8de2a = function () use($argc, $argv) {
                $phabelReturn = (require $argv[0]);
                if (!\is_callable($phabelReturn)) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                // Using $argc so it is available to the required script.
                return $phabelReturn;
            };
            // Protect current scope by requiring script within another function.
            $callable = $phabel_98cd3053bed8de2a();
        } catch (\TypeError $exception) {
            throw new \Error(\sprintf("Script '%s' did not return a callable function", $argv[0]), 0, $exception);
        } catch (\ParseError $exception) {
            throw new \Error(\sprintf("Script '%s' contains a parse error: " . $exception->getMessage(), $argv[0]), 0, $exception);
        }
        $result = new Sync\ExitSuccess(Promise\wait(call($callable, $channel)));
    } catch (\Exception $exception) {
        $result = new Sync\ExitFailure($exception);
    } catch (\Error $exception) {
        $result = new Sync\ExitFailure($exception);
    }
    try {
        Promise\wait(call(function () use($channel, $result) {
            try {
                (yield $channel->send($result));
            } catch (Sync\SerializationException $exception) {
                // Serializing the result failed. Send the reason why.
                (yield $channel->send(new Sync\ExitFailure($exception)));
            }
        }));
    } catch (\Exception $exception) {
        \trigger_error("Could not send result to parent; be sure to shutdown the child before ending the parent", \E_USER_ERROR);
        exit(1);
    } catch (\Error $exception) {
        \trigger_error("Could not send result to parent; be sure to shutdown the child before ending the parent", \E_USER_ERROR);
        exit(1);
    }
};
$phabel_94a45e49c4fa39bc();
