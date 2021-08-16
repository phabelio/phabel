<?php

namespace Phabel\Cli;

use Phabel\EventHandler as PhabelEventHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class EventHandler extends PhabelEventHandler {

    private OutputFormatter $outputFormatter;
    private ?ProgressBar $progress = null;
    /**
     * Progress bar getter
     *
     * @var null|(callable(int): ProgressBar)
     */
    private $getProgressBar = null;
    private int $count = 0;

    public function __construct(private LoggerInterface $logger, ?callable $getProgressBar)
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

    public function onBeginDirectoryTraversal(int $total): void
    {
        if ($this->getProgressBar
            && !$this->progress
        ) {
            $this->progress = ($this->getProgressBar)($total);
            $this->progress->setFormat($this->outputFormatter->format('<phabel>%message% <bold>%percent:3s%%</bold></phabel> (%current%/%max%)'));
        }
        if (!$this->count) {
            $message = 'Transpilation in progress...';
        } else {
            $secondary = $this->count === 1 ? 'secondary' : 'further';
            $message = "Applying $secondary transforms...";
        }
        $this->count++;
        if ($this->progress) {
            $this->progress->setMessage($message);
            $this->progress->clear();
            $this->progress->start();
        } else {
            $this->logger->debug($this->outputFormatter->format("<phabel>$message</phabel>"));
        }
    }
    public function onEndAstTraversal(string $file, int $iterations): void
    {
        $this->progress?->advance();
        $this->logger->debug($this->outputFormatter->format("<phabel>Transpiled $file in $iterations iterations!</phabel>"));
    }
    public function onEndDirectoryTraversal(): void
    {
        $this->progress?->finish();
        $this->logger->warning("");
    }
    public function onEnd(): void
    {
        $this->logger->warning($this->outputFormatter->format("<phabel>Done!</phabel>"));
    }
}