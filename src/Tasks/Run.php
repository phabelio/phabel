<?php

namespace Phabel\Tasks;

use Phabel\Amp\Parallel\Worker\Environment;
use Phabel\Amp\Parallel\Worker\Task;
use Phabel\ExceptionWrapper;
use Phabel\Traverser;
class Run implements Task
{
    public function __construct(private $relative, private $input, private $output, private $package, private $coverage)
    {
        if (!\is_string($relative)) {
            if (!(\is_string($relative) || \is_object($relative) && \method_exists($relative, '__toString') || (\is_bool($relative) || \is_numeric($relative)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($relative) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($relative) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $relative = (string) $relative;
        }
        if (!\is_string($input)) {
            if (!(\is_string($input) || \is_object($input) && \method_exists($input, '__toString') || (\is_bool($input) || \is_numeric($input)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($input) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($input) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $input = (string) $input;
        }
        if (!\is_string($output)) {
            if (!(\is_string($output) || \is_object($output) && \method_exists($output, '__toString') || (\is_bool($output) || \is_numeric($output)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($output) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($output) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $output = (string) $output;
        }
        if (!\is_null($package)) {
            if (!\is_string($package)) {
                if (!(\is_string($package) || \is_object($package) && \method_exists($package, '__toString') || (\is_bool($package) || \is_numeric($package)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($package) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($package) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $package = (string) $package;
            }
        }
        if (!\is_string($coverage)) {
            if (!(\is_string($coverage) || \is_object($coverage) && \method_exists($coverage, '__toString') || (\is_bool($coverage) || \is_numeric($coverage)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #5 ($coverage) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($coverage) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $coverage = (string) $coverage;
        }
    }
    public function run(Environment $environment)
    {
        try {
            $_ = Traverser::startCoverage($this->coverage);
            /** @var Traverser */
            $traverser = $environment->get(Traverser::class);
            $traverser->setPackage($this->package);
            return $traverser->traverse($this->relative, $this->input, $this->output);
        } catch (\Exception $e) {
            return new ExceptionWrapper($e);
        } catch (\Error $e) {
            return new ExceptionWrapper($e);
        }
    }
}
