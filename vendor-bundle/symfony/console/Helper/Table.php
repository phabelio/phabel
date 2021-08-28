<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Helper;

use Phabel\Symfony\Component\Console\Exception\InvalidArgumentException;
use Phabel\Symfony\Component\Console\Exception\RuntimeException;
use Phabel\Symfony\Component\Console\Formatter\OutputFormatter;
use Phabel\Symfony\Component\Console\Formatter\WrappableOutputFormatterInterface;
use Phabel\Symfony\Component\Console\Output\ConsoleSectionOutput;
use Phabel\Symfony\Component\Console\Output\OutputInterface;
/**
 * Provides helpers to display a table.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Саша Стаменковић <umpirsky@gmail.com>
 * @author Abdellatif Ait boudad <a.aitboudad@gmail.com>
 * @author Max Grigorian <maxakawizard@gmail.com>
 * @author Dany Maillard <danymaillard93b@gmail.com>
 */
class Table
{
    const SEPARATOR_TOP = 0;
    const SEPARATOR_TOP_BOTTOM = 1;
    const SEPARATOR_MID = 2;
    const SEPARATOR_BOTTOM = 3;
    const BORDER_OUTSIDE = 0;
    const BORDER_INSIDE = 1;
    private $headerTitle;
    private $footerTitle;
    /**
     * Table headers.
     */
    private $headers = [];
    /**
     * Table rows.
     */
    private $rows = [];
    private $horizontal = \false;
    /**
     * Column widths cache.
     */
    private $effectiveColumnWidths = [];
    /**
     * Number of columns cache.
     *
     * @var int
     */
    private $numberOfColumns;
    /**
     * @var OutputInterface
     */
    private $output;
    /**
     * @var TableStyle
     */
    private $style;
    /**
     * @var array
     */
    private $columnStyles = [];
    /**
     * User set column widths.
     *
     * @var array
     */
    private $columnWidths = [];
    private $columnMaxWidths = [];
    private static $styles;
    private $rendered = \false;
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
        if (!self::$styles) {
            self::$styles = self::initStyles();
        }
        $this->setStyle('default');
    }
    /**
     * Sets a style definition.
     */
    public static function setStyleDefinition($name, TableStyle $style)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!self::$styles) {
            self::$styles = self::initStyles();
        }
        self::$styles[$name] = $style;
    }
    /**
     * Gets a style definition by name.
     *
     * @return TableStyle
     */
    public static function getStyleDefinition($name)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!self::$styles) {
            self::$styles = self::initStyles();
        }
        if (isset(self::$styles[$name])) {
            return self::$styles[$name];
        }
        throw new InvalidArgumentException(\sprintf('Style "%s" is not defined.', $name));
    }
    /**
     * Sets table style.
     *
     * @param TableStyle|string $name The style name or a TableStyle instance
     *
     * @return $this
     */
    public function setStyle($name)
    {
        $this->style = $this->resolveStyle($name);
        return $this;
    }
    /**
     * Gets the current table style.
     *
     * @return TableStyle
     */
    public function getStyle()
    {
        return $this->style;
    }
    /**
     * Sets table column style.
     *
     * @param TableStyle|string $name The style name or a TableStyle instance
     *
     * @return $this
     */
    public function setColumnStyle($columnIndex, $name)
    {
        if (!\is_int($columnIndex)) {
            if (!(\is_bool($columnIndex) || \is_numeric($columnIndex))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($columnIndex) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($columnIndex) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $columnIndex = (int) $columnIndex;
            }
        }
        $this->columnStyles[$columnIndex] = $this->resolveStyle($name);
        return $this;
    }
    /**
     * Gets the current style for a column.
     *
     * If style was not set, it returns the global table style.
     *
     * @return TableStyle
     */
    public function getColumnStyle($columnIndex)
    {
        if (!\is_int($columnIndex)) {
            if (!(\is_bool($columnIndex) || \is_numeric($columnIndex))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($columnIndex) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($columnIndex) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $columnIndex = (int) $columnIndex;
            }
        }
        return isset($this->columnStyles[$columnIndex]) ? $this->columnStyles[$columnIndex] : $this->getStyle();
    }
    /**
     * Sets the minimum width of a column.
     *
     * @return $this
     */
    public function setColumnWidth($columnIndex, $width)
    {
        if (!\is_int($columnIndex)) {
            if (!(\is_bool($columnIndex) || \is_numeric($columnIndex))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($columnIndex) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($columnIndex) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $columnIndex = (int) $columnIndex;
            }
        }
        if (!\is_int($width)) {
            if (!(\is_bool($width) || \is_numeric($width))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($width) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($width) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $width = (int) $width;
            }
        }
        $this->columnWidths[$columnIndex] = $width;
        return $this;
    }
    /**
     * Sets the minimum width of all columns.
     *
     * @return $this
     */
    public function setColumnWidths(array $widths)
    {
        $this->columnWidths = [];
        foreach ($widths as $index => $width) {
            $this->setColumnWidth($index, $width);
        }
        return $this;
    }
    /**
     * Sets the maximum width of a column.
     *
     * Any cell within this column which contents exceeds the specified width will be wrapped into multiple lines, while
     * formatted strings are preserved.
     *
     * @return $this
     */
    public function setColumnMaxWidth($columnIndex, $width)
    {
        if (!\is_int($columnIndex)) {
            if (!(\is_bool($columnIndex) || \is_numeric($columnIndex))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($columnIndex) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($columnIndex) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $columnIndex = (int) $columnIndex;
            }
        }
        if (!\is_int($width)) {
            if (!(\is_bool($width) || \is_numeric($width))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($width) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($width) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $width = (int) $width;
            }
        }
        if (!$this->output->getFormatter() instanceof WrappableOutputFormatterInterface) {
            throw new \LogicException(\sprintf('Setting a maximum column width is only supported when using a "%s" formatter, got "%s".', WrappableOutputFormatterInterface::class, \get_debug_type($this->output->getFormatter())));
        }
        $this->columnMaxWidths[$columnIndex] = $width;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function setHeaders(array $headers)
    {
        $headers = \array_values($headers);
        if (!empty($headers) && !\is_array($headers[0])) {
            $headers = [$headers];
        }
        $this->headers = $headers;
        return $this;
    }
    public function setRows(array $rows)
    {
        $this->rows = [];
        return $this->addRows($rows);
    }
    public function addRows(array $rows)
    {
        foreach ($rows as $row) {
            $this->addRow($row);
        }
        return $this;
    }
    public function addRow($row)
    {
        if ($row instanceof TableSeparator) {
            $this->rows[] = $row;
            return $this;
        }
        if (!\is_array($row)) {
            throw new InvalidArgumentException('A row must be an array or a TableSeparator instance.');
        }
        $this->rows[] = \array_values($row);
        return $this;
    }
    /**
     * Adds a row to the table, and re-renders the table.
     */
    public function appendRow($row)
    {
        if (!$this->output instanceof ConsoleSectionOutput) {
            throw new RuntimeException(\sprintf('Output should be an instance of "%s" when calling "%s".', ConsoleSectionOutput::class, __METHOD__));
        }
        if ($this->rendered) {
            $this->output->clear($this->calculateRowCount());
        }
        $this->addRow($row);
        $this->render();
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function setRow($column, array $row)
    {
        $this->rows[$column] = $row;
        return $this;
    }
    public function setHeaderTitle($title)
    {
        if (!\is_null($title)) {
            if (!\is_string($title)) {
                if (!(\is_string($title) || \is_object($title) && \method_exists($title, '__toString') || (\is_bool($title) || \is_numeric($title)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($title) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($title) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $title = (string) $title;
                }
            }
        }
        $this->headerTitle = $title;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function setFooterTitle($title)
    {
        if (!\is_null($title)) {
            if (!\is_string($title)) {
                if (!(\is_string($title) || \is_object($title) && \method_exists($title, '__toString') || (\is_bool($title) || \is_numeric($title)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($title) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($title) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $title = (string) $title;
                }
            }
        }
        $this->footerTitle = $title;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function setHorizontal($horizontal = \true)
    {
        if (!\is_bool($horizontal)) {
            if (!(\is_bool($horizontal) || \is_numeric($horizontal) || \is_string($horizontal))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($horizontal) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($horizontal) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $horizontal = (bool) $horizontal;
            }
        }
        $this->horizontal = $horizontal;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Renders table to output.
     *
     * Example:
     *
     *     +---------------+-----------------------+------------------+
     *     | ISBN          | Title                 | Author           |
     *     +---------------+-----------------------+------------------+
     *     | 99921-58-10-7 | Divine Comedy         | Dante Alighieri  |
     *     | 9971-5-0210-0 | A Tale of Two Cities  | Charles Dickens  |
     *     | 960-425-059-0 | The Lord of the Rings | J. R. R. Tolkien |
     *     +---------------+-----------------------+------------------+
     */
    public function render()
    {
        $divider = new TableSeparator();
        if ($this->horizontal) {
            $rows = [];
            foreach (isset($this->headers[0]) ? $this->headers[0] : [] as $i => $header) {
                $rows[$i] = [$header];
                foreach ($this->rows as $row) {
                    if ($row instanceof TableSeparator) {
                        continue;
                    }
                    if (isset($row[$i])) {
                        $rows[$i][] = $row[$i];
                    } elseif ($rows[$i][0] instanceof TableCell && $rows[$i][0]->getColspan() >= 2) {
                        // Noop, there is a "title"
                    } else {
                        $rows[$i][] = null;
                    }
                }
            }
        } else {
            $rows = \array_merge($this->headers, [$divider], $this->rows);
        }
        $this->calculateNumberOfColumns($rows);
        $rows = $this->buildTableRows($rows);
        $this->calculateColumnsWidth($rows);
        $isHeader = !$this->horizontal;
        $isFirstRow = $this->horizontal;
        $hasTitle = (bool) $this->headerTitle;
        foreach ($rows as $row) {
            if ($divider === $row) {
                $isHeader = \false;
                $isFirstRow = \true;
                continue;
            }
            if ($row instanceof TableSeparator) {
                $this->renderRowSeparator();
                continue;
            }
            if (!$row) {
                continue;
            }
            if ($isHeader || $isFirstRow) {
                $this->renderRowSeparator($isHeader ? self::SEPARATOR_TOP : self::SEPARATOR_TOP_BOTTOM, $hasTitle ? $this->headerTitle : null, $hasTitle ? $this->style->getHeaderTitleFormat() : null);
                $isFirstRow = \false;
                $hasTitle = \false;
            }
            if ($this->horizontal) {
                $this->renderRow($row, $this->style->getCellRowFormat(), $this->style->getCellHeaderFormat());
            } else {
                $this->renderRow($row, $isHeader ? $this->style->getCellHeaderFormat() : $this->style->getCellRowFormat());
            }
        }
        $this->renderRowSeparator(self::SEPARATOR_BOTTOM, $this->footerTitle, $this->style->getFooterTitleFormat());
        $this->cleanup();
        $this->rendered = \true;
    }
    /**
     * Renders horizontal header separator.
     *
     * Example:
     *
     *     +-----+-----------+-------+
     */
    private function renderRowSeparator($type = self::SEPARATOR_MID, $title = null, $titleFormat = null)
    {
        if (!\is_int($type)) {
            if (!(\is_bool($type) || \is_numeric($type))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($type) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $type = (int) $type;
            }
        }
        if (!\is_null($title)) {
            if (!\is_string($title)) {
                if (!(\is_string($title) || \is_object($title) && \method_exists($title, '__toString') || (\is_bool($title) || \is_numeric($title)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($title) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($title) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $title = (string) $title;
                }
            }
        }
        if (!\is_null($titleFormat)) {
            if (!\is_string($titleFormat)) {
                if (!(\is_string($titleFormat) || \is_object($titleFormat) && \method_exists($titleFormat, '__toString') || (\is_bool($titleFormat) || \is_numeric($titleFormat)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($titleFormat) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($titleFormat) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $titleFormat = (string) $titleFormat;
                }
            }
        }
        if (0 === ($count = $this->numberOfColumns)) {
            return;
        }
        $borders = $this->style->getBorderChars();
        if (!$borders[0] && !$borders[2] && !$this->style->getCrossingChar()) {
            return;
        }
        $crossings = $this->style->getCrossingChars();
        if (self::SEPARATOR_MID === $type) {
            list($horizontal, $leftChar, $midChar, $rightChar) = [$borders[2], $crossings[8], $crossings[0], $crossings[4]];
        } elseif (self::SEPARATOR_TOP === $type) {
            list($horizontal, $leftChar, $midChar, $rightChar) = [$borders[0], $crossings[1], $crossings[2], $crossings[3]];
        } elseif (self::SEPARATOR_TOP_BOTTOM === $type) {
            list($horizontal, $leftChar, $midChar, $rightChar) = [$borders[0], $crossings[9], $crossings[10], $crossings[11]];
        } else {
            list($horizontal, $leftChar, $midChar, $rightChar) = [$borders[0], $crossings[7], $crossings[6], $crossings[5]];
        }
        $markup = $leftChar;
        for ($column = 0; $column < $count; ++$column) {
            $markup .= \str_repeat($horizontal, $this->effectiveColumnWidths[$column]);
            $markup .= $column === $count - 1 ? $rightChar : $midChar;
        }
        if (null !== $title) {
            $titleLength = Helper::width(Helper::removeDecoration($formatter = $this->output->getFormatter(), $formattedTitle = \sprintf($titleFormat, $title)));
            $markupLength = Helper::width($markup);
            if ($titleLength > ($limit = $markupLength - 4)) {
                $titleLength = $limit;
                $formatLength = Helper::width(Helper::removeDecoration($formatter, \sprintf($titleFormat, '')));
                $formattedTitle = \sprintf($titleFormat, Helper::substr($title, 0, $limit - $formatLength - 3) . '...');
            }
            $titleStart = \intdiv($markupLength - $titleLength, 2);
            if (\false === \mb_detect_encoding($markup, null, \true)) {
                $markup = \substr_replace($markup, $formattedTitle, $titleStart, $titleLength);
            } else {
                $markup = \mb_substr($markup, 0, $titleStart) . $formattedTitle . \mb_substr($markup, $titleStart + $titleLength);
            }
        }
        $this->output->writeln(\sprintf($this->style->getBorderFormat(), $markup));
    }
    /**
     * Renders vertical column separator.
     */
    private function renderColumnSeparator($type = self::BORDER_OUTSIDE)
    {
        if (!\is_int($type)) {
            if (!(\is_bool($type) || \is_numeric($type))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($type) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $type = (int) $type;
            }
        }
        $borders = $this->style->getBorderChars();
        $phabelReturn = \sprintf($this->style->getBorderFormat(), self::BORDER_OUTSIDE === $type ? $borders[1] : $borders[3]);
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
     * Renders table row.
     *
     * Example:
     *
     *     | 9971-5-0210-0 | A Tale of Two Cities  | Charles Dickens  |
     */
    private function renderRow(array $row, $cellFormat, $firstCellFormat = null)
    {
        if (!\is_string($cellFormat)) {
            if (!(\is_string($cellFormat) || \is_object($cellFormat) && \method_exists($cellFormat, '__toString') || (\is_bool($cellFormat) || \is_numeric($cellFormat)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($cellFormat) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($cellFormat) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $cellFormat = (string) $cellFormat;
            }
        }
        if (!\is_null($firstCellFormat)) {
            if (!\is_string($firstCellFormat)) {
                if (!(\is_string($firstCellFormat) || \is_object($firstCellFormat) && \method_exists($firstCellFormat, '__toString') || (\is_bool($firstCellFormat) || \is_numeric($firstCellFormat)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($firstCellFormat) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($firstCellFormat) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $firstCellFormat = (string) $firstCellFormat;
                }
            }
        }
        $rowContent = $this->renderColumnSeparator(self::BORDER_OUTSIDE);
        $columns = $this->getRowColumns($row);
        $last = \count($columns) - 1;
        foreach ($columns as $i => $column) {
            if ($firstCellFormat && 0 === $i) {
                $rowContent .= $this->renderCell($row, $column, $firstCellFormat);
            } else {
                $rowContent .= $this->renderCell($row, $column, $cellFormat);
            }
            $rowContent .= $this->renderColumnSeparator($last === $i ? self::BORDER_OUTSIDE : self::BORDER_INSIDE);
        }
        $this->output->writeln($rowContent);
    }
    /**
     * Renders table cell with padding.
     */
    private function renderCell(array $row, $column, $cellFormat)
    {
        if (!\is_int($column)) {
            if (!(\is_bool($column) || \is_numeric($column))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($column) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($column) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $column = (int) $column;
            }
        }
        if (!\is_string($cellFormat)) {
            if (!(\is_string($cellFormat) || \is_object($cellFormat) && \method_exists($cellFormat, '__toString') || (\is_bool($cellFormat) || \is_numeric($cellFormat)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($cellFormat) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($cellFormat) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $cellFormat = (string) $cellFormat;
            }
        }
        $cell = isset($row[$column]) ? $row[$column] : '';
        $width = $this->effectiveColumnWidths[$column];
        if ($cell instanceof TableCell && $cell->getColspan() > 1) {
            // add the width of the following columns(numbers of colspan).
            foreach (\range($column + 1, $column + $cell->getColspan() - 1) as $nextColumn) {
                $width += $this->getColumnSeparatorWidth() + $this->effectiveColumnWidths[$nextColumn];
            }
        }
        // str_pad won't work properly with multi-byte strings, we need to fix the padding
        if (\false !== ($encoding = \mb_detect_encoding($cell, null, \true))) {
            $width += \strlen($cell) - \mb_strwidth($cell, $encoding);
        }
        $style = $this->getColumnStyle($column);
        if ($cell instanceof TableSeparator) {
            $phabelReturn = \sprintf($style->getBorderFormat(), \str_repeat($style->getBorderChars()[2], $width));
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $width += Helper::length($cell) - Helper::length(Helper::removeDecoration($this->output->getFormatter(), $cell));
        $content = \sprintf($style->getCellRowContentFormat(), $cell);
        $padType = $style->getPadType();
        if ($cell instanceof TableCell && $cell->getStyle() instanceof TableCellStyle) {
            $isNotStyledByTag = !\preg_match('/^<(\\w+|(\\w+=[\\w,]+;?)*)>.+<\\/(\\w+|(\\w+=\\w+;?)*)?>$/', $cell);
            if ($isNotStyledByTag) {
                $cellFormat = $cell->getStyle()->getCellFormat();
                if (!\is_string($cellFormat)) {
                    $tag = \http_build_query($cell->getStyle()->getTagOptions(), '', ';');
                    $cellFormat = '<' . $tag . '>%s</>';
                }
                if (\strstr($content, '</>')) {
                    $content = \str_replace('</>', '', $content);
                    $width -= 3;
                }
                if (\strstr($content, '<fg=default;bg=default>')) {
                    $content = \str_replace('<fg=default;bg=default>', '', $content);
                    $width -= \strlen('<fg=default;bg=default>');
                }
            }
            $padType = $cell->getStyle()->getPadByAlign();
        }
        $phabelReturn = \sprintf($cellFormat, \str_pad($content, $width, $style->getPaddingChar(), $padType));
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
     * Calculate number of columns for this table.
     */
    private function calculateNumberOfColumns(array $rows)
    {
        $columns = [0];
        foreach ($rows as $row) {
            if ($row instanceof TableSeparator) {
                continue;
            }
            $columns[] = $this->getNumberOfColumns($row);
        }
        $this->numberOfColumns = \max($columns);
    }
    private function buildTableRows(array $rows)
    {
        /** @var WrappableOutputFormatterInterface $formatter */
        $formatter = $this->output->getFormatter();
        $unmergedRows = [];
        for ($rowKey = 0; $rowKey < \count($rows); ++$rowKey) {
            $rows = $this->fillNextRows($rows, $rowKey);
            // Remove any new line breaks and replace it with a new line
            foreach ($rows[$rowKey] as $column => $cell) {
                $colspan = $cell instanceof TableCell ? $cell->getColspan() : 1;
                if (isset($this->columnMaxWidths[$column]) && Helper::width(Helper::removeDecoration($formatter, $cell)) > $this->columnMaxWidths[$column]) {
                    $cell = $formatter->formatAndWrap($cell, $this->columnMaxWidths[$column] * $colspan);
                }
                if (!\strstr(isset($cell) ? $cell : '', "\n")) {
                    continue;
                }
                $escaped = \implode("\n", \array_map([OutputFormatter::class, 'escapeTrailingBackslash'], \explode("\n", $cell)));
                $cell = $cell instanceof TableCell ? new TableCell($escaped, ['colspan' => $cell->getColspan()]) : $escaped;
                $lines = \explode("\n", \str_replace("\n", "<fg=default;bg=default>\n</>", $cell));
                foreach ($lines as $lineKey => $line) {
                    if ($colspan > 1) {
                        $line = new TableCell($line, ['colspan' => $colspan]);
                    }
                    if (0 === $lineKey) {
                        $rows[$rowKey][$column] = $line;
                    } else {
                        if (!\array_key_exists($rowKey, $unmergedRows) || !\array_key_exists($lineKey, $unmergedRows[$rowKey])) {
                            $unmergedRows[$rowKey][$lineKey] = $this->copyRow($rows, $rowKey);
                        }
                        $unmergedRows[$rowKey][$lineKey][$column] = $line;
                    }
                }
            }
        }
        $phabelReturn = new TableRows(function () use($rows, $unmergedRows) {
            foreach ($rows as $rowKey => $row) {
                (yield $row instanceof TableSeparator ? $row : $this->fillCells($row));
                if (isset($unmergedRows[$rowKey])) {
                    foreach ($unmergedRows[$rowKey] as $row) {
                        (yield $row instanceof TableSeparator ? $row : $this->fillCells($row));
                    }
                }
            }
        });
        if (!$phabelReturn instanceof TableRows) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type TableRows, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function calculateRowCount()
    {
        $numberOfRows = \count(\iterator_to_array($this->buildTableRows(\array_merge($this->headers, [new TableSeparator()], $this->rows))));
        if ($this->headers) {
            ++$numberOfRows;
            // Add row for header separator
        }
        if (\count($this->rows) > 0) {
            ++$numberOfRows;
            // Add row for footer separator
        }
        $phabelReturn = $numberOfRows;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * fill rows that contains rowspan > 1.
     *
     * @throws InvalidArgumentException
     */
    private function fillNextRows(array $rows, $line)
    {
        if (!\is_int($line)) {
            if (!(\is_bool($line) || \is_numeric($line))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($line) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($line) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $line = (int) $line;
            }
        }
        $unmergedRows = [];
        foreach ($rows[$line] as $column => $cell) {
            if (null !== $cell && !$cell instanceof TableCell && !\is_scalar($cell) && !(\is_object($cell) && \method_exists($cell, '__toString'))) {
                throw new InvalidArgumentException(\sprintf('A cell must be a TableCell, a scalar or an object implementing "__toString()", "%s" given.', \get_debug_type($cell)));
            }
            if ($cell instanceof TableCell && $cell->getRowspan() > 1) {
                $nbLines = $cell->getRowspan() - 1;
                $lines = [$cell];
                if (\strstr($cell, "\n")) {
                    $lines = \explode("\n", \str_replace("\n", "<fg=default;bg=default>\n</>", $cell));
                    $nbLines = \count($lines) > $nbLines ? \substr_count($cell, "\n") : $nbLines;
                    $rows[$line][$column] = new TableCell($lines[0], ['colspan' => $cell->getColspan(), 'style' => $cell->getStyle()]);
                    unset($lines[0]);
                }
                // create a two dimensional array (rowspan x colspan)
                $unmergedRows = \array_replace_recursive(\array_fill($line + 1, $nbLines, []), $unmergedRows);
                foreach ($unmergedRows as $unmergedRowKey => $unmergedRow) {
                    $value = isset($lines[$unmergedRowKey - $line]) ? $lines[$unmergedRowKey - $line] : '';
                    $unmergedRows[$unmergedRowKey][$column] = new TableCell($value, ['colspan' => $cell->getColspan(), 'style' => $cell->getStyle()]);
                    if ($nbLines === $unmergedRowKey - $line) {
                        break;
                    }
                }
            }
        }
        foreach ($unmergedRows as $unmergedRowKey => $unmergedRow) {
            // we need to know if $unmergedRow will be merged or inserted into $rows
            if (isset($rows[$unmergedRowKey]) && \is_array($rows[$unmergedRowKey]) && $this->getNumberOfColumns($rows[$unmergedRowKey]) + $this->getNumberOfColumns($unmergedRows[$unmergedRowKey]) <= $this->numberOfColumns) {
                foreach ($unmergedRow as $cellKey => $cell) {
                    // insert cell into row at cellKey position
                    \array_splice($rows[$unmergedRowKey], $cellKey, 0, [$cell]);
                }
            } else {
                $row = $this->copyRow($rows, $unmergedRowKey - 1);
                foreach ($unmergedRow as $column => $cell) {
                    if (!empty($cell)) {
                        $row[$column] = $unmergedRow[$column];
                    }
                }
                \array_splice($rows, $unmergedRowKey, 0, [$row]);
            }
        }
        $phabelReturn = $rows;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * fill cells for a row that contains colspan > 1.
     */
    private function fillCells($row)
    {
        if (!(\is_array($row) || $row instanceof \Traversable)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($row) must be of type iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($row) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $newRow = [];
        foreach ($row as $column => $cell) {
            $newRow[] = $cell;
            if ($cell instanceof TableCell && $cell->getColspan() > 1) {
                foreach (\range($column + 1, $column + $cell->getColspan() - 1) as $position) {
                    // insert empty value at column position
                    $newRow[] = '';
                }
            }
        }
        return $newRow ?: $row;
    }
    private function copyRow(array $rows, $line)
    {
        if (!\is_int($line)) {
            if (!(\is_bool($line) || \is_numeric($line))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($line) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($line) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $line = (int) $line;
            }
        }
        $row = $rows[$line];
        foreach ($row as $cellKey => $cellValue) {
            $row[$cellKey] = '';
            if ($cellValue instanceof TableCell) {
                $row[$cellKey] = new TableCell('', ['colspan' => $cellValue->getColspan()]);
            }
        }
        $phabelReturn = $row;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Gets number of columns by row.
     */
    private function getNumberOfColumns(array $row)
    {
        $columns = \count($row);
        foreach ($row as $column) {
            $columns += $column instanceof TableCell ? $column->getColspan() - 1 : 0;
        }
        $phabelReturn = $columns;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Gets list of columns for the given row.
     */
    private function getRowColumns(array $row)
    {
        $columns = \range(0, $this->numberOfColumns - 1);
        foreach ($row as $cellKey => $cell) {
            if ($cell instanceof TableCell && $cell->getColspan() > 1) {
                // exclude grouped columns.
                $columns = \array_diff($columns, \range($cellKey + 1, $cellKey + $cell->getColspan() - 1));
            }
        }
        $phabelReturn = $columns;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Calculates columns widths.
     */
    private function calculateColumnsWidth($rows)
    {
        if (!(\is_array($rows) || $rows instanceof \Traversable)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($rows) must be of type iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($rows) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        for ($column = 0; $column < $this->numberOfColumns; ++$column) {
            $lengths = [];
            foreach ($rows as $row) {
                if ($row instanceof TableSeparator) {
                    continue;
                }
                foreach ($row as $i => $cell) {
                    if ($cell instanceof TableCell) {
                        $textContent = Helper::removeDecoration($this->output->getFormatter(), $cell);
                        $textLength = Helper::width($textContent);
                        if ($textLength > 0) {
                            $contentColumns = \str_split($textContent, \ceil($textLength / $cell->getColspan()));
                            foreach ($contentColumns as $position => $content) {
                                $row[$i + $position] = $content;
                            }
                        }
                    }
                }
                $lengths[] = $this->getCellWidth($row, $column);
            }
            $this->effectiveColumnWidths[$column] = \max($lengths) + Helper::width($this->style->getCellRowContentFormat()) - 2;
        }
    }
    private function getColumnSeparatorWidth()
    {
        $phabelReturn = Helper::width(\sprintf($this->style->getBorderFormat(), $this->style->getBorderChars()[3]));
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    private function getCellWidth(array $row, $column)
    {
        if (!\is_int($column)) {
            if (!(\is_bool($column) || \is_numeric($column))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($column) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($column) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $column = (int) $column;
            }
        }
        $cellWidth = 0;
        if (isset($row[$column])) {
            $cell = $row[$column];
            $cellWidth = Helper::width(Helper::removeDecoration($this->output->getFormatter(), $cell));
        }
        $columnWidth = isset($this->columnWidths[$column]) ? $this->columnWidths[$column] : 0;
        $cellWidth = \max($cellWidth, $columnWidth);
        $phabelReturn = isset($this->columnMaxWidths[$column]) ? \min($this->columnMaxWidths[$column], $cellWidth) : $cellWidth;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Called after rendering to cleanup cache data.
     */
    private function cleanup()
    {
        $this->effectiveColumnWidths = [];
        $this->numberOfColumns = null;
    }
    private static function initStyles()
    {
        $borderless = new TableStyle();
        $borderless->setHorizontalBorderChars('=')->setVerticalBorderChars(' ')->setDefaultCrossingChar(' ');
        $compact = new TableStyle();
        $compact->setHorizontalBorderChars('')->setVerticalBorderChars(' ')->setDefaultCrossingChar('')->setCellRowContentFormat('%s');
        $styleGuide = new TableStyle();
        $styleGuide->setHorizontalBorderChars('-')->setVerticalBorderChars(' ')->setDefaultCrossingChar(' ')->setCellHeaderFormat('%s');
        $box = (new TableStyle())->setHorizontalBorderChars('─')->setVerticalBorderChars('│')->setCrossingChars('┼', '┌', '┬', '┐', '┤', '┘', '┴', '└', '├');
        $boxDouble = (new TableStyle())->setHorizontalBorderChars('═', '─')->setVerticalBorderChars('║', '│')->setCrossingChars('┼', '╔', '╤', '╗', '╢', '╝', '╧', '╚', '╟', '╠', '╪', '╣');
        $phabelReturn = ['default' => new TableStyle(), 'borderless' => $borderless, 'compact' => $compact, 'symfony-style-guide' => $styleGuide, 'box' => $box, 'box-double' => $boxDouble];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function resolveStyle($name)
    {
        if ($name instanceof TableStyle) {
            $phabelReturn = $name;
            if (!$phabelReturn instanceof TableStyle) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type TableStyle, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (isset(self::$styles[$name])) {
            $phabelReturn = self::$styles[$name];
            if (!$phabelReturn instanceof TableStyle) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type TableStyle, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        throw new InvalidArgumentException(\sprintf('Style "%s" is not defined.', $name));
        throw new \TypeError(__METHOD__ . '(): Return value must be of type TableStyle, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
}
