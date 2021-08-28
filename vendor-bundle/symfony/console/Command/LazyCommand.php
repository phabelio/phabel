<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Command;

use Phabel\Symfony\Component\Console\Application;
use Phabel\Symfony\Component\Console\Helper\HelperSet;
use Phabel\Symfony\Component\Console\Input\InputDefinition;
use Phabel\Symfony\Component\Console\Input\InputInterface;
use Phabel\Symfony\Component\Console\Output\OutputInterface;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
final class LazyCommand extends Command
{
    private $command;
    private $isEnabled;
    public function __construct($name, array $aliases, $description, $isHidden, \Closure $commandFactory, $isEnabled = \true)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!\is_string($description)) {
            if (!(\is_string($description) || \is_object($description) && \method_exists($description, '__toString') || (\is_bool($description) || \is_numeric($description)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($description) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($description) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $description = (string) $description;
            }
        }
        if (!\is_bool($isHidden)) {
            if (!(\is_bool($isHidden) || \is_numeric($isHidden) || \is_string($isHidden))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($isHidden) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($isHidden) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $isHidden = (bool) $isHidden;
            }
        }
        if (!\is_null($isEnabled)) {
            if (!\is_bool($isEnabled)) {
                if (!(\is_bool($isEnabled) || \is_numeric($isEnabled) || \is_string($isEnabled))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #6 ($isEnabled) must be of type ?bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($isEnabled) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $isEnabled = (bool) $isEnabled;
                }
            }
        }
        $this->setName($name)->setAliases($aliases)->setHidden($isHidden)->setDescription($description);
        $this->command = $commandFactory;
        $this->isEnabled = $isEnabled;
    }
    public function ignoreValidationErrors()
    {
        $this->getCommand()->ignoreValidationErrors();
    }
    public function setApplication(Application $application = null)
    {
        if ($this->command instanceof parent) {
            $this->command->setApplication($application);
        }
        parent::setApplication($application);
    }
    public function setHelperSet(HelperSet $helperSet)
    {
        if ($this->command instanceof parent) {
            $this->command->setHelperSet($helperSet);
        }
        parent::setHelperSet($helperSet);
    }
    public function isEnabled()
    {
        $phabelReturn = isset($this->isEnabled) ? $this->isEnabled : $this->getCommand()->isEnabled();
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function run(InputInterface $input, OutputInterface $output)
    {
        $phabelReturn = $this->getCommand()->run($input, $output);
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
     * @return $this
     */
    public function setCode(callable $code)
    {
        $this->getCommand()->setCode($code);
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @internal
     */
    public function mergeApplicationDefinition($mergeArgs = \true)
    {
        if (!\is_bool($mergeArgs)) {
            if (!(\is_bool($mergeArgs) || \is_numeric($mergeArgs) || \is_string($mergeArgs))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($mergeArgs) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($mergeArgs) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $mergeArgs = (bool) $mergeArgs;
            }
        }
        $this->getCommand()->mergeApplicationDefinition($mergeArgs);
    }
    /**
     * @return $this
     */
    public function setDefinition($definition)
    {
        $this->getCommand()->setDefinition($definition);
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getDefinition()
    {
        $phabelReturn = $this->getCommand()->getDefinition();
        if (!$phabelReturn instanceof InputDefinition) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type InputDefinition, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getNativeDefinition()
    {
        $phabelReturn = $this->getCommand()->getNativeDefinition();
        if (!$phabelReturn instanceof InputDefinition) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type InputDefinition, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return $this
     */
    public function addArgument($name, $mode = null, $description = '', $default = null)
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
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($mode) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($mode) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $mode = (int) $mode;
                }
            }
        }
        if (!\is_string($description)) {
            if (!(\is_string($description) || \is_object($description) && \method_exists($description, '__toString') || (\is_bool($description) || \is_numeric($description)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($description) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($description) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $description = (string) $description;
            }
        }
        $this->getCommand()->addArgument($name, $mode, $description, $default);
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return $this
     */
    public function addOption($name, $shortcut = null, $mode = null, $description = '', $default = null)
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
        $this->getCommand()->addOption($name, $shortcut, $mode, $description, $default);
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return $this
     */
    public function setProcessTitle($title)
    {
        if (!\is_string($title)) {
            if (!(\is_string($title) || \is_object($title) && \method_exists($title, '__toString') || (\is_bool($title) || \is_numeric($title)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($title) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($title) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $title = (string) $title;
            }
        }
        $this->getCommand()->setProcessTitle($title);
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return $this
     */
    public function setHelp($help)
    {
        if (!\is_string($help)) {
            if (!(\is_string($help) || \is_object($help) && \method_exists($help, '__toString') || (\is_bool($help) || \is_numeric($help)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($help) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($help) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $help = (string) $help;
            }
        }
        $this->getCommand()->setHelp($help);
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getHelp()
    {
        $phabelReturn = $this->getCommand()->getHelp();
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function getProcessedHelp()
    {
        $phabelReturn = $this->getCommand()->getProcessedHelp();
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function getSynopsis($short = \false)
    {
        if (!\is_bool($short)) {
            if (!(\is_bool($short) || \is_numeric($short) || \is_string($short))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($short) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($short) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $short = (bool) $short;
            }
        }
        $phabelReturn = $this->getCommand()->getSynopsis($short);
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
     * @return $this
     */
    public function addUsage($usage)
    {
        if (!\is_string($usage)) {
            if (!(\is_string($usage) || \is_object($usage) && \method_exists($usage, '__toString') || (\is_bool($usage) || \is_numeric($usage)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($usage) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($usage) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $usage = (string) $usage;
            }
        }
        $this->getCommand()->addUsage($usage);
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getUsages()
    {
        $phabelReturn = $this->getCommand()->getUsages();
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return mixed
     */
    public function getHelper($name)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        return $this->getCommand()->getHelper($name);
    }
    public function getCommand()
    {
        if (!$this->command instanceof \Closure) {
            $phabelReturn = $this->command;
            if (!$phabelReturn instanceof parent) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabel_9b2fc8900731f1a7 = $this->command;
        $command = $this->command = $phabel_9b2fc8900731f1a7();
        $command->setApplication($this->getApplication());
        if (null !== $this->getHelperSet()) {
            $command->setHelperSet($this->getHelperSet());
        }
        $command->setName($this->getName())->setAliases($this->getAliases())->setHidden($this->isHidden())->setDescription($this->getDescription());
        // Will throw if the command is not correctly initialized.
        $command->getDefinition();
        $phabelReturn = $command;
        if (!$phabelReturn instanceof parent) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . parent::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
