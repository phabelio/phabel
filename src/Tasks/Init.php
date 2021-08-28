<?php

namespace Phabel\Tasks;

use Phabel\Amp\Parallel\Worker\Environment;
use Phabel\Amp\Parallel\Worker\Task;
use Phabel\Exception;
use Phabel\PluginGraph\ResolvedGraph;
use Phabel\Traverser;
class Init implements Task
{
    public function __construct(private ResolvedGraph $graph)
    {
    }
    public function run(Environment $environment)
    {
        if (\function_exists("cli_set_process_title")) {
            try {
                @\cli_set_process_title("Phabel - PHP transpiler worker");
            } catch (\Exception $e) {
            } catch (\Error $e) {
            }
        }
        \set_error_handler(function ($errno = 0, $errstr = '', $errfile = '', $errline = -1) {
            if (!\is_int($errno)) {
                if (!(\is_bool($errno) || \is_numeric($errno))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($errno) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($errno) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $errno = (int) $errno;
            }
            if (!\is_string($errstr)) {
                if (!(\is_string($errstr) || \is_object($errstr) && \method_exists($errstr, '__toString') || (\is_bool($errstr) || \is_numeric($errstr)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($errstr) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($errstr) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $errstr = (string) $errstr;
            }
            if (!\is_string($errfile)) {
                if (!(\is_string($errfile) || \is_object($errfile) && \method_exists($errfile, '__toString') || (\is_bool($errfile) || \is_numeric($errfile)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($errfile) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($errfile) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $errfile = (string) $errfile;
            }
            if (!\is_int($errline)) {
                if (!(\is_bool($errline) || \is_numeric($errline))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($errline) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($errline) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $errline = (int) $errline;
            }
            // If error is suppressed with @, don't throw an exception
            if (\error_reporting() === 0) {
                $phabelReturn = \false;
                if (!\is_bool($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (bool) $phabelReturn;
                }
                return $phabelReturn;
            }
            throw new Exception($errstr, $errno, null, $errfile, $errline);
            throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        });
        $environment->set(Traverser::class, (new Traverser())->setGraph($this->graph));
    }
}
