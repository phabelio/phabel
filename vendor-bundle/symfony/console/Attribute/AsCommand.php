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
    public function __construct(string $name, $description = null, array $aliases = [], bool $hidden = \false)
    {
        if (!\is_null($description)) {
            if (!\is_string($description)) {
                if (!(\is_string($description) || \Phabel\Target\Php72\Polyfill::is_object($description) && \method_exists($description, '__toString') || (\is_bool($description) || \is_numeric($description)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($description) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($description) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $description = (string) $description;
                }
            }
        }
        $this->description = $description;
        $this->name = $name;
        if (!$hidden && !$aliases) {
            return;
        }
        $name = \explode('|', $name);
        $name = \array_merge($name, $aliases);
        if ($hidden && '' !== $name[0]) {
            \Phabel\Target\Php73\Polyfill::array_unshift($name, '');
        }
        $this->name = \implode('|', $name);
    }
    public $description;
    public $name;
}
