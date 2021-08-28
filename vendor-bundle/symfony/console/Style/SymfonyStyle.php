<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Style;

use Phabel\Symfony\Component\Console\Exception\InvalidArgumentException;
use Phabel\Symfony\Component\Console\Exception\RuntimeException;
use Phabel\Symfony\Component\Console\Formatter\OutputFormatter;
use Phabel\Symfony\Component\Console\Helper\Helper;
use Phabel\Symfony\Component\Console\Helper\ProgressBar;
use Phabel\Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Phabel\Symfony\Component\Console\Helper\Table;
use Phabel\Symfony\Component\Console\Helper\TableCell;
use Phabel\Symfony\Component\Console\Helper\TableSeparator;
use Phabel\Symfony\Component\Console\Input\InputInterface;
use Phabel\Symfony\Component\Console\Output\OutputInterface;
use Phabel\Symfony\Component\Console\Output\TrimmedBufferOutput;
use Phabel\Symfony\Component\Console\Question\ChoiceQuestion;
use Phabel\Symfony\Component\Console\Question\ConfirmationQuestion;
use Phabel\Symfony\Component\Console\Question\Question;
use Phabel\Symfony\Component\Console\Terminal;
/**
 * Output decorator helpers for the Symfony Style Guide.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SymfonyStyle extends OutputStyle
{
    const MAX_LINE_LENGTH = 120;
    private $input;
    private $questionHelper;
    private $progressBar;
    private $lineLength;
    private $bufferedOutput;
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->bufferedOutput = new TrimmedBufferOutput(\DIRECTORY_SEPARATOR === '\\' ? 4 : 2, $output->getVerbosity(), \false, clone $output->getFormatter());
        // Windows cmd wraps lines as soon as the terminal width is reached, whether there are following chars or not.
        $width = (new Terminal())->getWidth() ?: self::MAX_LINE_LENGTH;
        $this->lineLength = \min($width - (int) (\DIRECTORY_SEPARATOR === '\\'), self::MAX_LINE_LENGTH);
        parent::__construct($output);
    }
    /**
     * Formats a message as a block of text.
     *
     * @param string|array $messages The message to write in the block
     */
    public function block($messages, $type = null, $style = null, $prefix = ' ', $padding = \false, $escape = \true)
    {
        if (!\is_null($type)) {
            if (!\is_string($type)) {
                if (!(\is_string($type) || \is_object($type) && \method_exists($type, '__toString') || (\is_bool($type) || \is_numeric($type)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($type) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $type = (string) $type;
                }
            }
        }
        if (!\is_null($style)) {
            if (!\is_string($style)) {
                if (!(\is_string($style) || \is_object($style) && \method_exists($style, '__toString') || (\is_bool($style) || \is_numeric($style)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($style) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($style) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $style = (string) $style;
                }
            }
        }
        if (!\is_string($prefix)) {
            if (!(\is_string($prefix) || \is_object($prefix) && \method_exists($prefix, '__toString') || (\is_bool($prefix) || \is_numeric($prefix)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($prefix) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($prefix) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $prefix = (string) $prefix;
            }
        }
        if (!\is_bool($padding)) {
            if (!(\is_bool($padding) || \is_numeric($padding) || \is_string($padding))) {
                throw new \TypeError(__METHOD__ . '(): Argument #5 ($padding) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($padding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $padding = (bool) $padding;
            }
        }
        if (!\is_bool($escape)) {
            if (!(\is_bool($escape) || \is_numeric($escape) || \is_string($escape))) {
                throw new \TypeError(__METHOD__ . '(): Argument #6 ($escape) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($escape) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $escape = (bool) $escape;
            }
        }
        $messages = \is_array($messages) ? \array_values($messages) : [$messages];
        $this->autoPrependBlock();
        $this->writeln($this->createBlock($messages, $type, $style, $prefix, $padding, $escape));
        $this->newLine();
    }
    /**
     * {@inheritdoc}
     */
    public function title($message)
    {
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        $this->autoPrependBlock();
        $this->writeln([\sprintf('<comment>%s</>', OutputFormatter::escapeTrailingBackslash($message)), \sprintf('<comment>%s</>', \str_repeat('=', Helper::width(Helper::removeDecoration($this->getFormatter(), $message))))]);
        $this->newLine();
    }
    /**
     * {@inheritdoc}
     */
    public function section($message)
    {
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        $this->autoPrependBlock();
        $this->writeln([\sprintf('<comment>%s</>', OutputFormatter::escapeTrailingBackslash($message)), \sprintf('<comment>%s</>', \str_repeat('-', Helper::width(Helper::removeDecoration($this->getFormatter(), $message))))]);
        $this->newLine();
    }
    /**
     * {@inheritdoc}
     */
    public function listing(array $elements)
    {
        $this->autoPrependText();
        $elements = \array_map(function ($element) {
            return \sprintf(' * %s', $element);
        }, $elements);
        $this->writeln($elements);
        $this->newLine();
    }
    /**
     * {@inheritdoc}
     */
    public function text($message)
    {
        $this->autoPrependText();
        $messages = \is_array($message) ? \array_values($message) : [$message];
        foreach ($messages as $message) {
            $this->writeln(\sprintf(' %s', $message));
        }
    }
    /**
     * Formats a command comment.
     *
     * @param string|array $message
     */
    public function comment($message)
    {
        $this->block($message, null, null, '<fg=default;bg=default> // </>', \false, \false);
    }
    /**
     * {@inheritdoc}
     */
    public function success($message)
    {
        $this->block($message, 'OK', 'fg=black;bg=green', ' ', \true);
    }
    /**
     * {@inheritdoc}
     */
    public function error($message)
    {
        $this->block($message, 'ERROR', 'fg=white;bg=red', ' ', \true);
    }
    /**
     * {@inheritdoc}
     */
    public function warning($message)
    {
        $this->block($message, 'WARNING', 'fg=black;bg=yellow', ' ', \true);
    }
    /**
     * {@inheritdoc}
     */
    public function note($message)
    {
        $this->block($message, 'NOTE', 'fg=yellow', ' ! ');
    }
    /**
     * Formats an info message.
     *
     * @param string|array $message
     */
    public function info($message)
    {
        $this->block($message, 'INFO', 'fg=green', ' ', \true);
    }
    /**
     * {@inheritdoc}
     */
    public function caution($message)
    {
        $this->block($message, 'CAUTION', 'fg=white;bg=red', ' ! ', \true);
    }
    /**
     * {@inheritdoc}
     */
    public function table(array $headers, array $rows)
    {
        $style = clone Table::getStyleDefinition('symfony-style-guide');
        $style->setCellHeaderFormat('<info>%s</info>');
        $table = new Table($this);
        $table->setHeaders($headers);
        $table->setRows($rows);
        $table->setStyle($style);
        $table->render();
        $this->newLine();
    }
    /**
     * Formats a horizontal table.
     */
    public function horizontalTable(array $headers, array $rows)
    {
        $style = clone Table::getStyleDefinition('symfony-style-guide');
        $style->setCellHeaderFormat('<info>%s</info>');
        $table = new Table($this);
        $table->setHeaders($headers);
        $table->setRows($rows);
        $table->setStyle($style);
        $table->setHorizontal(\true);
        $table->render();
        $this->newLine();
    }
    /**
     * Formats a list of key/value horizontally.
     *
     * Each row can be one of:
     * * 'A title'
     * * ['key' => 'value']
     * * new TableSeparator()
     *
     * @param string|array|TableSeparator ...$list
     */
    public function definitionList(...$list)
    {
        $style = clone Table::getStyleDefinition('symfony-style-guide');
        $style->setCellHeaderFormat('<info>%s</info>');
        $table = new Table($this);
        $headers = [];
        $row = [];
        foreach ($list as $value) {
            if ($value instanceof TableSeparator) {
                $headers[] = $value;
                $row[] = $value;
                continue;
            }
            if (\is_string($value)) {
                $headers[] = new TableCell($value, ['colspan' => 2]);
                $row[] = null;
                continue;
            }
            if (!\is_array($value)) {
                throw new InvalidArgumentException('Value should be an array, string, or an instance of TableSeparator.');
            }
            $headers[] = \key($value);
            $row[] = \current($value);
        }
        $table->setHeaders($headers);
        $table->setRows([$row]);
        $table->setHorizontal();
        $table->setStyle($style);
        $table->render();
        $this->newLine();
    }
    /**
     * {@inheritdoc}
     */
    public function ask($question, $default = null, callable $validator = null)
    {
        if (!\is_string($question)) {
            if (!(\is_string($question) || \is_object($question) && \method_exists($question, '__toString') || (\is_bool($question) || \is_numeric($question)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($question) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($question) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $question = (string) $question;
            }
        }
        if (!\is_null($default)) {
            if (!\is_string($default)) {
                if (!(\is_string($default) || \is_object($default) && \method_exists($default, '__toString') || (\is_bool($default) || \is_numeric($default)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($default) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($default) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $default = (string) $default;
                }
            }
        }
        $question = new Question($question, $default);
        $question->setValidator($validator);
        return $this->askQuestion($question);
    }
    /**
     * {@inheritdoc}
     */
    public function askHidden($question, callable $validator = null)
    {
        if (!\is_string($question)) {
            if (!(\is_string($question) || \is_object($question) && \method_exists($question, '__toString') || (\is_bool($question) || \is_numeric($question)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($question) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($question) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $question = (string) $question;
            }
        }
        $question = new Question($question);
        $question->setHidden(\true);
        $question->setValidator($validator);
        return $this->askQuestion($question);
    }
    /**
     * {@inheritdoc}
     */
    public function confirm($question, $default = \true)
    {
        if (!\is_string($question)) {
            if (!(\is_string($question) || \is_object($question) && \method_exists($question, '__toString') || (\is_bool($question) || \is_numeric($question)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($question) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($question) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $question = (string) $question;
            }
        }
        if (!\is_bool($default)) {
            if (!(\is_bool($default) || \is_numeric($default) || \is_string($default))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($default) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($default) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $default = (bool) $default;
            }
        }
        return $this->askQuestion(new ConfirmationQuestion($question, $default));
    }
    /**
     * {@inheritdoc}
     */
    public function choice($question, array $choices, $default = null)
    {
        if (!\is_string($question)) {
            if (!(\is_string($question) || \is_object($question) && \method_exists($question, '__toString') || (\is_bool($question) || \is_numeric($question)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($question) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($question) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $question = (string) $question;
            }
        }
        if (null !== $default) {
            $values = \array_flip($choices);
            $default = isset($values[$default]) ? $values[$default] : $default;
        }
        return $this->askQuestion(new ChoiceQuestion($question, $choices, $default));
    }
    /**
     * {@inheritdoc}
     */
    public function progressStart($max = 0)
    {
        if (!\is_int($max)) {
            if (!(\is_bool($max) || \is_numeric($max))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($max) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($max) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $max = (int) $max;
            }
        }
        $this->progressBar = $this->createProgressBar($max);
        $this->progressBar->start();
    }
    /**
     * {@inheritdoc}
     */
    public function progressAdvance($step = 1)
    {
        if (!\is_int($step)) {
            if (!(\is_bool($step) || \is_numeric($step))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($step) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($step) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $step = (int) $step;
            }
        }
        $this->getProgressBar()->advance($step);
    }
    /**
     * {@inheritdoc}
     */
    public function progressFinish()
    {
        $this->getProgressBar()->finish();
        $this->newLine(2);
        $this->progressBar = null;
    }
    /**
     * {@inheritdoc}
     */
    public function createProgressBar($max = 0)
    {
        if (!\is_int($max)) {
            if (!(\is_bool($max) || \is_numeric($max))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($max) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($max) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $max = (int) $max;
            }
        }
        $progressBar = parent::createProgressBar($max);
        if ('\\' !== \DIRECTORY_SEPARATOR || 'Hyper' === \getenv('TERM_PROGRAM')) {
            $progressBar->setEmptyBarCharacter('░');
            // light shade character \u2591
            $progressBar->setProgressCharacter('');
            $progressBar->setBarCharacter('▓');
            // dark shade character \u2593
        }
        return $progressBar;
    }
    /**
     * @return mixed
     */
    public function askQuestion(Question $question)
    {
        if ($this->input->isInteractive()) {
            $this->autoPrependBlock();
        }
        if (!$this->questionHelper) {
            $this->questionHelper = new SymfonyQuestionHelper();
        }
        $answer = $this->questionHelper->ask($this->input, $this, $question);
        if ($this->input->isInteractive()) {
            $this->newLine();
            $this->bufferedOutput->write("\n");
        }
        return $answer;
    }
    /**
     * {@inheritdoc}
     */
    public function writeln($messages, $type = self::OUTPUT_NORMAL)
    {
        if (!\is_int($type)) {
            if (!(\is_bool($type) || \is_numeric($type))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($type) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $type = (int) $type;
            }
        }
        if (!\is_iterable($messages)) {
            $messages = [$messages];
        }
        foreach ($messages as $message) {
            parent::writeln($message, $type);
            $this->writeBuffer($message, \true, $type);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function write($messages, $newline = \false, $type = self::OUTPUT_NORMAL)
    {
        if (!\is_bool($newline)) {
            if (!(\is_bool($newline) || \is_numeric($newline) || \is_string($newline))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($newline) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($newline) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $newline = (bool) $newline;
            }
        }
        if (!\is_int($type)) {
            if (!(\is_bool($type) || \is_numeric($type))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($type) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $type = (int) $type;
            }
        }
        if (!\is_iterable($messages)) {
            $messages = [$messages];
        }
        foreach ($messages as $message) {
            parent::write($message, $newline, $type);
            $this->writeBuffer($message, $newline, $type);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function newLine($count = 1)
    {
        if (!\is_int($count)) {
            if (!(\is_bool($count) || \is_numeric($count))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($count) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($count) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $count = (int) $count;
            }
        }
        parent::newLine($count);
        $this->bufferedOutput->write(\str_repeat("\n", $count));
    }
    /**
     * Returns a new instance which makes use of stderr if available.
     *
     * @return self
     */
    public function getErrorStyle()
    {
        return new self($this->input, $this->getErrorOutput());
    }
    private function getProgressBar()
    {
        if (!$this->progressBar) {
            throw new RuntimeException('The ProgressBar is not started.');
        }
        $phabelReturn = $this->progressBar;
        if (!$phabelReturn instanceof ProgressBar) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ProgressBar, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function autoPrependBlock()
    {
        $chars = \substr(\str_replace(\PHP_EOL, "\n", $this->bufferedOutput->fetch()), -2);
        if (!isset($chars[0])) {
            $this->newLine();
            //empty history, so we should start with a new line.
            return;
        }
        //Prepend new line for each non LF chars (This means no blank line was output before)
        $this->newLine(2 - \substr_count($chars, "\n"));
    }
    private function autoPrependText()
    {
        $fetched = $this->bufferedOutput->fetch();
        //Prepend new line if last char isn't EOL:
        if (!\str_ends_with($fetched, "\n")) {
            $this->newLine();
        }
    }
    private function writeBuffer($message, $newLine, $type)
    {
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        if (!\is_bool($newLine)) {
            if (!(\is_bool($newLine) || \is_numeric($newLine) || \is_string($newLine))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($newLine) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($newLine) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $newLine = (bool) $newLine;
            }
        }
        if (!\is_int($type)) {
            if (!(\is_bool($type) || \is_numeric($type))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($type) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $type = (int) $type;
            }
        }
        // We need to know if the last chars are PHP_EOL
        $this->bufferedOutput->write($message, $newLine, $type);
    }
    private function createBlock($messages, $type = null, $style = null, $prefix = ' ', $padding = \false, $escape = \false)
    {
        if (!(\is_array($messages) || $messages instanceof \Traversable)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($messages) must be of type iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($messages) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_null($type)) {
            if (!\is_string($type)) {
                if (!(\is_string($type) || \is_object($type) && \method_exists($type, '__toString') || (\is_bool($type) || \is_numeric($type)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($type) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $type = (string) $type;
                }
            }
        }
        if (!\is_null($style)) {
            if (!\is_string($style)) {
                if (!(\is_string($style) || \is_object($style) && \method_exists($style, '__toString') || (\is_bool($style) || \is_numeric($style)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($style) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($style) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $style = (string) $style;
                }
            }
        }
        if (!\is_string($prefix)) {
            if (!(\is_string($prefix) || \is_object($prefix) && \method_exists($prefix, '__toString') || (\is_bool($prefix) || \is_numeric($prefix)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($prefix) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($prefix) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $prefix = (string) $prefix;
            }
        }
        if (!\is_bool($padding)) {
            if (!(\is_bool($padding) || \is_numeric($padding) || \is_string($padding))) {
                throw new \TypeError(__METHOD__ . '(): Argument #5 ($padding) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($padding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $padding = (bool) $padding;
            }
        }
        if (!\is_bool($escape)) {
            if (!(\is_bool($escape) || \is_numeric($escape) || \is_string($escape))) {
                throw new \TypeError(__METHOD__ . '(): Argument #6 ($escape) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($escape) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $escape = (bool) $escape;
            }
        }
        $indentLength = 0;
        $prefixLength = Helper::width(Helper::removeDecoration($this->getFormatter(), $prefix));
        $lines = [];
        if (null !== $type) {
            $type = \sprintf('[%s] ', $type);
            $indentLength = \strlen($type);
            $lineIndentation = \str_repeat(' ', $indentLength);
        }
        // wrap and add newlines for each element
        foreach ($messages as $key => $message) {
            if ($escape) {
                $message = OutputFormatter::escape($message);
            }
            $decorationLength = Helper::width($message) - Helper::width(Helper::removeDecoration($this->getFormatter(), $message));
            $messageLineLength = \min($this->lineLength - $prefixLength - $indentLength + $decorationLength, $this->lineLength);
            $messageLines = \explode(\PHP_EOL, \wordwrap($message, $messageLineLength, \PHP_EOL, \true));
            foreach ($messageLines as $messageLine) {
                $lines[] = $messageLine;
            }
            if (\count($messages) > 1 && $key < \count($messages) - 1) {
                $lines[] = '';
            }
        }
        $firstLineIndex = 0;
        if ($padding && $this->isDecorated()) {
            $firstLineIndex = 1;
            \array_unshift($lines, '');
            $lines[] = '';
        }
        foreach ($lines as $i => &$line) {
            if (null !== $type) {
                $line = $firstLineIndex === $i ? $type . $line : $lineIndentation . $line;
            }
            $line = $prefix . $line;
            $line .= \str_repeat(' ', \max($this->lineLength - Helper::width(Helper::removeDecoration($this->getFormatter(), $line)), 0));
            if ($style) {
                $line = \sprintf('<%s>%s</>', $style, $line);
            }
        }
        $phabelReturn = $lines;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
