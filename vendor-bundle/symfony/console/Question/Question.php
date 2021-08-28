<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Question;

use Phabel\Symfony\Component\Console\Exception\InvalidArgumentException;
use Phabel\Symfony\Component\Console\Exception\LogicException;
/**
 * Represents a Question.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Question
{
    private $question;
    private $attempts;
    private $hidden = \false;
    private $hiddenFallback = \true;
    private $autocompleterCallback;
    private $validator;
    private $default;
    private $normalizer;
    private $trimmable = \true;
    private $multiline = \false;
    /**
     * @param string                     $question The question to ask to the user
     * @param string|bool|int|float|null $default  The default answer to return if the user enters nothing
     */
    public function __construct($question, $default = null)
    {
        if (!\is_string($question)) {
            if (!(\is_string($question) || \is_object($question) && \method_exists($question, '__toString') || (\is_bool($question) || \is_numeric($question)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($question) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($question) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $question = (string) $question;
            }
        }
        $this->question = $question;
        $this->default = $default;
    }
    /**
     * Returns the question.
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }
    /**
     * Returns the default answer.
     *
     * @return string|bool|int|float|null
     */
    public function getDefault()
    {
        return $this->default;
    }
    /**
     * Returns whether the user response accepts newline characters.
     */
    public function isMultiline()
    {
        $phabelReturn = $this->multiline;
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
     * Sets whether the user response should accept newline characters.
     *
     * @return $this
     */
    public function setMultiline($multiline)
    {
        if (!\is_bool($multiline)) {
            if (!(\is_bool($multiline) || \is_numeric($multiline) || \is_string($multiline))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($multiline) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($multiline) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $multiline = (bool) $multiline;
            }
        }
        $this->multiline = $multiline;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Returns whether the user response must be hidden.
     *
     * @return bool
     */
    public function isHidden()
    {
        return $this->hidden;
    }
    /**
     * Sets whether the user response must be hidden or not.
     *
     * @return $this
     *
     * @throws LogicException In case the autocompleter is also used
     */
    public function setHidden($hidden)
    {
        if (!\is_bool($hidden)) {
            if (!(\is_bool($hidden) || \is_numeric($hidden) || \is_string($hidden))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($hidden) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($hidden) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $hidden = (bool) $hidden;
            }
        }
        if ($this->autocompleterCallback) {
            throw new LogicException('A hidden question cannot use the autocompleter.');
        }
        $this->hidden = (bool) $hidden;
        return $this;
    }
    /**
     * In case the response can not be hidden, whether to fallback on non-hidden question or not.
     *
     * @return bool
     */
    public function isHiddenFallback()
    {
        return $this->hiddenFallback;
    }
    /**
     * Sets whether to fallback on non-hidden question if the response can not be hidden.
     *
     * @return $this
     */
    public function setHiddenFallback($fallback)
    {
        if (!\is_bool($fallback)) {
            if (!(\is_bool($fallback) || \is_numeric($fallback) || \is_string($fallback))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($fallback) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($fallback) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $fallback = (bool) $fallback;
            }
        }
        $this->hiddenFallback = (bool) $fallback;
        return $this;
    }
    /**
     * Gets values for the autocompleter.
     *
     * @return iterable|null
     */
    public function getAutocompleterValues()
    {
        $callback = $this->getAutocompleterCallback();
        return $callback ? $callback('') : null;
    }
    /**
     * Sets values for the autocompleter.
     *
     * @return $this
     *
     * @throws LogicException
     */
    public function setAutocompleterValues($values)
    {
        if (!(\is_array($values) || $values instanceof \Traversable || \is_null($values))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($values) must be of type ?iterable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($values) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (\is_array($values)) {
            $values = $this->isAssoc($values) ? \array_merge(\array_keys($values), \array_values($values)) : \array_values($values);
            $callback = static function () use($values) {
                return $values;
            };
        } elseif ($values instanceof \Traversable) {
            $valueCache = null;
            $callback = static function () use($values, &$valueCache) {
                return isset($valueCache) ? $valueCache : ($valueCache = \iterator_to_array($values, \false));
            };
        } else {
            $callback = null;
        }
        return $this->setAutocompleterCallback($callback);
    }
    /**
     * Gets the callback function used for the autocompleter.
     */
    public function getAutocompleterCallback()
    {
        $phabelReturn = $this->autocompleterCallback;
        if (!(\is_callable($phabelReturn) || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Sets the callback function used for the autocompleter.
     *
     * The callback is passed the user input as argument and should return an iterable of corresponding suggestions.
     *
     * @return $this
     */
    public function setAutocompleterCallback(callable $callback = null)
    {
        if ($this->hidden && null !== $callback) {
            throw new LogicException('A hidden question cannot use the autocompleter.');
        }
        $this->autocompleterCallback = $callback;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Sets a validator for the question.
     *
     * @return $this
     */
    public function setValidator(callable $validator = null)
    {
        $this->validator = $validator;
        return $this;
    }
    /**
     * Gets the validator for the question.
     *
     * @return callable|null
     */
    public function getValidator()
    {
        return $this->validator;
    }
    /**
     * Sets the maximum number of attempts.
     *
     * Null means an unlimited number of attempts.
     *
     * @return $this
     *
     * @throws InvalidArgumentException in case the number of attempts is invalid
     */
    public function setMaxAttempts($attempts)
    {
        if (!\is_null($attempts)) {
            if (!\is_int($attempts)) {
                if (!(\is_bool($attempts) || \is_numeric($attempts))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($attempts) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($attempts) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $attempts = (int) $attempts;
                }
            }
        }
        if (null !== $attempts) {
            $attempts = (int) $attempts;
            if ($attempts < 1) {
                throw new InvalidArgumentException('Maximum number of attempts must be a positive value.');
            }
        }
        $this->attempts = $attempts;
        return $this;
    }
    /**
     * Gets the maximum number of attempts.
     *
     * Null means an unlimited number of attempts.
     *
     * @return int|null
     */
    public function getMaxAttempts()
    {
        return $this->attempts;
    }
    /**
     * Sets a normalizer for the response.
     *
     * The normalizer can be a callable (a string), a closure or a class implementing __invoke.
     *
     * @return $this
     */
    public function setNormalizer(callable $normalizer)
    {
        $this->normalizer = $normalizer;
        return $this;
    }
    /**
     * Gets the normalizer for the response.
     *
     * The normalizer can ba a callable (a string), a closure or a class implementing __invoke.
     *
     * @return callable|null
     */
    public function getNormalizer()
    {
        return $this->normalizer;
    }
    protected function isAssoc(array $array)
    {
        return (bool) \count(\array_filter(\array_keys($array), 'is_string'));
    }
    public function isTrimmable()
    {
        $phabelReturn = $this->trimmable;
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
     * @return $this
     */
    public function setTrimmable($trimmable)
    {
        if (!\is_bool($trimmable)) {
            if (!(\is_bool($trimmable) || \is_numeric($trimmable) || \is_string($trimmable))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($trimmable) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($trimmable) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $trimmable = (bool) $trimmable;
            }
        }
        $this->trimmable = $trimmable;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
