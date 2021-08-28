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
use Phabel\Symfony\Component\Console\Input\InputArgument;
use Phabel\Symfony\Component\Console\Input\InputDefinition;
use Phabel\Symfony\Component\Console\Input\InputOption;
/**
 * JSON descriptor.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 *
 * @internal
 */
class JsonDescriptor extends Descriptor
{
    /**
     * {@inheritdoc}
     */
    protected function describeInputArgument(InputArgument $argument, array $options = [])
    {
        $this->writeData($this->getInputArgumentData($argument), $options);
    }
    /**
     * {@inheritdoc}
     */
    protected function describeInputOption(InputOption $option, array $options = [])
    {
        $this->writeData($this->getInputOptionData($option), $options);
        if ($option->isNegatable()) {
            $this->writeData($this->getInputOptionData($option, \true), $options);
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function describeInputDefinition(InputDefinition $definition, array $options = [])
    {
        $this->writeData($this->getInputDefinitionData($definition), $options);
    }
    /**
     * {@inheritdoc}
     */
    protected function describeCommand(Command $command, array $options = [])
    {
        $this->writeData($this->getCommandData($command, isset($options['short']) ? $options['short'] : \false), $options);
    }
    /**
     * {@inheritdoc}
     */
    protected function describeApplication(Application $application, array $options = [])
    {
        $describedNamespace = isset($options['namespace']) ? $options['namespace'] : null;
        $description = new ApplicationDescription($application, $describedNamespace, \true);
        $commands = [];
        foreach ($description->getCommands() as $command) {
            $commands[] = $this->getCommandData($command, isset($options['short']) ? $options['short'] : \false);
        }
        $data = [];
        if ('UNKNOWN' !== $application->getName()) {
            $data['application']['name'] = $application->getName();
            if ('UNKNOWN' !== $application->getVersion()) {
                $data['application']['version'] = $application->getVersion();
            }
        }
        $data['commands'] = $commands;
        if ($describedNamespace) {
            $data['namespace'] = $describedNamespace;
        } else {
            $data['namespaces'] = \array_values($description->getNamespaces());
        }
        $this->writeData($data, $options);
    }
    /**
     * Writes data as json.
     */
    private function writeData(array $data, array $options)
    {
        $flags = isset($options['json_encoding']) ? $options['json_encoding'] : 0;
        $this->write(\json_encode($data, $flags));
    }
    private function getInputArgumentData(InputArgument $argument)
    {
        $phabelReturn = ['name' => $argument->getName(), 'is_required' => $argument->isRequired(), 'is_array' => $argument->isArray(), 'description' => \preg_replace('/\\s*[\\r\\n]\\s*/', ' ', $argument->getDescription()), 'default' => \INF === $argument->getDefault() ? 'INF' : $argument->getDefault()];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function getInputOptionData(InputOption $option, $negated = \false)
    {
        if (!\is_bool($negated)) {
            if (!(\is_bool($negated) || \is_numeric($negated) || \is_string($negated))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($negated) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($negated) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $negated = (bool) $negated;
            }
        }
        $phabelReturn = $negated ? ['name' => '--no-' . $option->getName(), 'shortcut' => '', 'accept_value' => \false, 'is_value_required' => \false, 'is_multiple' => \false, 'description' => 'Negate the "--' . $option->getName() . '" option', 'default' => \false] : ['name' => '--' . $option->getName(), 'shortcut' => $option->getShortcut() ? '-' . \str_replace('|', '|-', $option->getShortcut()) : '', 'accept_value' => $option->acceptValue(), 'is_value_required' => $option->isValueRequired(), 'is_multiple' => $option->isArray(), 'description' => \preg_replace('/\\s*[\\r\\n]\\s*/', ' ', $option->getDescription()), 'default' => \INF === $option->getDefault() ? 'INF' : $option->getDefault()];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function getInputDefinitionData(InputDefinition $definition)
    {
        $inputArguments = [];
        foreach ($definition->getArguments() as $name => $argument) {
            $inputArguments[$name] = $this->getInputArgumentData($argument);
        }
        $inputOptions = [];
        foreach ($definition->getOptions() as $name => $option) {
            $inputOptions[$name] = $this->getInputOptionData($option);
            if ($option->isNegatable()) {
                $inputOptions['no-' . $name] = $this->getInputOptionData($option, \true);
            }
        }
        $phabelReturn = ['arguments' => $inputArguments, 'options' => $inputOptions];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function getCommandData(Command $command, $short = \false)
    {
        if (!\is_bool($short)) {
            if (!(\is_bool($short) || \is_numeric($short) || \is_string($short))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($short) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($short) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $short = (bool) $short;
            }
        }
        $data = ['name' => $command->getName(), 'description' => $command->getDescription()];
        if ($short) {
            $data += ['usage' => $command->getAliases()];
        } else {
            $command->mergeApplicationDefinition(\false);
            $data += ['usage' => \array_merge([$command->getSynopsis()], $command->getUsages(), $command->getAliases()), 'help' => $command->getProcessedHelp(), 'definition' => $this->getInputDefinitionData($command->getDefinition())];
        }
        $data['hidden'] = $command->isHidden();
        $phabelReturn = $data;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
