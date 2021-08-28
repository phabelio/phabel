<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Input;

use Phabel\Symfony\Component\Console\Exception\InvalidArgumentException;
use Phabel\Symfony\Component\Console\Exception\LogicException;
/**
 * Represents a command line option.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class InputOption
{
    /**
     * Do not accept input for the option (e.g. --yell). This is the default behavior of options.
     */
    const VALUE_NONE = 1;
    /**
     * A value must be passed when the option is used (e.g. --iterations=5 or -i5).
     */
    const VALUE_REQUIRED = 2;
    /**
     * The option may or may not have a value (e.g. --yell or --yell=loud).
     */
    const VALUE_OPTIONAL = 4;
    /**
     * The option accepts multiple values (e.g. --dir=/foo --dir=/bar).
     */
    const VALUE_IS_ARRAY = 8;
    /**
     * The option may have either positive or negative value (e.g. --ansi or --no-ansi).
     */
    const VALUE_NEGATABLE = 16;
    private $name;
    private $shortcut;
    private $mode;
    private $default;
    private $description;
    /**
     * @param string                           $name        The option name
     * @param string|array|null                $shortcut    The shortcuts, can be null, a string of shortcuts delimited by | or an array of shortcuts
     * @param int|null                         $mode        The option mode: One of the VALUE_* constants
     * @param string                           $description A description text
     * @param string|bool|int|float|array|null $default     The default value (must be null for self::VALUE_NONE)
     *
     * @throws InvalidArgumentException If option mode is invalid or incompatible
     */
    public function __construct($name, $shortcut = null, $mode = null, $description = '', $default = null)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!\is_null($mode)) {
            if (!\is_int($mode)) {
                if (!(\is_bool($mode) || \is_numeric($mode))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($mode) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($mode) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $mode = (int) $mode;
                }
            }
        }
        if (!\is_string($description)) {
            if (!(\is_string($description) || \is_object($description) && \method_exists($description, '__toString') || (\is_bool($description) || \is_numeric($description)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($description) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($description) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $description = (string) $description;
            }
        }
        if (\str_starts_with($name, '--')) {
            $name = \substr($name, 2);
        }
        if (empty($name)) {
            throw new InvalidArgumentException('An option name cannot be empty.');
        }
        if (empty($shortcut)) {
            $shortcut = null;
        }
        if (null !== $shortcut) {
            if (\is_array($shortcut)) {
                $shortcut = \implode('|', $shortcut);
            }
            $shortcuts = \preg_split('{(\\|)-?}', \ltrim($shortcut, '-'));
            $shortcuts = \array_filter($shortcuts);
            $shortcut = \implode('|', $shortcuts);
            if (empty($shortcut)) {
                throw new InvalidArgumentException('An option shortcut cannot be empty.');
            }
        }
        if (null === $mode) {
            $mode = self::VALUE_NONE;
        } elseif ($mode >= self::VALUE_NEGATABLE << 1 || $mode < 1) {
            throw new InvalidArgumentException(\sprintf('Option mode "%s" is not valid.', $mode));
        }
        $this->name = $name;
        $this->shortcut = $shortcut;
        $this->mode = $mode;
        $this->description = $description;
        if ($this->isArray() && !$this->acceptValue()) {
            throw new InvalidArgumentException('Impossible to have an option mode VALUE_IS_ARRAY if the option does not accept a value.');
        }
        if ($this->isNegatable() && $this->acceptValue()) {
            throw new InvalidArgumentException('Impossible to have an option mode VALUE_NEGATABLE if the option also accepts a value.');
        }
        $this->setDefault($default);
    }
    /**
     * Returns the option shortcut.
     *
     * @return string|null The shortcut
     */
    public function getShortcut()
    {
        return $this->shortcut;
    }
    /**
     * Returns the option name.
     *
     * @return string The name
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Returns true if the option accepts a value.
     *
     * @return bool true if value mode is not self::VALUE_NONE, false otherwise
     */
    public function acceptValue()
    {
        return $this->isValueRequired() || $this->isValueOptional();
    }
    /**
     * Returns true if the option requires a value.
     *
     * @return bool true if value mode is self::VALUE_REQUIRED, false otherwise
     */
    public function isValueRequired()
    {
        return self::VALUE_REQUIRED === (self::VALUE_REQUIRED & $this->mode);
    }
    /**
     * Returns true if the option takes an optional value.
     *
     * @return bool true if value mode is self::VALUE_OPTIONAL, false otherwise
     */
    public function isValueOptional()
    {
        return self::VALUE_OPTIONAL === (self::VALUE_OPTIONAL & $this->mode);
    }
    /**
     * Returns true if the option can take multiple values.
     *
     * @return bool true if mode is self::VALUE_IS_ARRAY, false otherwise
     */
    public function isArray()
    {
        return self::VALUE_IS_ARRAY === (self::VALUE_IS_ARRAY & $this->mode);
    }
    public function isNegatable()
    {
        $phabelReturn = self::VALUE_NEGATABLE === (self::VALUE_NEGATABLE & $this->mode);
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
     * @param string|bool|int|float|array|null $default
     */
    public function setDefault($default = null)
    {
        if (self::VALUE_NONE === (self::VALUE_NONE & $this->mode) && null !== $default) {
            throw new LogicException('Cannot set a default value when using InputOption::VALUE_NONE mode.');
        }
        if ($this->isArray()) {
            if (null === $default) {
                $default = [];
            } elseif (!\is_array($default)) {
                throw new LogicException('A default value for an array option must be an array.');
            }
        }
        $this->default = $this->acceptValue() || $this->isNegatable() ? $default : \false;
    }
    /**
     * Returns the default value.
     *
     * @return string|bool|int|float|array|null
     */
    public function getDefault()
    {
        return $this->default;
    }
    /**
     * Returns the description text.
     *
     * @return string The description text
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Checks whether the given option equals this one.
     *
     * @return bool
     */
    public function equals(self $option)
    {
        return $option->getName() === $this->getName() && $option->getShortcut() === $this->getShortcut() && $option->getDefault() === $this->getDefault() && $option->isNegatable() === $this->isNegatable() && $option->isArray() === $this->isArray() && $option->isValueRequired() === $this->isValueRequired() && $option->isValueOptional() === $this->isValueOptional();
    }
}
