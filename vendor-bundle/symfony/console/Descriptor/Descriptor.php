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
use Phabel\Symfony\Component\Console\Exception\InvalidArgumentException;
use Phabel\Symfony\Component\Console\Input\InputArgument;
use Phabel\Symfony\Component\Console\Input\InputDefinition;
use Phabel\Symfony\Component\Console\Input\InputOption;
use Phabel\Symfony\Component\Console\Output\OutputInterface;
/**
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
abstract class Descriptor implements DescriptorInterface
{
    /**
     * @var OutputInterface
     */
    protected $output;
    /**
     * {@inheritdoc}
     */
    public function describe(OutputInterface $output, $object, array $options = [])
    {
        if (!\is_object($object)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($object) must be of type object, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($object) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $this->output = $output;
        switch (\true) {
            case $object instanceof InputArgument:
                $this->describeInputArgument($object, $options);
                break;
            case $object instanceof InputOption:
                $this->describeInputOption($object, $options);
                break;
            case $object instanceof InputDefinition:
                $this->describeInputDefinition($object, $options);
                break;
            case $object instanceof Command:
                $this->describeCommand($object, $options);
                break;
            case $object instanceof Application:
                $this->describeApplication($object, $options);
                break;
            default:
                throw new InvalidArgumentException(\sprintf('Object of type "%s" is not describable.', \get_debug_type($object)));
        }
    }
    /**
     * Writes content to output.
     */
    protected function write($content, $decorated = \false)
    {
        if (!\is_string($content)) {
            if (!(\is_string($content) || \is_object($content) && \method_exists($content, '__toString') || (\is_bool($content) || \is_numeric($content)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($content) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($content) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $content = (string) $content;
            }
        }
        if (!\is_bool($decorated)) {
            if (!(\is_bool($decorated) || \is_numeric($decorated) || \is_string($decorated))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($decorated) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($decorated) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $decorated = (bool) $decorated;
            }
        }
        $this->output->write($content, \false, $decorated ? OutputInterface::OUTPUT_NORMAL : OutputInterface::OUTPUT_RAW);
    }
    /**
     * Describes an InputArgument instance.
     */
    protected abstract function describeInputArgument(InputArgument $argument, array $options = []);
    /**
     * Describes an InputOption instance.
     */
    protected abstract function describeInputOption(InputOption $option, array $options = []);
    /**
     * Describes an InputDefinition instance.
     */
    protected abstract function describeInputDefinition(InputDefinition $definition, array $options = []);
    /**
     * Describes a Command instance.
     */
    protected abstract function describeCommand(Command $command, array $options = []);
    /**
     * Describes an Application instance.
     */
    protected abstract function describeApplication(Application $application, array $options = []);
}
