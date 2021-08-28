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

use Phabel\Symfony\Component\Console\Command\Command;
use Phabel\Symfony\Component\Console\Exception\InvalidArgumentException;
/**
 * HelperSet represents a set of helpers to be used with a command.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class HelperSet implements \IteratorAggregate
{
    /**
     * @var Helper[]
     */
    private $helpers = [];
    private $command;
    /**
     * @param Helper[] $helpers An array of helper
     */
    public function __construct(array $helpers = [])
    {
        foreach ($helpers as $alias => $helper) {
            $this->set($helper, \is_int($alias) ? null : $alias);
        }
    }
    public function set(HelperInterface $helper, $alias = null)
    {
        if (!\is_null($alias)) {
            if (!\is_string($alias)) {
                if (!(\is_string($alias) || \is_object($alias) && \method_exists($alias, '__toString') || (\is_bool($alias) || \is_numeric($alias)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($alias) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($alias) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $alias = (string) $alias;
                }
            }
        }
        $this->helpers[$helper->getName()] = $helper;
        if (null !== $alias) {
            $this->helpers[$alias] = $helper;
        }
        $helper->setHelperSet($this);
    }
    /**
     * Returns true if the helper if defined.
     *
     * @return bool true if the helper is defined, false otherwise
     */
    public function has($name)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        return isset($this->helpers[$name]);
    }
    /**
     * Gets a helper value.
     *
     * @return HelperInterface The helper instance
     *
     * @throws InvalidArgumentException if the helper is not defined
     */
    public function get($name)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!$this->has($name)) {
            throw new InvalidArgumentException(\sprintf('The helper "%s" is not defined.', $name));
        }
        return $this->helpers[$name];
    }
    public function setCommand(Command $command = null)
    {
        $this->command = $command;
    }
    /**
     * Gets the command associated with this helper set.
     *
     * @return Command A Command instance
     */
    public function getCommand()
    {
        return $this->command;
    }
    /**
     * @return Helper[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->helpers);
    }
}
