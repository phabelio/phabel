<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhabelVendor\Symfony\Component\Console\Descriptor;

use PhabelVendor\Symfony\Component\Console\Application;
use PhabelVendor\Symfony\Component\Console\Command\Command;
use PhabelVendor\Symfony\Component\Console\Helper\Helper;
use PhabelVendor\Symfony\Component\Console\Input\InputArgument;
use PhabelVendor\Symfony\Component\Console\Input\InputDefinition;
use PhabelVendor\Symfony\Component\Console\Input\InputOption;
use PhabelVendor\Symfony\Component\Console\Output\OutputInterface;
/**
 * Markdown descriptor.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 *
 * @internal
 */
class MarkdownDescriptor extends Descriptor
{
    /**
     * {@inheritdoc}
     */
    public function describe(OutputInterface $output, object $object, array $options = [])
    {
        $decorated = $output->isDecorated();
        $output->setDecorated(\false);
        parent::describe($output, $object, $options);
        $output->setDecorated($decorated);
    }
    /**
     * {@inheritdoc}
     */
    protected function write(string $content, bool $decorated = \true)
    {
        parent::write($content, $decorated);
    }
    /**
     * {@inheritdoc}
     */
    protected function describeInputArgument(InputArgument $argument, array $options = [])
    {
        $this->write('#### `' . ($argument->getName() ?: '<none>') . "`\n\n" . ($argument->getDescription() ? \preg_replace('/\\s*[\\r\\n]\\s*/', "\n", $argument->getDescription()) . "\n\n" : '') . '* Is required: ' . ($argument->isRequired() ? 'yes' : 'no') . '
* Is array: ' . ($argument->isArray() ? 'yes' : 'no') . '
* Default: `' . \str_replace("\n", '', \var_export($argument->getDefault(), \true)) . '`');
    }
    /**
     * {@inheritdoc}
     */
    protected function describeInputOption(InputOption $option, array $options = [])
    {
        $name = '--' . $option->getName();
        if ($option->isNegatable()) {
            $name .= '|--no-' . $option->getName();
        }
        if ($option->getShortcut()) {
            $name .= '|-' . \str_replace('|', '|-', $option->getShortcut()) . '';
        }
        $this->write('#### `' . $name . '`

' . ($option->getDescription() ? \preg_replace('/\\s*[\\r\\n]\\s*/', "\n", $option->getDescription()) . "\n\n" : '') . '* Accept value: ' . ($option->acceptValue() ? 'yes' : 'no') . '
* Is value required: ' . ($option->isValueRequired() ? 'yes' : 'no') . '
* Is multiple: ' . ($option->isArray() ? 'yes' : 'no') . '
* Is negatable: ' . ($option->isNegatable() ? 'yes' : 'no') . '
* Default: `' . \str_replace("\n", '', \var_export($option->getDefault(), \true)) . '`');
    }
    /**
     * {@inheritdoc}
     */
    protected function describeInputDefinition(InputDefinition $definition, array $options = [])
    {
        if ($showArguments = \count($definition->getArguments()) > 0) {
            $this->write('### Arguments');
            foreach ($definition->getArguments() as $argument) {
                $this->write("\n\n");
                if (null !== ($describeInputArgument = $this->describeInputArgument($argument))) {
                    $this->write($describeInputArgument);
                }
            }
        }
        if (\count($definition->getOptions()) > 0) {
            if ($showArguments) {
                $this->write("\n\n");
            }
            $this->write('### Options');
            foreach ($definition->getOptions() as $option) {
                $this->write("\n\n");
                if (null !== ($describeInputOption = $this->describeInputOption($option))) {
                    $this->write($describeInputOption);
                }
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function describeCommand(Command $command, array $options = [])
    {
        if ($options['short'] ?? \false) {
            $this->write('`' . $command->getName() . "`\n" . \str_repeat('-', Helper::width($command->getName()) + 2) . "\n\n" . ($command->getDescription() ? $command->getDescription() . "\n\n" : '') . '### Usage

' . \array_reduce($command->getAliases(), function ($carry, $usage) {
                return $carry . '* `' . $usage . '`
';
            }));
            return;
        }
        $command->mergeApplicationDefinition(\false);
        $this->write('`' . $command->getName() . "`\n" . \str_repeat('-', Helper::width($command->getName()) + 2) . "\n\n" . ($command->getDescription() ? $command->getDescription() . "\n\n" : '') . '### Usage

' . \array_reduce(\array_merge([$command->getSynopsis()], $command->getAliases(), $command->getUsages()), function ($carry, $usage) {
            return $carry . '* `' . $usage . '`
';
        }));
        if ($help = $command->getProcessedHelp()) {
            $this->write("\n");
            $this->write($help);
        }
        $definition = $command->getDefinition();
        if ($definition->getOptions() || $definition->getArguments()) {
            $this->write("\n\n");
            $this->describeInputDefinition($definition);
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function describeApplication(Application $application, array $options = [])
    {
        $describedNamespace = $options['namespace'] ?? null;
        $description = new ApplicationDescription($application, $describedNamespace);
        $title = $this->getApplicationTitle($application);
        $this->write($title . "\n" . \str_repeat('=', Helper::width($title)));
        foreach ($description->getNamespaces() as $namespace) {
            if (ApplicationDescription::GLOBAL_NAMESPACE !== $namespace['id']) {
                $this->write("\n\n");
                $this->write('**' . $namespace['id'] . ':**');
            }
            $this->write("\n\n");
            $this->write(\implode("\n", \array_map(function ($commandName) use($description) {
                return \sprintf('* [`%s`](#%s)', $commandName, \str_replace(':', '', $description->getCommand($commandName)->getName()));
            }, $namespace['commands'])));
        }
        foreach ($description->getCommands() as $command) {
            $this->write("\n\n");
            if (null !== ($describeCommand = $this->describeCommand($command, $options))) {
                $this->write($describeCommand);
            }
        }
    }
    private function getApplicationTitle(Application $application) : string
    {
        if ('UNKNOWN' !== $application->getName()) {
            if ('UNKNOWN' !== $application->getVersion()) {
                return \sprintf('%s %s', $application->getName(), $application->getVersion());
            }
            return $application->getName();
        }
        return 'Console Tool';
    }
}
