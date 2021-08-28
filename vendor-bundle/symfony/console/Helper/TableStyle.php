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
use Phabel\Symfony\Component\Console\Exception\LogicException;
/**
 * Defines the styles for a Table.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Саша Стаменковић <umpirsky@gmail.com>
 * @author Dany Maillard <danymaillard93b@gmail.com>
 */
class TableStyle
{
    private $paddingChar = ' ';
    private $horizontalOutsideBorderChar = '-';
    private $horizontalInsideBorderChar = '-';
    private $verticalOutsideBorderChar = '|';
    private $verticalInsideBorderChar = '|';
    private $crossingChar = '+';
    private $crossingTopRightChar = '+';
    private $crossingTopMidChar = '+';
    private $crossingTopLeftChar = '+';
    private $crossingMidRightChar = '+';
    private $crossingBottomRightChar = '+';
    private $crossingBottomMidChar = '+';
    private $crossingBottomLeftChar = '+';
    private $crossingMidLeftChar = '+';
    private $crossingTopLeftBottomChar = '+';
    private $crossingTopMidBottomChar = '+';
    private $crossingTopRightBottomChar = '+';
    private $headerTitleFormat = '<fg=black;bg=white;options=bold> %s </>';
    private $footerTitleFormat = '<fg=black;bg=white;options=bold> %s </>';
    private $cellHeaderFormat = '<info>%s</info>';
    private $cellRowFormat = '%s';
    private $cellRowContentFormat = ' %s ';
    private $borderFormat = '%s';
    private $padType = \STR_PAD_RIGHT;
    /**
     * Sets padding character, used for cell padding.
     *
     * @return $this
     */
    public function setPaddingChar($paddingChar)
    {
        if (!\is_string($paddingChar)) {
            if (!(\is_string($paddingChar) || \is_object($paddingChar) && \method_exists($paddingChar, '__toString') || (\is_bool($paddingChar) || \is_numeric($paddingChar)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($paddingChar) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($paddingChar) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $paddingChar = (string) $paddingChar;
            }
        }
        if (!$paddingChar) {
            throw new LogicException('The padding char must not be empty.');
        }
        $this->paddingChar = $paddingChar;
        return $this;
    }
    /**
     * Gets padding character, used for cell padding.
     *
     * @return string
     */
    public function getPaddingChar()
    {
        return $this->paddingChar;
    }
    /**
     * Sets horizontal border characters.
     *
     * <code>
     * ╔═══════════════╤══════════════════════════╤══════════════════╗
     * 1 ISBN          2 Title                    │ Author           ║
     * ╠═══════════════╪══════════════════════════╪══════════════════╣
     * ║ 99921-58-10-7 │ Divine Comedy            │ Dante Alighieri  ║
     * ║ 9971-5-0210-0 │ A Tale of Two Cities     │ Charles Dickens  ║
     * ║ 960-425-059-0 │ The Lord of the Rings    │ J. R. R. Tolkien ║
     * ║ 80-902734-1-6 │ And Then There Were None │ Agatha Christie  ║
     * ╚═══════════════╧══════════════════════════╧══════════════════╝
     * </code>
     */
    public function setHorizontalBorderChars($outside, $inside = null)
    {
        if (!\is_string($outside)) {
            if (!(\is_string($outside) || \is_object($outside) && \method_exists($outside, '__toString') || (\is_bool($outside) || \is_numeric($outside)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($outside) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($outside) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $outside = (string) $outside;
            }
        }
        if (!\is_null($inside)) {
            if (!\is_string($inside)) {
                if (!(\is_string($inside) || \is_object($inside) && \method_exists($inside, '__toString') || (\is_bool($inside) || \is_numeric($inside)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($inside) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($inside) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $inside = (string) $inside;
                }
            }
        }
        $this->horizontalOutsideBorderChar = $outside;
        $this->horizontalInsideBorderChar = isset($inside) ? $inside : $outside;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Sets vertical border characters.
     *
     * <code>
     * ╔═══════════════╤══════════════════════════╤══════════════════╗
     * ║ ISBN          │ Title                    │ Author           ║
     * ╠═══════1═══════╪══════════════════════════╪══════════════════╣
     * ║ 99921-58-10-7 │ Divine Comedy            │ Dante Alighieri  ║
     * ║ 9971-5-0210-0 │ A Tale of Two Cities     │ Charles Dickens  ║
     * ╟───────2───────┼──────────────────────────┼──────────────────╢
     * ║ 960-425-059-0 │ The Lord of the Rings    │ J. R. R. Tolkien ║
     * ║ 80-902734-1-6 │ And Then There Were None │ Agatha Christie  ║
     * ╚═══════════════╧══════════════════════════╧══════════════════╝
     * </code>
     */
    public function setVerticalBorderChars($outside, $inside = null)
    {
        if (!\is_string($outside)) {
            if (!(\is_string($outside) || \is_object($outside) && \method_exists($outside, '__toString') || (\is_bool($outside) || \is_numeric($outside)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($outside) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($outside) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $outside = (string) $outside;
            }
        }
        if (!\is_null($inside)) {
            if (!\is_string($inside)) {
                if (!(\is_string($inside) || \is_object($inside) && \method_exists($inside, '__toString') || (\is_bool($inside) || \is_numeric($inside)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($inside) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($inside) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $inside = (string) $inside;
                }
            }
        }
        $this->verticalOutsideBorderChar = $outside;
        $this->verticalInsideBorderChar = isset($inside) ? $inside : $outside;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Gets border characters.
     *
     * @internal
     */
    public function getBorderChars()
    {
        $phabelReturn = [$this->horizontalOutsideBorderChar, $this->verticalOutsideBorderChar, $this->horizontalInsideBorderChar, $this->verticalInsideBorderChar];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Sets crossing characters.
     *
     * Example:
     * <code>
     * 1═══════════════2══════════════════════════2══════════════════3
     * ║ ISBN          │ Title                    │ Author           ║
     * 8'══════════════0'═════════════════════════0'═════════════════4'
     * ║ 99921-58-10-7 │ Divine Comedy            │ Dante Alighieri  ║
     * ║ 9971-5-0210-0 │ A Tale of Two Cities     │ Charles Dickens  ║
     * 8───────────────0──────────────────────────0──────────────────4
     * ║ 960-425-059-0 │ The Lord of the Rings    │ J. R. R. Tolkien ║
     * ║ 80-902734-1-6 │ And Then There Were None │ Agatha Christie  ║
     * 7═══════════════6══════════════════════════6══════════════════5
     * </code>
     *
     * @param string      $cross          Crossing char (see #0 of example)
     * @param string      $topLeft        Top left char (see #1 of example)
     * @param string      $topMid         Top mid char (see #2 of example)
     * @param string      $topRight       Top right char (see #3 of example)
     * @param string      $midRight       Mid right char (see #4 of example)
     * @param string      $bottomRight    Bottom right char (see #5 of example)
     * @param string      $bottomMid      Bottom mid char (see #6 of example)
     * @param string      $bottomLeft     Bottom left char (see #7 of example)
     * @param string      $midLeft        Mid left char (see #8 of example)
     * @param string|null $topLeftBottom  Top left bottom char (see #8' of example), equals to $midLeft if null
     * @param string|null $topMidBottom   Top mid bottom char (see #0' of example), equals to $cross if null
     * @param string|null $topRightBottom Top right bottom char (see #4' of example), equals to $midRight if null
     */
    public function setCrossingChars($cross, $topLeft, $topMid, $topRight, $midRight, $bottomRight, $bottomMid, $bottomLeft, $midLeft, $topLeftBottom = null, $topMidBottom = null, $topRightBottom = null)
    {
        if (!\is_string($cross)) {
            if (!(\is_string($cross) || \is_object($cross) && \method_exists($cross, '__toString') || (\is_bool($cross) || \is_numeric($cross)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($cross) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($cross) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $cross = (string) $cross;
            }
        }
        if (!\is_string($topLeft)) {
            if (!(\is_string($topLeft) || \is_object($topLeft) && \method_exists($topLeft, '__toString') || (\is_bool($topLeft) || \is_numeric($topLeft)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($topLeft) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($topLeft) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $topLeft = (string) $topLeft;
            }
        }
        if (!\is_string($topMid)) {
            if (!(\is_string($topMid) || \is_object($topMid) && \method_exists($topMid, '__toString') || (\is_bool($topMid) || \is_numeric($topMid)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($topMid) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($topMid) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $topMid = (string) $topMid;
            }
        }
        if (!\is_string($topRight)) {
            if (!(\is_string($topRight) || \is_object($topRight) && \method_exists($topRight, '__toString') || (\is_bool($topRight) || \is_numeric($topRight)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($topRight) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($topRight) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $topRight = (string) $topRight;
            }
        }
        if (!\is_string($midRight)) {
            if (!(\is_string($midRight) || \is_object($midRight) && \method_exists($midRight, '__toString') || (\is_bool($midRight) || \is_numeric($midRight)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #5 ($midRight) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($midRight) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $midRight = (string) $midRight;
            }
        }
        if (!\is_string($bottomRight)) {
            if (!(\is_string($bottomRight) || \is_object($bottomRight) && \method_exists($bottomRight, '__toString') || (\is_bool($bottomRight) || \is_numeric($bottomRight)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #6 ($bottomRight) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($bottomRight) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $bottomRight = (string) $bottomRight;
            }
        }
        if (!\is_string($bottomMid)) {
            if (!(\is_string($bottomMid) || \is_object($bottomMid) && \method_exists($bottomMid, '__toString') || (\is_bool($bottomMid) || \is_numeric($bottomMid)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #7 ($bottomMid) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($bottomMid) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $bottomMid = (string) $bottomMid;
            }
        }
        if (!\is_string($bottomLeft)) {
            if (!(\is_string($bottomLeft) || \is_object($bottomLeft) && \method_exists($bottomLeft, '__toString') || (\is_bool($bottomLeft) || \is_numeric($bottomLeft)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #8 ($bottomLeft) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($bottomLeft) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $bottomLeft = (string) $bottomLeft;
            }
        }
        if (!\is_string($midLeft)) {
            if (!(\is_string($midLeft) || \is_object($midLeft) && \method_exists($midLeft, '__toString') || (\is_bool($midLeft) || \is_numeric($midLeft)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #9 ($midLeft) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($midLeft) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $midLeft = (string) $midLeft;
            }
        }
        if (!\is_null($topLeftBottom)) {
            if (!\is_string($topLeftBottom)) {
                if (!(\is_string($topLeftBottom) || \is_object($topLeftBottom) && \method_exists($topLeftBottom, '__toString') || (\is_bool($topLeftBottom) || \is_numeric($topLeftBottom)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #10 ($topLeftBottom) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($topLeftBottom) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $topLeftBottom = (string) $topLeftBottom;
                }
            }
        }
        if (!\is_null($topMidBottom)) {
            if (!\is_string($topMidBottom)) {
                if (!(\is_string($topMidBottom) || \is_object($topMidBottom) && \method_exists($topMidBottom, '__toString') || (\is_bool($topMidBottom) || \is_numeric($topMidBottom)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #11 ($topMidBottom) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($topMidBottom) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $topMidBottom = (string) $topMidBottom;
                }
            }
        }
        if (!\is_null($topRightBottom)) {
            if (!\is_string($topRightBottom)) {
                if (!(\is_string($topRightBottom) || \is_object($topRightBottom) && \method_exists($topRightBottom, '__toString') || (\is_bool($topRightBottom) || \is_numeric($topRightBottom)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #12 ($topRightBottom) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($topRightBottom) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $topRightBottom = (string) $topRightBottom;
                }
            }
        }
        $this->crossingChar = $cross;
        $this->crossingTopLeftChar = $topLeft;
        $this->crossingTopMidChar = $topMid;
        $this->crossingTopRightChar = $topRight;
        $this->crossingMidRightChar = $midRight;
        $this->crossingBottomRightChar = $bottomRight;
        $this->crossingBottomMidChar = $bottomMid;
        $this->crossingBottomLeftChar = $bottomLeft;
        $this->crossingMidLeftChar = $midLeft;
        $this->crossingTopLeftBottomChar = isset($topLeftBottom) ? $topLeftBottom : $midLeft;
        $this->crossingTopMidBottomChar = isset($topMidBottom) ? $topMidBottom : $cross;
        $this->crossingTopRightBottomChar = isset($topRightBottom) ? $topRightBottom : $midRight;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Sets default crossing character used for each cross.
     *
     * @see {@link setCrossingChars()} for setting each crossing individually.
     */
    public function setDefaultCrossingChar($char)
    {
        if (!\is_string($char)) {
            if (!(\is_string($char) || \is_object($char) && \method_exists($char, '__toString') || (\is_bool($char) || \is_numeric($char)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($char) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($char) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $char = (string) $char;
            }
        }
        $phabelReturn = $this->setCrossingChars($char, $char, $char, $char, $char, $char, $char, $char, $char);
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Gets crossing character.
     *
     * @return string
     */
    public function getCrossingChar()
    {
        return $this->crossingChar;
    }
    /**
     * Gets crossing characters.
     *
     * @internal
     */
    public function getCrossingChars()
    {
        $phabelReturn = [$this->crossingChar, $this->crossingTopLeftChar, $this->crossingTopMidChar, $this->crossingTopRightChar, $this->crossingMidRightChar, $this->crossingBottomRightChar, $this->crossingBottomMidChar, $this->crossingBottomLeftChar, $this->crossingMidLeftChar, $this->crossingTopLeftBottomChar, $this->crossingTopMidBottomChar, $this->crossingTopRightBottomChar];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Sets header cell format.
     *
     * @return $this
     */
    public function setCellHeaderFormat($cellHeaderFormat)
    {
        if (!\is_string($cellHeaderFormat)) {
            if (!(\is_string($cellHeaderFormat) || \is_object($cellHeaderFormat) && \method_exists($cellHeaderFormat, '__toString') || (\is_bool($cellHeaderFormat) || \is_numeric($cellHeaderFormat)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($cellHeaderFormat) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($cellHeaderFormat) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $cellHeaderFormat = (string) $cellHeaderFormat;
            }
        }
        $this->cellHeaderFormat = $cellHeaderFormat;
        return $this;
    }
    /**
     * Gets header cell format.
     *
     * @return string
     */
    public function getCellHeaderFormat()
    {
        return $this->cellHeaderFormat;
    }
    /**
     * Sets row cell format.
     *
     * @return $this
     */
    public function setCellRowFormat($cellRowFormat)
    {
        if (!\is_string($cellRowFormat)) {
            if (!(\is_string($cellRowFormat) || \is_object($cellRowFormat) && \method_exists($cellRowFormat, '__toString') || (\is_bool($cellRowFormat) || \is_numeric($cellRowFormat)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($cellRowFormat) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($cellRowFormat) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $cellRowFormat = (string) $cellRowFormat;
            }
        }
        $this->cellRowFormat = $cellRowFormat;
        return $this;
    }
    /**
     * Gets row cell format.
     *
     * @return string
     */
    public function getCellRowFormat()
    {
        return $this->cellRowFormat;
    }
    /**
     * Sets row cell content format.
     *
     * @return $this
     */
    public function setCellRowContentFormat($cellRowContentFormat)
    {
        if (!\is_string($cellRowContentFormat)) {
            if (!(\is_string($cellRowContentFormat) || \is_object($cellRowContentFormat) && \method_exists($cellRowContentFormat, '__toString') || (\is_bool($cellRowContentFormat) || \is_numeric($cellRowContentFormat)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($cellRowContentFormat) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($cellRowContentFormat) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $cellRowContentFormat = (string) $cellRowContentFormat;
            }
        }
        $this->cellRowContentFormat = $cellRowContentFormat;
        return $this;
    }
    /**
     * Gets row cell content format.
     *
     * @return string
     */
    public function getCellRowContentFormat()
    {
        return $this->cellRowContentFormat;
    }
    /**
     * Sets table border format.
     *
     * @return $this
     */
    public function setBorderFormat($borderFormat)
    {
        if (!\is_string($borderFormat)) {
            if (!(\is_string($borderFormat) || \is_object($borderFormat) && \method_exists($borderFormat, '__toString') || (\is_bool($borderFormat) || \is_numeric($borderFormat)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($borderFormat) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($borderFormat) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $borderFormat = (string) $borderFormat;
            }
        }
        $this->borderFormat = $borderFormat;
        return $this;
    }
    /**
     * Gets table border format.
     *
     * @return string
     */
    public function getBorderFormat()
    {
        return $this->borderFormat;
    }
    /**
     * Sets cell padding type.
     *
     * @return $this
     */
    public function setPadType($padType)
    {
        if (!\is_int($padType)) {
            if (!(\is_bool($padType) || \is_numeric($padType))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($padType) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($padType) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $padType = (int) $padType;
            }
        }
        if (!\in_array($padType, [\STR_PAD_LEFT, \STR_PAD_RIGHT, \STR_PAD_BOTH], \true)) {
            throw new InvalidArgumentException('Invalid padding type. Expected one of (STR_PAD_LEFT, STR_PAD_RIGHT, STR_PAD_BOTH).');
        }
        $this->padType = $padType;
        return $this;
    }
    /**
     * Gets cell padding type.
     *
     * @return int
     */
    public function getPadType()
    {
        return $this->padType;
    }
    public function getHeaderTitleFormat()
    {
        $phabelReturn = $this->headerTitleFormat;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function setHeaderTitleFormat($format)
    {
        if (!\is_string($format)) {
            if (!(\is_string($format) || \is_object($format) && \method_exists($format, '__toString') || (\is_bool($format) || \is_numeric($format)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($format) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($format) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $format = (string) $format;
            }
        }
        $this->headerTitleFormat = $format;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getFooterTitleFormat()
    {
        $phabelReturn = $this->footerTitleFormat;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function setFooterTitleFormat($format)
    {
        if (!\is_string($format)) {
            if (!(\is_string($format) || \is_object($format) && \method_exists($format, '__toString') || (\is_bool($format) || \is_numeric($format)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($format) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($format) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $format = (string) $format;
            }
        }
        $this->footerTitleFormat = $format;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
