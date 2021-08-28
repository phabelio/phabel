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
/**
 * Represents a choice question.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ChoiceQuestion extends Question
{
    private $choices;
    private $multiselect = \false;
    private $prompt = ' > ';
    private $errorMessage = 'Value "%s" is invalid';
    /**
     * @param string $question The question to ask to the user
     * @param array  $choices  The list of available choices
     * @param mixed  $default  The default answer to return
     */
    public function __construct($question, $choices, $default = null)
    {
        if (!\is_array($choices)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($choices) must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($choices) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($question)) {
            if (!(\is_string($question) || \is_object($question) && \method_exists($question, '__toString') || (\is_bool($question) || \is_numeric($question)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($question) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($question) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $question = (string) $question;
            }
        }
        if (!$choices) {
            throw new \LogicException('Choice question must have at least 1 choice available.');
        }
        parent::__construct($question, $default);
        $this->choices = $choices;
        $this->setValidator($this->getDefaultValidator());
        $this->setAutocompleterValues($choices);
    }
    /**
     * Returns available choices.
     *
     * @return array
     */
    public function getChoices()
    {
        return $this->choices;
    }
    /**
     * Sets multiselect option.
     *
     * When multiselect is set to true, multiple choices can be answered.
     *
     * @return $this
     */
    public function setMultiselect($multiselect)
    {
        if (!\is_bool($multiselect)) {
            if (!(\is_bool($multiselect) || \is_numeric($multiselect) || \is_string($multiselect))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($multiselect) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($multiselect) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $multiselect = (bool) $multiselect;
            }
        }
        $this->multiselect = $multiselect;
        $this->setValidator($this->getDefaultValidator());
        return $this;
    }
    /**
     * Returns whether the choices are multiselect.
     *
     * @return bool
     */
    public function isMultiselect()
    {
        return $this->multiselect;
    }
    /**
     * Gets the prompt for choices.
     *
     * @return string
     */
    public function getPrompt()
    {
        return $this->prompt;
    }
    /**
     * Sets the prompt for choices.
     *
     * @return $this
     */
    public function setPrompt($prompt)
    {
        if (!\is_string($prompt)) {
            if (!(\is_string($prompt) || \is_object($prompt) && \method_exists($prompt, '__toString') || (\is_bool($prompt) || \is_numeric($prompt)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($prompt) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($prompt) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $prompt = (string) $prompt;
            }
        }
        $this->prompt = $prompt;
        return $this;
    }
    /**
     * Sets the error message for invalid values.
     *
     * The error message has a string placeholder (%s) for the invalid value.
     *
     * @return $this
     */
    public function setErrorMessage($errorMessage)
    {
        if (!\is_string($errorMessage)) {
            if (!(\is_string($errorMessage) || \is_object($errorMessage) && \method_exists($errorMessage, '__toString') || (\is_bool($errorMessage) || \is_numeric($errorMessage)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($errorMessage) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($errorMessage) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $errorMessage = (string) $errorMessage;
            }
        }
        $this->errorMessage = $errorMessage;
        $this->setValidator($this->getDefaultValidator());
        return $this;
    }
    private function getDefaultValidator()
    {
        $choices = $this->choices;
        $errorMessage = $this->errorMessage;
        $multiselect = $this->multiselect;
        $isAssoc = $this->isAssoc($choices);
        $phabelReturn = function ($selected) use($choices, $errorMessage, $multiselect, $isAssoc) {
            if ($multiselect) {
                // Check for a separated comma values
                if (!\preg_match('/^[^,]+(?:,[^,]+)*$/', $selected, $matches)) {
                    throw new InvalidArgumentException(\sprintf($errorMessage, $selected));
                }
                $selectedChoices = \explode(',', $selected);
            } else {
                $selectedChoices = [$selected];
            }
            if ($this->isTrimmable()) {
                foreach ($selectedChoices as $k => $v) {
                    $selectedChoices[$k] = \trim($v);
                }
            }
            $multiselectChoices = [];
            foreach ($selectedChoices as $value) {
                $results = [];
                foreach ($choices as $key => $choice) {
                    if ($choice === $value) {
                        $results[] = $key;
                    }
                }
                if (\count($results) > 1) {
                    throw new InvalidArgumentException(\sprintf('The provided answer is ambiguous. Value should be one of "%s".', \implode('" or "', $results)));
                }
                $result = \array_search($value, $choices);
                if (!$isAssoc) {
                    if (\false !== $result) {
                        $result = $choices[$result];
                    } elseif (isset($choices[$value])) {
                        $result = $choices[$value];
                    }
                } elseif (\false === $result && isset($choices[$value])) {
                    $result = $value;
                }
                if (\false === $result) {
                    throw new InvalidArgumentException(\sprintf($errorMessage, $value));
                }
                // For associative choices, consistently return the key as string:
                $multiselectChoices[] = $isAssoc ? (string) $result : $result;
            }
            if ($multiselect) {
                return $multiselectChoices;
            }
            return \current($multiselectChoices);
        };
        if (!\is_callable($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
