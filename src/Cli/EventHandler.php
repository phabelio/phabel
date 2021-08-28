<?php

namespace Phabel\Cli;

use Phabel\EventHandler as PhabelEventHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

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
    public static function create()
    {
        $output = new ConsoleOutput();
        $phabelReturn = new EventHandler(new SimpleConsoleLogger($output), function ($max) use ($output) {
            if (!\is_int($max)) {
                if (!(\is_bool($max) || \is_numeric($max))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($max) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($max) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $max = (int) $max;
            }
            $phabelReturn = new ProgressBar($output, $max, -1);
            if (!$phabelReturn instanceof ProgressBar) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ProgressBar, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        });
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function __construct(private LoggerInterface $logger, $getProgressBar)
    {
        if (!(\is_callable($getProgressBar) || \is_null($getProgressBar))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($getProgressBar) must be of type ?callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($getProgressBar) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $this->outputFormatter = Formatter::getFormatter();
        $this->getProgressBar = $getProgressBar;
    }
    public function onBeginPluginGraphResolution()
    {
        $this->logger->debug($this->outputFormatter->format("<phabel>Plugin graph resolution in progress...</phabel>"));
    }
    public function onEndPluginGraphResolution()
    {
        $this->logger->debug($this->outputFormatter->format("<phabel>Finished plugin graph resolution!</phabel>"));
    }
    private function startProgressBar($message, $total, $workers = 1)
    {
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $message = (string) $message;
        }
        if (!\is_int($total)) {
            if (!(\is_bool($total) || \is_numeric($total))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($total) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($total) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $total = (int) $total;
        }
        if (!\is_int($workers)) {
            if (!(\is_bool($workers) || \is_numeric($workers))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($workers) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($workers) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $workers = (int) $workers;
        }
        if ($this->getProgressBar) {
            $phabel_11f8432a35865732 = $this->getProgressBar;
            $this->progress = $phabel_11f8432a35865732($total);
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
    public function onBeginDirectoryTraversal($total, $workers)
    {
        if (!\is_int($total)) {
            if (!(\is_bool($total) || \is_numeric($total))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($total) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($total) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $total = (int) $total;
        }
        if (!\is_int($workers)) {
            if (!(\is_bool($workers) || \is_numeric($workers))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($workers) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($workers) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $workers = (int) $workers;
        }
        if (!$this->count) {
            $message = 'Transpilation in progress...';
        } else {
            $secondary = $this->count === 1 ? 'covariance and contravariance' : 'further covariance and contravariance';
            $message = "Applying {$secondary} transforms...";
        }
        $this->count++;
        $this->startProgressBar($message, $total, $workers);
    }
    public function onEndAstTraversal($file, $iterationsOrError)
    {
        if (!\is_string($file)) {
            if (!(\is_string($file) || \is_object($file) && \method_exists($file, '__toString') || (\is_bool($file) || \is_numeric($file)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($file) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $file = (string) $file;
        }
        if (!($iterationsOrError instanceof \Exception || $iterationsOrError instanceof \Error)) {
            if (!\is_int($iterationsOrError)) {
                if (!(\is_bool($iterationsOrError) || \is_numeric($iterationsOrError))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($iterationsOrError) must be of type Throwable|int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($iterationsOrError) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $iterationsOrError = (int) $iterationsOrError;
            }
        }
        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->progress) ? $this->progress : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->advance();
        if ($iterationsOrError instanceof \Exception || $iterationsOrError instanceof \Error) {
            $this->logger->error($this->outputFormatter->format(PHP_EOL . "<error>{$iterationsOrError->getMessage()}!</error>"));
            $this->logger->debug($this->outputFormatter->format("<error>{$iterationsOrError}</error>"));
        } else {
            $this->logger->debug($this->outputFormatter->format("<phabel>Transpiled {$file} in {$iterationsOrError} iterations!</phabel>"));
        }
    }
    public function onEndDirectoryTraversal()
    {
        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->progress) ? $this->progress : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->finish();
        $this->logger->warning("");
    }
    public function onBeginClassGraphMerge($count)
    {
        if (!\is_int($count)) {
            if (!(\is_bool($count) || \is_numeric($count))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($count) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($count) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $count = (int) $count;
        }
        $this->startProgressBar("Merging class graphs...", $count);
    }
    public function onClassGraphMerged()
    {
        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->progress) ? $this->progress : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->advance();
        $this->logger->debug($this->outputFormatter->format("<phabel>Merged class graph!</phabel>"));
    }
    public function onEndClassGraphMerge()
    {
        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->progress) ? $this->progress : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->finish();
    }
    public function onEnd()
    {
        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->progress) ? $this->progress : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->clear();
        $this->logger->warning($this->outputFormatter->format('<phabel>Done!</phabel>'));
    }
}
