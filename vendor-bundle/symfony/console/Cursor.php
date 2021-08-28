<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console;

use Phabel\Symfony\Component\Console\Output\OutputInterface;
/**
 * @author Pierre du Plessis <pdples@gmail.com>
 */
final class Cursor
{
    private $output;
    private $input;
    public function __construct(OutputInterface $output, $input = null)
    {
        $this->output = $output;
        $this->input = isset($input) ? $input : (\defined('STDIN') ? \STDIN : \fopen('php://input', 'r+'));
    }
    public function moveUp($lines = 1)
    {
        if (!\is_int($lines)) {
            if (!(\is_bool($lines) || \is_numeric($lines))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($lines) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($lines) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $lines = (int) $lines;
            }
        }
        $this->output->write(\sprintf("\x1b[%dA", $lines));
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function moveDown($lines = 1)
    {
        if (!\is_int($lines)) {
            if (!(\is_bool($lines) || \is_numeric($lines))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($lines) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($lines) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $lines = (int) $lines;
            }
        }
        $this->output->write(\sprintf("\x1b[%dB", $lines));
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function moveRight($columns = 1)
    {
        if (!\is_int($columns)) {
            if (!(\is_bool($columns) || \is_numeric($columns))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($columns) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($columns) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $columns = (int) $columns;
            }
        }
        $this->output->write(\sprintf("\x1b[%dC", $columns));
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function moveLeft($columns = 1)
    {
        if (!\is_int($columns)) {
            if (!(\is_bool($columns) || \is_numeric($columns))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($columns) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($columns) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $columns = (int) $columns;
            }
        }
        $this->output->write(\sprintf("\x1b[%dD", $columns));
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function moveToColumn($column)
    {
        if (!\is_int($column)) {
            if (!(\is_bool($column) || \is_numeric($column))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($column) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($column) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $column = (int) $column;
            }
        }
        $this->output->write(\sprintf("\x1b[%dG", $column));
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function moveToPosition($column, $row)
    {
        if (!\is_int($column)) {
            if (!(\is_bool($column) || \is_numeric($column))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($column) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($column) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $column = (int) $column;
            }
        }
        if (!\is_int($row)) {
            if (!(\is_bool($row) || \is_numeric($row))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($row) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($row) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $row = (int) $row;
            }
        }
        $this->output->write(\sprintf("\x1b[%d;%dH", $row + 1, $column));
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function savePosition()
    {
        $this->output->write("\x1b7");
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function restorePosition()
    {
        $this->output->write("\x1b8");
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function hide()
    {
        $this->output->write("\x1b[?25l");
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function show()
    {
        $this->output->write("\x1b[?25h\x1b[?0c");
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Clears all the output from the current line.
     */
    public function clearLine()
    {
        $this->output->write("\x1b[2K");
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Clears all the output from the current line after the current position.
     */
    public function clearLineAfter()
    {
        $this->output->write("\x1b[K");
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Clears all the output from the cursors' current position to the end of the screen.
     */
    public function clearOutput()
    {
        $this->output->write("\x1b[0J");
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Clears the entire screen.
     */
    public function clearScreen()
    {
        $this->output->write("\x1b[2J");
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Returns the current cursor position as x,y coordinates.
     */
    public function getCurrentPosition()
    {
        static $isTtySupported;
        if (null === $isTtySupported && \function_exists('proc_open')) {
            $isTtySupported = (bool) @\proc_open('echo 1 >/dev/null', [['file', '/dev/tty', 'r'], ['file', '/dev/tty', 'w'], ['file', '/dev/tty', 'w']], $pipes);
        }
        if (!$isTtySupported) {
            $phabelReturn = [1, 1];
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $sttyMode = \shell_exec('stty -g');
        \shell_exec('stty -icanon -echo');
        @\fwrite($this->input, "\x1b[6n");
        $code = \trim(\fread($this->input, 1024));
        \shell_exec(\sprintf('stty %s', $sttyMode));
        \sscanf($code, "\x1b[%d;%dR", $row, $col);
        $phabelReturn = [$col, $row];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
