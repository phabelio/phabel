<?php

namespace Phabel\PhpParser\Internal;

/**
 * Provides operations on token streams, for use by pretty printer.
 *
 * @internal
 */
class TokenStream
{
    /** @var array Tokens (in token_get_all format) */
    private $tokens;
    /** @var int[] Map from position to indentation */
    private $indentMap;
    /**
     * Create token stream instance.
     *
     * @param array $tokens Tokens in token_get_all() format
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
        $this->indentMap = $this->calcIndentMap();
    }
    /**
     * Whether the given position is immediately surrounded by parenthesis.
     *
     * @param int $startPos Start position
     * @param int $endPos   End position
     *
     * @return bool
     */
    public function haveParens($startPos, $endPos)
    {
        if (!\is_int($startPos)) {
            if (!(\is_bool($startPos) || \is_numeric($startPos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($startPos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($startPos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $startPos = (int) $startPos;
            }
        }
        if (!\is_int($endPos)) {
            if (!(\is_bool($endPos) || \is_numeric($endPos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($endPos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($endPos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $endPos = (int) $endPos;
            }
        }
        $phabelReturn = $this->haveTokenImmediatelyBefore($startPos, '(') && $this->haveTokenImmediatelyAfter($endPos, ')');
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Whether the given position is immediately surrounded by braces.
     *
     * @param int $startPos Start position
     * @param int $endPos   End position
     *
     * @return bool
     */
    public function haveBraces($startPos, $endPos)
    {
        if (!\is_int($startPos)) {
            if (!(\is_bool($startPos) || \is_numeric($startPos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($startPos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($startPos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $startPos = (int) $startPos;
            }
        }
        if (!\is_int($endPos)) {
            if (!(\is_bool($endPos) || \is_numeric($endPos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($endPos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($endPos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $endPos = (int) $endPos;
            }
        }
        $phabelReturn = ($this->haveTokenImmediatelyBefore($startPos, '{') || $this->haveTokenImmediatelyBefore($startPos, \T_CURLY_OPEN)) && $this->haveTokenImmediatelyAfter($endPos, '}');
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Check whether the position is directly preceded by a certain token type.
     *
     * During this check whitespace and comments are skipped.
     *
     * @param int        $pos               Position before which the token should occur
     * @param int|string $expectedTokenType Token to check for
     *
     * @return bool Whether the expected token was found
     */
    public function haveTokenImmediatelyBefore($pos, $expectedTokenType)
    {
        if (!\is_int($pos)) {
            if (!(\is_bool($pos) || \is_numeric($pos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($pos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($pos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $pos = (int) $pos;
            }
        }
        $tokens = $this->tokens;
        $pos--;
        for (; $pos >= 0; $pos--) {
            $tokenType = $tokens[$pos][0];
            if ($tokenType === $expectedTokenType) {
                $phabelReturn = \true;
                if (!\is_bool($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (bool) $phabelReturn;
                    }
                }
                return $phabelReturn;
            }
            if ($tokenType !== \T_WHITESPACE && $tokenType !== \T_COMMENT && $tokenType !== \T_DOC_COMMENT) {
                break;
            }
        }
        $phabelReturn = \false;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Check whether the position is directly followed by a certain token type.
     *
     * During this check whitespace and comments are skipped.
     *
     * @param int        $pos               Position after which the token should occur
     * @param int|string $expectedTokenType Token to check for
     *
     * @return bool Whether the expected token was found
     */
    public function haveTokenImmediatelyAfter($pos, $expectedTokenType)
    {
        if (!\is_int($pos)) {
            if (!(\is_bool($pos) || \is_numeric($pos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($pos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($pos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $pos = (int) $pos;
            }
        }
        $tokens = $this->tokens;
        $pos++;
        for (; $pos < \count($tokens); $pos++) {
            $tokenType = $tokens[$pos][0];
            if ($tokenType === $expectedTokenType) {
                $phabelReturn = \true;
                if (!\is_bool($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (bool) $phabelReturn;
                    }
                }
                return $phabelReturn;
            }
            if ($tokenType !== \T_WHITESPACE && $tokenType !== \T_COMMENT && $tokenType !== \T_DOC_COMMENT) {
                break;
            }
        }
        $phabelReturn = \false;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function skipLeft($pos, $skipTokenType)
    {
        if (!\is_int($pos)) {
            if (!(\is_bool($pos) || \is_numeric($pos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($pos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($pos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $pos = (int) $pos;
            }
        }
        $tokens = $this->tokens;
        $pos = $this->skipLeftWhitespace($pos);
        if ($skipTokenType === \T_WHITESPACE) {
            return $pos;
        }
        if ($tokens[$pos][0] !== $skipTokenType) {
            // Shouldn't happen. The skip token MUST be there
            throw new \Exception('Encountered unexpected token');
        }
        $pos--;
        return $this->skipLeftWhitespace($pos);
    }
    public function skipRight($pos, $skipTokenType)
    {
        if (!\is_int($pos)) {
            if (!(\is_bool($pos) || \is_numeric($pos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($pos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($pos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $pos = (int) $pos;
            }
        }
        $tokens = $this->tokens;
        $pos = $this->skipRightWhitespace($pos);
        if ($skipTokenType === \T_WHITESPACE) {
            return $pos;
        }
        if ($tokens[$pos][0] !== $skipTokenType) {
            // Shouldn't happen. The skip token MUST be there
            throw new \Exception('Encountered unexpected token');
        }
        $pos++;
        return $this->skipRightWhitespace($pos);
    }
    /**
     * Return first non-whitespace token position smaller or equal to passed position.
     *
     * @param int $pos Token position
     * @return int Non-whitespace token position
     */
    public function skipLeftWhitespace($pos)
    {
        if (!\is_int($pos)) {
            if (!(\is_bool($pos) || \is_numeric($pos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($pos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($pos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $pos = (int) $pos;
            }
        }
        $tokens = $this->tokens;
        for (; $pos >= 0; $pos--) {
            $type = $tokens[$pos][0];
            if ($type !== \T_WHITESPACE && $type !== \T_COMMENT && $type !== \T_DOC_COMMENT) {
                break;
            }
        }
        return $pos;
    }
    /**
     * Return first non-whitespace position greater or equal to passed position.
     *
     * @param int $pos Token position
     * @return int Non-whitespace token position
     */
    public function skipRightWhitespace($pos)
    {
        if (!\is_int($pos)) {
            if (!(\is_bool($pos) || \is_numeric($pos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($pos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($pos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $pos = (int) $pos;
            }
        }
        $tokens = $this->tokens;
        for ($count = \count($tokens); $pos < $count; $pos++) {
            $type = $tokens[$pos][0];
            if ($type !== \T_WHITESPACE && $type !== \T_COMMENT && $type !== \T_DOC_COMMENT) {
                break;
            }
        }
        return $pos;
    }
    public function findRight($pos, $findTokenType)
    {
        if (!\is_int($pos)) {
            if (!(\is_bool($pos) || \is_numeric($pos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($pos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($pos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $pos = (int) $pos;
            }
        }
        $tokens = $this->tokens;
        for ($count = \count($tokens); $pos < $count; $pos++) {
            $type = $tokens[$pos][0];
            if ($type === $findTokenType) {
                return $pos;
            }
        }
        return -1;
    }
    /**
     * Whether the given position range contains a certain token type.
     *
     * @param int $startPos Starting position (inclusive)
     * @param int $endPos Ending position (exclusive)
     * @param int|string $tokenType Token type to look for
     * @return bool Whether the token occurs in the given range
     */
    public function haveTokenInRange($startPos, $endPos, $tokenType)
    {
        if (!\is_int($startPos)) {
            if (!(\is_bool($startPos) || \is_numeric($startPos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($startPos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($startPos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $startPos = (int) $startPos;
            }
        }
        if (!\is_int($endPos)) {
            if (!(\is_bool($endPos) || \is_numeric($endPos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($endPos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($endPos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $endPos = (int) $endPos;
            }
        }
        $tokens = $this->tokens;
        for ($pos = $startPos; $pos < $endPos; $pos++) {
            if ($tokens[$pos][0] === $tokenType) {
                return \true;
            }
        }
        return \false;
    }
    public function haveBracesInRange($startPos, $endPos)
    {
        if (!\is_int($startPos)) {
            if (!(\is_bool($startPos) || \is_numeric($startPos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($startPos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($startPos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $startPos = (int) $startPos;
            }
        }
        if (!\is_int($endPos)) {
            if (!(\is_bool($endPos) || \is_numeric($endPos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($endPos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($endPos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $endPos = (int) $endPos;
            }
        }
        return $this->haveTokenInRange($startPos, $endPos, '{') || $this->haveTokenInRange($startPos, $endPos, \T_CURLY_OPEN) || $this->haveTokenInRange($startPos, $endPos, '}');
    }
    /**
     * Get indentation before token position.
     *
     * @param int $pos Token position
     *
     * @return int Indentation depth (in spaces)
     */
    public function getIndentationBefore($pos)
    {
        if (!\is_int($pos)) {
            if (!(\is_bool($pos) || \is_numeric($pos))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($pos) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($pos) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $pos = (int) $pos;
            }
        }
        $phabelReturn = $this->indentMap[$pos];
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
     * Get the code corresponding to a token offset range, optionally adjusted for indentation.
     *
     * @param int $from   Token start position (inclusive)
     * @param int $to     Token end position (exclusive)
     * @param int $indent By how much the code should be indented (can be negative as well)
     *
     * @return string Code corresponding to token range, adjusted for indentation
     */
    public function getTokenCode($from, $to, $indent)
    {
        if (!\is_int($from)) {
            if (!(\is_bool($from) || \is_numeric($from))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($from) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($from) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $from = (int) $from;
            }
        }
        if (!\is_int($to)) {
            if (!(\is_bool($to) || \is_numeric($to))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($to) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($to) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $to = (int) $to;
            }
        }
        if (!\is_int($indent)) {
            if (!(\is_bool($indent) || \is_numeric($indent))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($indent) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($indent) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $indent = (int) $indent;
            }
        }
        $tokens = $this->tokens;
        $result = '';
        for ($pos = $from; $pos < $to; $pos++) {
            $token = $tokens[$pos];
            if (\is_array($token)) {
                $type = $token[0];
                $content = $token[1];
                if ($type === \T_CONSTANT_ENCAPSED_STRING || $type === \T_ENCAPSED_AND_WHITESPACE) {
                    $result .= $content;
                } else {
                    // TODO Handle non-space indentation
                    if ($indent < 0) {
                        $result .= \str_replace("\n" . \str_repeat(" ", -$indent), "\n", $content);
                    } elseif ($indent > 0) {
                        $result .= \str_replace("\n", "\n" . \str_repeat(" ", $indent), $content);
                    } else {
                        $result .= $content;
                    }
                }
            } else {
                $result .= $token;
            }
        }
        $phabelReturn = $result;
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
     * Precalculate the indentation at every token position.
     *
     * @return int[] Token position to indentation map
     */
    private function calcIndentMap()
    {
        $indentMap = [];
        $indent = 0;
        foreach ($this->tokens as $token) {
            $indentMap[] = $indent;
            if ($token[0] === \T_WHITESPACE) {
                $content = $token[1];
                $newlinePos = \strrpos($content, "\n");
                if (\false !== $newlinePos) {
                    $indent = \strlen($content) - $newlinePos - 1;
                }
            }
        }
        // Add a sentinel for one past end of the file
        $indentMap[] = $indent;
        return $indentMap;
    }
}
