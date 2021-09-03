<?php

namespace Phabel\Tasks;

use Phabel\Amp\Parallel\Worker\Environment;
use Phabel\Amp\Parallel\Worker\Task;
use Phabel\ExceptionWrapper;
use Phabel\Traverser;
class Run implements Task
{
    public function __construct(string $relative, string $input, string $output, ?string $package, string $coverage)
    {
        $this->coverage = $coverage;
        $this->package = $package;
        $this->output = $output;
        $this->input = $input;
        $this->relative = $relative;
    }
    private string $coverage;
    private ?string $package;
    private string $output;
    private string $input;
    private string $relative;
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
