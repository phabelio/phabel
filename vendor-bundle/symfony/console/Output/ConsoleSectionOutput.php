<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Output;

use Phabel\Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Phabel\Symfony\Component\Console\Helper\Helper;
use Phabel\Symfony\Component\Console\Terminal;
/**
 * @author Pierre du Plessis <pdples@gmail.com>
 * @author Gabriel Ostrolucký <gabriel.ostrolucky@gmail.com>
 */
class ConsoleSectionOutput extends StreamOutput
{
    private $content = [];
    private $lines = 0;
    private $sections;
    private $terminal;
    /**
     * @param resource               $stream
     * @param ConsoleSectionOutput[] $sections
     */
    public function __construct($stream, &$sections, $verbosity, $decorated, OutputFormatterInterface $formatter)
    {
        if (!\is_array($sections)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($sections) must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($sections) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_int($verbosity)) {
            if (!(\is_bool($verbosity) || \is_numeric($verbosity))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($verbosity) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($verbosity) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $verbosity = (int) $verbosity;
            }
        }
        if (!\is_bool($decorated)) {
            if (!(\is_bool($decorated) || \is_numeric($decorated) || \is_string($decorated))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($decorated) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($decorated) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $decorated = (bool) $decorated;
            }
        }
        parent::__construct($stream, $verbosity, $decorated, $formatter);
        \array_unshift($sections, $this);
        $this->sections =& $sections;
        $this->terminal = new Terminal();
    }
    /**
     * Clears previous output for this section.
     *
     * @param int $lines Number of lines to clear. If null, then the entire output of this section is cleared
     */
    public function clear($lines = null)
    {
        if (!\is_null($lines)) {
            if (!\is_int($lines)) {
                if (!(\is_bool($lines) || \is_numeric($lines))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($lines) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($lines) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $lines = (int) $lines;
                }
            }
        }
        if (empty($this->content) || !$this->isDecorated()) {
            return;
        }
        if ($lines) {
            \array_splice($this->content, -($lines * 2));
            // Multiply lines by 2 to cater for each new line added between content
        } else {
            $lines = $this->lines;
            $this->content = [];
        }
        $this->lines -= $lines;
        parent::doWrite($this->popStreamContentUntilCurrentSection($lines), \false);
    }
    /**
     * Overwrites the previous output with a new message.
     *
     * @param array|string $message
     */
    public function overwrite($message)
    {
        $this->clear();
        $this->writeln($message);
    }
    public function getContent()
    {
        $phabelReturn = \implode('', $this->content);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * @internal
     */
    public function addContent($input)
    {
        if (!\is_string($input)) {
            if (!(\is_string($input) || \is_object($input) && \method_exists($input, '__toString') || (\is_bool($input) || \is_numeric($input)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($input) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($input) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $input = (string) $input;
            }
        }
        foreach (\explode(\PHP_EOL, $input) as $lineContent) {
            $this->lines += \ceil($this->getDisplayLength($lineContent) / $this->terminal->getWidth()) ?: 1;
            $this->content[] = $lineContent;
            $this->content[] = \PHP_EOL;
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function doWrite($message, $newline)
    {
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        if (!\is_bool($newline)) {
            if (!(\is_bool($newline) || \is_numeric($newline) || \is_string($newline))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($newline) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($newline) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $newline = (bool) $newline;
            }
        }
        if (!$this->isDecorated()) {
            parent::doWrite($message, $newline);
            return;
        }
        $erasedContent = $this->popStreamContentUntilCurrentSection();
        $this->addContent($message);
        parent::doWrite($message, \true);
        parent::doWrite($erasedContent, \false);
    }
    /**
     * At initial stage, cursor is at the end of stream output. This method makes cursor crawl upwards until it hits
     * current section. Then it erases content it crawled through. Optionally, it erases part of current section too.
     */
    private function popStreamContentUntilCurrentSection($numberOfLinesToClearFromCurrentSection = 0)
    {
        if (!\is_int($numberOfLinesToClearFromCurrentSection)) {
            if (!(\is_bool($numberOfLinesToClearFromCurrentSection) || \is_numeric($numberOfLinesToClearFromCurrentSection))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($numberOfLinesToClearFromCurrentSection) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($numberOfLinesToClearFromCurrentSection) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $numberOfLinesToClearFromCurrentSection = (int) $numberOfLinesToClearFromCurrentSection;
            }
        }
        $numberOfLinesToClear = $numberOfLinesToClearFromCurrentSection;
        $erasedContent = [];
        foreach ($this->sections as $section) {
            if ($section === $this) {
                break;
            }
            $numberOfLinesToClear += $section->lines;
            $erasedContent[] = $section->getContent();
        }
        if ($numberOfLinesToClear > 0) {
            // move cursor up n lines
            parent::doWrite(\sprintf("\x1b[%dA", $numberOfLinesToClear), \false);
            // erase to end of screen
            parent::doWrite("\x1b[0J", \false);
        }
        $phabelReturn = \implode('', \array_reverse($erasedContent));
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    private function getDisplayLength($text)
    {
        if (!\is_string($text)) {
            if (!(\is_string($text) || \is_object($text) && \method_exists($text, '__toString') || (\is_bool($text) || \is_numeric($text)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($text) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($text) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $text = (string) $text;
            }
        }
        $phabelReturn = Helper::width(Helper::removeDecoration($this->getFormatter(), \str_replace("\t", '        ', $text)));
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
