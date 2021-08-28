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

use Phabel\Symfony\Component\Console\Descriptor\DescriptorInterface;
use Phabel\Symfony\Component\Console\Descriptor\JsonDescriptor;
use Phabel\Symfony\Component\Console\Descriptor\MarkdownDescriptor;
use Phabel\Symfony\Component\Console\Descriptor\TextDescriptor;
use Phabel\Symfony\Component\Console\Descriptor\XmlDescriptor;
use Phabel\Symfony\Component\Console\Exception\InvalidArgumentException;
use Phabel\Symfony\Component\Console\Output\OutputInterface;
/**
 * This class adds helper method to describe objects in various formats.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class DescriptorHelper extends Helper
{
    /**
     * @var DescriptorInterface[]
     */
    private $descriptors = [];
    public function __construct()
    {
        $this->register('txt', new TextDescriptor())->register('xml', new XmlDescriptor())->register('json', new JsonDescriptor())->register('md', new MarkdownDescriptor());
    }
    /**
     * Describes an object if supported.
     *
     * Available options are:
     * * format: string, the output format name
     * * raw_text: boolean, sets output type as raw
     *
     * @throws InvalidArgumentException when the given format is not supported
     */
    public function describe(OutputInterface $output, $object, array $options = [])
    {
        if (!(\is_object($object) || \is_null($object))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($object) must be of type ?object, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($object) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $options = \array_merge(['raw_text' => \false, 'format' => 'txt'], $options);
        if (!isset($this->descriptors[$options['format']])) {
            throw new InvalidArgumentException(\sprintf('Unsupported format "%s".', $options['format']));
        }
        $descriptor = $this->descriptors[$options['format']];
        $descriptor->describe($output, $object, $options);
    }
    /**
     * Registers a descriptor.
     *
     * @return $this
     */
    public function register($format, DescriptorInterface $descriptor)
    {
        if (!\is_string($format)) {
            if (!(\is_string($format) || \is_object($format) && \method_exists($format, '__toString') || (\is_bool($format) || \is_numeric($format)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($format) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($format) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $format = (string) $format;
            }
        }
        $this->descriptors[$format] = $descriptor;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'descriptor';
    }
}
