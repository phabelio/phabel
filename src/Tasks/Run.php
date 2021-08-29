<?php

namespace Phabel\Tasks;

use Phabel\Amp\Parallel\Worker\Environment;
use Phabel\Amp\Parallel\Worker\Task;
use Phabel\ExceptionWrapper;
use Phabel\Traverser;
class Run implements Task
{
    public function __construct(string $relative, string $input, string $output, $package, string $coverage)
    {
        if (!\is_null($package)) {
            if (!\is_string($package)) {
                if (!(\is_string($package) || \Phabel\Target\Php72\Polyfill::is_object($package) && \method_exists($package, '__toString') || (\is_bool($package) || \is_numeric($package)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($package) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($package) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $package = (string) $package;
            }
        }
        $this->coverage = $coverage;
        $this->package = $package;
        $this->output = $output;
        $this->input = $input;
        $this->relative = $relative;
    }
    private $coverage;
    private $package;
    private $output;
    private $input;
    private $relative;
    public function run(Environment $environment)
    {
        try {
            $_ = Traverser::startCoverage($this->coverage);
            /** @var Traverser */
            $traverser = $environment->get(Traverser::class);
            $traverser->setPackage($this->package);
            return $traverser->traverse($this->relative, $this->input, $this->output);
        } catch (\Throwable $e) {
            return new ExceptionWrapper($e);
        }
    }
}
