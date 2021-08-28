<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Descriptor;

use Phabel\Symfony\Component\Console\Application;
use Phabel\Symfony\Component\Console\Command\Command;
use Phabel\Symfony\Component\Console\Exception\CommandNotFoundException;
/**
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class ApplicationDescription
{
    const GLOBAL_NAMESPACE = '_global';
    private $application;
    private $namespace;
    private $showHidden;
    /**
     * @var array
     */
    private $namespaces;
    /**
     * @var Command[]
     */
    private $commands;
    /**
     * @var Command[]
     */
    private $aliases;
    public function __construct(Application $application, $namespace = null, $showHidden = \false)
    {
        if (!\is_null($namespace)) {
            if (!\is_string($namespace)) {
                if (!(\is_string($namespace) || \is_object($namespace) && \method_exists($namespace, '__toString') || (\is_bool($namespace) || \is_numeric($namespace)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($namespace) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($namespace) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $namespace = (string) $namespace;
                }
            }
        }
        if (!\is_bool($showHidden)) {
            if (!(\is_bool($showHidden) || \is_numeric($showHidden) || \is_string($showHidden))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($showHidden) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($showHidden) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $showHidden = (bool) $showHidden;
            }
        }
        $this->application = $application;
        $this->namespace = $namespace;
        $this->showHidden = $showHidden;
    }
    public function getNamespaces()
    {
        if (null === $this->namespaces) {
            $this->inspectApplication();
        }
        $phabelReturn = $this->namespaces;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return Command[]
     */
    public function getCommands()
    {
        if (null === $this->commands) {
            $this->inspectApplication();
        }
        $phabelReturn = $this->commands;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @throws CommandNotFoundException
     */
    public function getCommand($name)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!isset($this->commands[$name]) && !isset($this->aliases[$name])) {
            throw new CommandNotFoundException(\sprintf('Command "%s" does not exist.', $name));
        }
        $phabelReturn = isset($this->commands[$name]) ? $this->commands[$name] : $this->aliases[$name];
        if (!$phabelReturn instanceof Command) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Command, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function inspectApplication()
    {
        $this->commands = [];
        $this->namespaces = [];
        $all = $this->application->all($this->namespace ? $this->application->findNamespace($this->namespace) : null);
        foreach ($this->sortCommands($all) as $namespace => $commands) {
            $names = [];
            /** @var Command $command */
            foreach ($commands as $name => $command) {
                if (!$command->getName() || !$this->showHidden && $command->isHidden()) {
                    continue;
                }
                if ($command->getName() === $name) {
                    $this->commands[$name] = $command;
                } else {
                    $this->aliases[$name] = $command;
                }
                $names[] = $name;
            }
            $this->namespaces[$namespace] = ['id' => $namespace, 'commands' => $names];
        }
    }
    private function sortCommands(array $commands)
    {
        $namespacedCommands = [];
        $globalCommands = [];
        $sortedCommands = [];
        foreach ($commands as $name => $command) {
            $key = $this->application->extractNamespace($name, 1);
            if (\in_array($key, ['', self::GLOBAL_NAMESPACE], \true)) {
                $globalCommands[$name] = $command;
            } else {
                $namespacedCommands[$key][$name] = $command;
            }
        }
        if ($globalCommands) {
            \ksort($globalCommands);
            $sortedCommands[self::GLOBAL_NAMESPACE] = $globalCommands;
        }
        if ($namespacedCommands) {
            \ksort($namespacedCommands);
            foreach ($namespacedCommands as $key => $commandsSet) {
                \ksort($commandsSet);
                $sortedCommands[$key] = $commandsSet;
            }
        }
        $phabelReturn = $sortedCommands;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
