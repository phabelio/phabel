<?php

namespace Phabel\PhpParser;

class Comment implements \JsonSerializable
{
    protected $text;
    protected $startLine;
    protected $startFilePos;
    protected $startTokenPos;
    protected $endLine;
    protected $endFilePos;
    protected $endTokenPos;
    /**
     * Constructs a comment node.
     *
     * @param string $text          Comment text (including comment delimiters like /*)
     * @param int    $startLine     Line number the comment started on
     * @param int    $startFilePos  File offset the comment started on
     * @param int    $startTokenPos Token offset the comment started on
     */
    public function __construct($text, $startLine = -1, $startFilePos = -1, $startTokenPos = -1, $endLine = -1, $endFilePos = -1, $endTokenPos = -1)
    {
        if (!\is_string($text)) {
            if (!(\is_string($text) || \is_object($text) && \method_exists($text, '__toString') || (\is_bool($text) || \is_numeric($text)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($text) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($text) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $text = (string) $text;
            }
        }
        if (!\is_int($startLine)) {
            if (!(\is_bool($startLine) || \is_numeric($startLine))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($startLine) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($startLine) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $startLine = (int) $startLine;
            }
        }
        if (!\is_int($startFilePos)) {
            if (!(\is_bool($startFilePos) || \is_numeric($startFilePos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($startFilePos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($startFilePos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $startFilePos = (int) $startFilePos;
            }
        }
        if (!\is_int($startTokenPos)) {
            if (!(\is_bool($startTokenPos) || \is_numeric($startTokenPos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($startTokenPos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($startTokenPos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $startTokenPos = (int) $startTokenPos;
            }
        }
        if (!\is_int($endLine)) {
            if (!(\is_bool($endLine) || \is_numeric($endLine))) {
                throw new \TypeError(__METHOD__ . '(): Argument #5 ($endLine) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($endLine) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $endLine = (int) $endLine;
            }
        }
        if (!\is_int($endFilePos)) {
            if (!(\is_bool($endFilePos) || \is_numeric($endFilePos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #6 ($endFilePos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($endFilePos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $endFilePos = (int) $endFilePos;
            }
        }
        if (!\is_int($endTokenPos)) {
            if (!(\is_bool($endTokenPos) || \is_numeric($endTokenPos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #7 ($endTokenPos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($endTokenPos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $endTokenPos = (int) $endTokenPos;
            }
        }
        $this->text = $text;
        $this->startLine = $startLine;
        $this->startFilePos = $startFilePos;
        $this->startTokenPos = $startTokenPos;
        $this->endLine = $endLine;
        $this->endFilePos = $endFilePos;
        $this->endTokenPos = $endTokenPos;
    }
    /**
     * Gets the comment text.
     *
     * @return string The comment text (including comment delimiters like /*)
     */
    public function getText()
    {
        $phabelReturn = $this->text;
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
     * Gets the line number the comment started on.
     *
     * @return int Line number (or -1 if not available)
     */
    public function getStartLine()
    {
        $phabelReturn = $this->startLine;
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
     * Gets the file offset the comment started on.
     *
     * @return int File offset (or -1 if not available)
     */
    public function getStartFilePos()
    {
        $phabelReturn = $this->startFilePos;
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
     * Gets the token offset the comment started on.
     *
     * @return int Token offset (or -1 if not available)
     */
    public function getStartTokenPos()
    {
        $phabelReturn = $this->startTokenPos;
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
     * Gets the line number the comment ends on.
     *
     * @return int Line number (or -1 if not available)
     */
    public function getEndLine()
    {
        $phabelReturn = $this->endLine;
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
     * Gets the file offset the comment ends on.
     *
     * @return int File offset (or -1 if not available)
     */
    public function getEndFilePos()
    {
        $phabelReturn = $this->endFilePos;
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
     * Gets the token offset the comment ends on.
     *
     * @return int Token offset (or -1 if not available)
     */
    public function getEndTokenPos()
    {
        $phabelReturn = $this->endTokenPos;
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
     * Gets the line number the comment started on.
     *
     * @deprecated Use getStartLine() instead
     *
     * @return int Line number
     */
    public function getLine()
    {
        $phabelReturn = $this->startLine;
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
     * Gets the file offset the comment started on.
     *
     * @deprecated Use getStartFilePos() instead
     *
     * @return int File offset
     */
    public function getFilePos()
    {
        $phabelReturn = $this->startFilePos;
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
     * Gets the token offset the comment started on.
     *
     * @deprecated Use getStartTokenPos() instead
     *
     * @return int Token offset
     */
    public function getTokenPos()
    {
        $phabelReturn = $this->startTokenPos;
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
     * Gets the comment text.
     *
     * @return string The comment text (including comment delimiters like /*)
     */
    public function __toString()
    {
        $phabelReturn = $this->text;
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
     * Gets the reformatted comment text.
     *
     * "Reformatted" here means that we try to clean up the whitespace at the
     * starts of the lines. This is necessary because we receive the comments
     * without trailing whitespace on the first line, but with trailing whitespace
     * on all subsequent lines.
     *
     * @return mixed|string
     */
    public function getReformattedText()
    {
        $text = \trim($this->text);
        $newlinePos = \strpos($text, "\n");
        if (\false === $newlinePos) {
            // Single line comments don't need further processing
            return $text;
        } elseif (\preg_match('((*BSR_ANYCRLF)(*ANYCRLF)^.*(?:\\R\\s+\\*.*)+$)', $text)) {
            // Multi line comment of the type
            //
            //     /*
            //      * Some text.
            //      * Some more text.
            //      */
            //
            // is handled by replacing the whitespace sequences before the * by a single space
            return \preg_replace('(^\\s+\\*)m', ' *', $this->text);
        } elseif (\preg_match('(^/\\*\\*?\\s*[\\r\\n])', $text) && \preg_match('(\\n(\\s*)\\*/$)', $text, $matches)) {
            // Multi line comment of the type
            //
            //    /*
            //        Some text.
            //        Some more text.
            //    */
            //
            // is handled by removing the whitespace sequence on the line before the closing
            // */ on all lines. So if the last line is "    */", then "    " is removed at the
            // start of all lines.
            return \preg_replace('(^' . \preg_quote($matches[1]) . ')m', '', $text);
        } elseif (\preg_match('(^/\\*\\*?\\s*(?!\\s))', $text, $matches)) {
            // Multi line comment of the type
            //
            //     /* Some text.
            //        Some more text.
            //          Indented text.
            //        Even more text. */
            //
            // is handled by removing the difference between the shortest whitespace prefix on all
            // lines and the length of the "/* " opening sequence.
            $prefixLen = $this->getShortestWhitespacePrefixLen(\substr($text, $newlinePos + 1));
            $removeLen = $prefixLen - \strlen($matches[0]);
            return \preg_replace('(^\\s{' . $removeLen . '})m', '', $text);
        }
        // No idea how to format this comment, so simply return as is
        return $text;
    }
    /**
     * Get length of shortest whitespace prefix (at the start of a line).
     *
     * If there is a line with no prefix whitespace, 0 is a valid return value.
     *
     * @param string $str String to check
     * @return int Length in characters. Tabs count as single characters.
     */
    private function getShortestWhitespacePrefixLen($str)
    {
        if (!\is_string($str)) {
            if (!(\is_string($str) || \is_object($str) && \method_exists($str, '__toString') || (\is_bool($str) || \is_numeric($str)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($str) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($str) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $str = (string) $str;
            }
        }
        $lines = \explode("\n", $str);
        $shortestPrefixLen = \INF;
        foreach ($lines as $line) {
            \preg_match('(^\\s*)', $line, $matches);
            $prefixLen = \strlen($matches[0]);
            if ($prefixLen < $shortestPrefixLen) {
                $shortestPrefixLen = $prefixLen;
            }
        }
        $phabelReturn = $shortestPrefixLen;
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
     * @return       array
     * @psalm-return array{nodeType:string, text:mixed, line:mixed, filePos:mixed}
     */
    public function jsonSerialize()
    {
        // Technically not a node, but we make it look like one anyway
        $type = $this instanceof Comment\Doc ? 'Comment_Doc' : 'Comment';
        $phabelReturn = [
            'nodeType' => $type,
            'text' => $this->text,
            // TODO: Rename these to include "start".
            'line' => $this->startLine,
            'filePos' => $this->startFilePos,
            'tokenPos' => $this->startTokenPos,
            'endLine' => $this->endLine,
            'endFilePos' => $this->endFilePos,
            'endTokenPos' => $this->endTokenPos,
        ];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
