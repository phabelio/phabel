<?php

namespace Phabel\Composer;

use Phabel\Cli\Formatter;
use Phabel\EventHandler as PhabelEventHandler;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\ProgressBar;
use Throwable;

class EventHandler extends PhabelEventHandler
{
    /**
     * @var OutputFormatter
     */
    private $outputFormatter;
    /**
     * @var null|ProgressBar
     */
    private $progress = null;
    /**
     * Progress bar getter.
     *
     * @var null|(callable(int): ProgressBar)
     */
    private $getProgressBar = null;
    private int $count = 0;
    public function __construct(private $logger, ?callable $getProgressBar)
    {
        $this->outputFormatter = Formatter::getFormatter();
        $this->getProgressBar = $getProgressBar;
    }
    public function onBeginPluginGraphResolution(): void
    {
        $this->logger->debug($this->outputFormatter->format("<phabel>Plugin graph resolution in progress...</phabel>"));
    }
    public function onEndPluginGraphResolution(): void
    {
        $this->logger->debug($this->outputFormatter->format("<phabel>Finished plugin graph resolution!</phabel>"));
    }
    private function startProgressBar(string $message, int $total, int $workers = 1): void
    {
        if ($this->getProgressBar) {
            try {
                $this->progress = ($this->getProgressBar)($total);
                $this->progress->setFormat($this->outputFormatter->format('<phabel>%message% <bold>%percent:3s%%</bold></phabel> (%current%/%max%)'));
            } catch (\Throwable) {
            }
        }
        if ($this->progress) {
            if ($workers > 1) {
                $message .= " ({$workers} threads)";
            }
            $this->progress->setMessage($message);
            $this->progress->clear();
            $this->progress->start();
        } else {
            $this->logger->debug($this->outputFormatter->format("<phabel>{$message}</phabel>"));
        }
    }
    public function onBeginDirectoryTraversal(int $total, int $workers): void
    {
        if (!$this->count) {
            $message = 'Transpilation in progress...';
        } else {
            $secondary = $this->count === 1 ? 'covariance and contravariance' : 'further covariance and contravariance';
            $message = "Applying {$secondary} transforms...";
        }
        $this->count++;
        $this->startProgressBar($message, $total, $workers);
    }
    public function onEndAstTraversal(string $file, int|\Throwable $iterationsOrError): void
    {
        $this->progress?->advance();
        if ($iterationsOrError instanceof Throwable) {
            $this->logger->error($this->outputFormatter->format(PHP_EOL . "<error>{$iterationsOrError->getMessage()}!</error>"));
            $this->logger->debug($this->outputFormatter->format("<error>{$iterationsOrError}</error>"));
        } else {
            $this->logger->debug($this->outputFormatter->format("<phabel>Transpiled {$file} in {$iterationsOrError} iterations!</phabel>"));
        }
    }
    public function onEndDirectoryTraversal(): void
    {
        $this->progress?->finish();
        $this->logger->warning("");
    }
    public function onBeginClassGraphMerge(int $count): void
    {
        $this->startProgressBar("Merging class graphs...", $count);
    }
    public function onClassGraphMerged(): void
    {
        $this->progress?->advance();
        $this->logger->debug($this->outputFormatter->format("<phabel>Merged class graph!</phabel>"));
    }
    public function onEndClassGraphMerge(): void
    {
        $this->progress?->finish();
    }
    public function onEnd(): void
    {
        $this->progress?->clear();
        $this->logger->warning($this->outputFormatter->format('<phabel>Done!</phabel>'));
    }
}
