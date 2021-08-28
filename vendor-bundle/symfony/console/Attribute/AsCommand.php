<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Attribute;

/**
 * Service tag to autoconfigure commands.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class AsCommand
{
    public function __construct(public $name, public $description = null, array $aliases = [], $hidden = \false)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!\is_null($description)) {
            if (!\is_string($description)) {
                if (!(\is_string($description) || \is_object($description) && \method_exists($description, '__toString') || (\is_bool($description) || \is_numeric($description)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($description) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($description) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $description = (string) $description;
                }
            }
        }
        if (!\is_bool($hidden)) {
            if (!(\is_bool($hidden) || \is_numeric($hidden) || \is_string($hidden))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($hidden) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($hidden) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $hidden = (bool) $hidden;
            }
        }
        if (!$hidden && !$aliases) {
            return;
        }
        $name = \explode('|', $name);
        $name = \array_merge($name, $aliases);
        if ($hidden && '' !== $name[0]) {
            \array_unshift($name, '');
        }
        $this->name = \implode('|', $name);
    }
}
