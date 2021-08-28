<?php

namespace Phabel\Amp\Parallel\Worker\Internal;

use Phabel\Amp\Parallel\Sync;
use Phabel\Amp\Parallel\Worker;
use Phabel\Amp\Promise;
return function (Sync\Channel $channel) use($argc, $argv) {
    if (!\defined("AMP_WORKER")) {
        \define("AMP_WORKER", \AMP_CONTEXT);
    }
    if (isset($argv[2])) {
        if (!\is_file($argv[2])) {
            throw new \Error(\sprintf("No file found at bootstrap file path given '%s'", $argv[2]));
        }
        $phabel_c5d30c026e634fae = function () use($argc, $argv) {
            require $argv[2];
        };
        // Include file within closure to protect scope.
        $phabel_c5d30c026e634fae();
    }
    if (!isset($argv[1])) {
        throw new \Error("No environment class name provided");
    }
    $className = $argv[1];
    if (!\class_exists($className)) {
        throw new \Error(\sprintf("Invalid environment class name '%s'", $className));
    }
    if (!\is_subclass_of($className, Worker\Environment::class)) {
        throw new \Error(\sprintf("The class '%s' does not implement '%s'", $className, Worker\Environment::class));
    }
    $environment = new $className();
    $runner = new Worker\TaskRunner($channel, $environment);
    $phabelReturn = $runner->run();
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
};
