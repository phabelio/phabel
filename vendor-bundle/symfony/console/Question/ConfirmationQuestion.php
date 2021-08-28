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

/**
 * Represents a yes/no question.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ConfirmationQuestion extends Question
{
    private $trueAnswerRegex;
    /**
     * @param string $question        The question to ask to the user
     * @param bool   $default         The default answer to return, true or false
     * @param string $trueAnswerRegex A regex to match the "yes" answer
     */
    public function __construct($question, $default = \true, $trueAnswerRegex = '/^y/i')
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
        if (!\is_string($trueAnswerRegex)) {
            if (!(\is_string($trueAnswerRegex) || \is_object($trueAnswerRegex) && \method_exists($trueAnswerRegex, '__toString') || (\is_bool($trueAnswerRegex) || \is_numeric($trueAnswerRegex)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($trueAnswerRegex) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($trueAnswerRegex) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $trueAnswerRegex = (string) $trueAnswerRegex;
            }
        }
        parent::__construct($question, $default);
        $this->trueAnswerRegex = $trueAnswerRegex;
        $this->setNormalizer($this->getDefaultNormalizer());
    }
    /**
     * Returns the default answer normalizer.
     */
    private function getDefaultNormalizer()
    {
        $default = $this->getDefault();
        $regex = $this->trueAnswerRegex;
        $phabelReturn = function ($answer) use($default, $regex) {
            if (\is_bool($answer)) {
                return $answer;
            }
            $answerIsTrue = (bool) \preg_match($regex, $answer);
            if (\false === $default) {
                return $answer && $answerIsTrue;
            }
            return '' === $answer || $answerIsTrue;
        };
        if (!\is_callable($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
