<?php

namespace Phabel\PhpParser\Lexer;

use Phabel\PhpParser\Error;
use Phabel\PhpParser\ErrorHandler;
use Phabel\PhpParser\Lexer;
use Phabel\PhpParser\Lexer\TokenEmulator\AttributeEmulator;
use Phabel\PhpParser\Lexer\TokenEmulator\CoaleseEqualTokenEmulator;
use Phabel\PhpParser\Lexer\TokenEmulator\FlexibleDocStringEmulator;
use Phabel\PhpParser\Lexer\TokenEmulator\FnTokenEmulator;
use Phabel\PhpParser\Lexer\TokenEmulator\MatchTokenEmulator;
use Phabel\PhpParser\Lexer\TokenEmulator\NullsafeTokenEmulator;
use Phabel\PhpParser\Lexer\TokenEmulator\NumericLiteralSeparatorEmulator;
use Phabel\PhpParser\Lexer\TokenEmulator\ReverseEmulator;
use Phabel\PhpParser\Lexer\TokenEmulator\TokenEmulator;
use Phabel\PhpParser\Parser\Tokens;
class Emulative extends Lexer
{
    const PHP_7_3 = '7.3dev';
    const PHP_7_4 = '7.4dev';
    const PHP_8_0 = '8.0dev';
    /** @var mixed[] Patches used to reverse changes introduced in the code */
    private $patches = [];
    /** @var TokenEmulator[] */
    private $emulators = [];
    /** @var string */
    private $targetPhpVersion;
    /**
     * @param mixed[] $options Lexer options. In addition to the usual options,
     *                         accepts a 'phpVersion' string that specifies the
     *                         version to emulated. Defaults to newest supported.
     */
    public function __construct(array $options = [])
    {
        $this->targetPhpVersion = isset($options['phpVersion']) ? $options['phpVersion'] : Emulative::PHP_8_0;
        unset($options['phpVersion']);
        parent::__construct($options);
        $emulators = [new FlexibleDocStringEmulator(), new FnTokenEmulator(), new MatchTokenEmulator(), new CoaleseEqualTokenEmulator(), new NumericLiteralSeparatorEmulator(), new NullsafeTokenEmulator(), new AttributeEmulator()];
        // Collect emulators that are relevant for the PHP version we're running
        // and the PHP version we're targeting for emulation.
        foreach ($emulators as $emulator) {
            $emulatorPhpVersion = $emulator->getPhpVersion();
            if ($this->isForwardEmulationNeeded($emulatorPhpVersion)) {
                $this->emulators[] = $emulator;
            } else {
                if ($this->isReverseEmulationNeeded($emulatorPhpVersion)) {
                    $this->emulators[] = new ReverseEmulator($emulator);
                }
            }
        }
    }
    public function startLexing($code, ErrorHandler $errorHandler = null)
    {
        if (!\is_string($code)) {
            if (!(\is_string($code) || \is_object($code) && \method_exists($code, '__toString') || (\is_bool($code) || \is_numeric($code)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($code) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($code) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $code = (string) $code;
            }
        }
        $emulators = \array_filter($this->emulators, function ($emulator) use($code) {
            return $emulator->isEmulationNeeded($code);
        });
        if (empty($emulators)) {
            // Nothing to emulate, yay
            parent::startLexing($code, $errorHandler);
            return;
        }
        $this->patches = [];
        foreach ($emulators as $emulator) {
            $code = $emulator->preprocessCode($code, $this->patches);
        }
        $collector = new ErrorHandler\Collecting();
        parent::startLexing($code, $collector);
        $this->sortPatches();
        $this->fixupTokens();
        $errors = $collector->getErrors();
        if (!empty($errors)) {
            $this->fixupErrors($errors);
            foreach ($errors as $error) {
                $errorHandler->handleError($error);
            }
        }
        foreach ($emulators as $emulator) {
            $this->tokens = $emulator->emulate($code, $this->tokens);
        }
    }
    private function isForwardEmulationNeeded($emulatorPhpVersion)
    {
        if (!\is_string($emulatorPhpVersion)) {
            if (!(\is_string($emulatorPhpVersion) || \is_object($emulatorPhpVersion) && \method_exists($emulatorPhpVersion, '__toString') || (\is_bool($emulatorPhpVersion) || \is_numeric($emulatorPhpVersion)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($emulatorPhpVersion) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($emulatorPhpVersion) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $emulatorPhpVersion = (string) $emulatorPhpVersion;
            }
        }
        $phabelReturn = \version_compare(\PHP_VERSION, $emulatorPhpVersion, '<') && \version_compare($this->targetPhpVersion, $emulatorPhpVersion, '>=');
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    private function isReverseEmulationNeeded($emulatorPhpVersion)
    {
        if (!\is_string($emulatorPhpVersion)) {
            if (!(\is_string($emulatorPhpVersion) || \is_object($emulatorPhpVersion) && \method_exists($emulatorPhpVersion, '__toString') || (\is_bool($emulatorPhpVersion) || \is_numeric($emulatorPhpVersion)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($emulatorPhpVersion) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($emulatorPhpVersion) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $emulatorPhpVersion = (string) $emulatorPhpVersion;
            }
        }
        $phabelReturn = \version_compare(\PHP_VERSION, $emulatorPhpVersion, '>=') && \version_compare($this->targetPhpVersion, $emulatorPhpVersion, '<');
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    private function sortPatches()
    {
        // Patches may be contributed by different emulators.
        // Make sure they are sorted by increasing patch position.
        \usort($this->patches, function ($p1, $p2) {
            return \Phabel\Target\Php70\SpaceshipOperatorReplacer::spaceship($p1[0], $p2[0]);
        });
    }
    private function fixupTokens()
    {
        if (\count($this->patches) === 0) {
            return;
        }
        // Load first patch
        $patchIdx = 0;
        list($patchPos, $patchType, $patchText) = $this->patches[$patchIdx];
        // We use a manual loop over the tokens, because we modify the array on the fly
        $pos = 0;
        for ($i = 0, $c = \count($this->tokens); $i < $c; $i++) {
            $token = $this->tokens[$i];
            if (\is_string($token)) {
                if ($patchPos === $pos) {
                    // Only support replacement for string tokens.
                    \assert($patchType === 'replace');
                    $this->tokens[$i] = $patchText;
                    // Fetch the next patch
                    $patchIdx++;
                    if ($patchIdx >= \count($this->patches)) {
                        // No more patches, we're done
                        return;
                    }
                    list($patchPos, $patchType, $patchText) = $this->patches[$patchIdx];
                }
                $pos += \strlen($token);
                continue;
            }
            $len = \strlen($token[1]);
            $posDelta = 0;
            while ($patchPos >= $pos && $patchPos < $pos + $len) {
                $patchTextLen = \strlen($patchText);
                if ($patchType === 'remove') {
                    if ($patchPos === $pos && $patchTextLen === $len) {
                        // Remove token entirely
                        \array_splice($this->tokens, $i, 1, []);
                        $i--;
                        $c--;
                    } else {
                        // Remove from token string
                        $this->tokens[$i][1] = \substr_replace($token[1], '', $patchPos - $pos + $posDelta, $patchTextLen);
                        $posDelta -= $patchTextLen;
                    }
                } elseif ($patchType === 'add') {
                    // Insert into the token string
                    $this->tokens[$i][1] = \substr_replace($token[1], $patchText, $patchPos - $pos + $posDelta, 0);
                    $posDelta += $patchTextLen;
                } else {
                    if ($patchType === 'replace') {
                        // Replace inside the token string
                        $this->tokens[$i][1] = \substr_replace($token[1], $patchText, $patchPos - $pos + $posDelta, $patchTextLen);
                    } else {
                        \assert(\false);
                    }
                }
                // Fetch the next patch
                $patchIdx++;
                if ($patchIdx >= \count($this->patches)) {
                    // No more patches, we're done
                    return;
                }
                list($patchPos, $patchType, $patchText) = $this->patches[$patchIdx];
                // Multiple patches may apply to the same token. Reload the current one to check
                // If the new patch applies
                $token = $this->tokens[$i];
            }
            $pos += $len;
        }
        // A patch did not apply
        \assert(\false);
    }
    /**
     * Fixup line and position information in errors.
     *
     * @param Error[] $errors
     */
    private function fixupErrors(array $errors)
    {
        foreach ($errors as $error) {
            $attrs = $error->getAttributes();
            $posDelta = 0;
            $lineDelta = 0;
            foreach ($this->patches as $patch) {
                list($patchPos, $patchType, $patchText) = $patch;
                if ($patchPos >= $attrs['startFilePos']) {
                    // No longer relevant
                    break;
                }
                if ($patchType === 'add') {
                    $posDelta += \strlen($patchText);
                    $lineDelta += \substr_count($patchText, "\n");
                } else {
                    if ($patchType === 'remove') {
                        $posDelta -= \strlen($patchText);
                        $lineDelta -= \substr_count($patchText, "\n");
                    }
                }
            }
            $attrs['startFilePos'] += $posDelta;
            $attrs['endFilePos'] += $posDelta;
            $attrs['startLine'] += $lineDelta;
            $attrs['endLine'] += $lineDelta;
            $error->setAttributes($attrs);
        }
    }
}
