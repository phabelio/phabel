<?php

namespace Phabel\Cli;

use Phabel\EventHandler as PhabelEventHandler;
use Phabel\Psr\Log\LoggerInterface;
use Phabel\Symfony\Component\Console\Helper\ProgressBar;
use Phabel\Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;
class EventHandler extends PhabelEventHandler
{
    private $outputFormatter;
    private $progress = null;
    /**
     * Progress bar getter.
     *
     * @var null|(callable(int): ProgressBar)
     */
    private $getProgressBar = null;
    private $count = 0;
    /**
     * Create simple CLI-based preconfigured instance.
     *
     * @return self
     */
    public static function create() : self
    {
        $output = new ConsoleOutput();
        return new \Phabel\Cli\EventHandler(new \Phabel\Cli\SimpleConsoleLogger($output), function (int $max) use($output) : ProgressBar {
            return new ProgressBar($output, $max, -1);
        });
    }
    public function __construct(LoggerInterface $logger, $getProgressBar)
    {
        if (!(\is_callable($getProgressBar) || \is_null($getProgressBar))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($getProgressBar) must be of type ?callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($getProgressBar) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $this->logger = $logger;
        $this->outputFormatter = \Phabel\Cli\Formatter::getFormatter();
        $this->getProgressBar = $getProgressBar;
    }
    private $logger;
    public function onBeginPluginGraphResolution()
    {
        $this->logger->debug($this->outputFormatter->format("<phabel>Plugin graph resolution in progress...</phabel>"));
    }
    public function onEndPluginGraphResolution()
    {
        $this->logger->debug($this->outputFormatter->format("<phabel>Finished plugin graph resolution!</phabel>"));
    }
    private function startProgressBar(string $message, int $total, int $workers = 1)
    {
        if ($this->getProgressBar) {
            $this->progress = ($this->getProgressBar)($total);
            $this->progress->setFormat($this->outputFormatter->format('<phabel>%message% <bold>%percent:3s%%</bold></phabel> (%current%/%max%)'));
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
    public function onBeginDirectoryTraversal(int $total, int $workers)
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
    public function onEndAstTraversal(string $file, $iterationsOrError)
    {
        if (!$iterationsOrError instanceof \Throwable) {
            if (!\is_int($iterationsOrError)) {
                if (!(\is_bool($iterationsOrError) || \is_numeric($iterationsOrError))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($iterationsOrError) must be of type Throwable|int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($iterationsOrError) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $iterationsOrError = (int) $iterationsOrError;
            }
        }
        ($this->progress ?? \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->advance();
        if ($iterationsOrError instanceof Throwable) {
            $this->logger->error($this->outputFormatter->format(\PHP_EOL . "<error>{$iterationsOrError->getMessage()}!</error>"));
            $this->logger->debug($this->outputFormatter->format("<error>{$iterationsOrError}</error>"));
        } else {
            $this->logger->debug($this->outputFormatter->format("<phabel>Transpiled {$file} in {$iterationsOrError} iterations!</phabel>"));
        }
    }
    public function onEndDirectoryTraversal()
    {
        ($this->progress ?? \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->finish();
        $this->logger->warning("");
    }
    public function onBeginClassGraphMerge(int $count)
    {
        $this->startProgressBar("Merging class graphs...", $count);
    }
    public function onClassGraphMerged()
    {
        ($this->progress ?? \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->advance();
        $this->logger->debug($this->outputFormatter->format("<phabel>Merged class graph!</phabel>"));
    }
    public function onEndClassGraphMerge()
    {
        ($this->progress ?? \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->finish();
    }
    public function onEnd()
    {
        ($this->progress ?? \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->clear();
        $this->logger->warning($this->outputFormatter->format('<phabel>Done!</phabel>'));
    }
}
