<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\DependencyInjection;

use Phabel\Symfony\Component\Console\Command\Command;
use Phabel\Symfony\Component\Console\Command\LazyCommand;
use Phabel\Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Phabel\Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use Phabel\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Phabel\Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Phabel\Symfony\Component\DependencyInjection\ContainerBuilder;
use Phabel\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Phabel\Symfony\Component\DependencyInjection\Reference;
use Phabel\Symfony\Component\DependencyInjection\TypedReference;
/**
 * Registers console commands.
 *
 * @author Grégoire Pineau <lyrixx@lyrixx.info>
 */
class AddConsoleCommandPass implements CompilerPassInterface
{
    private $commandLoaderServiceId;
    private $commandTag;
    private $noPreloadTag;
    private $privateTagName;
    public function __construct($commandLoaderServiceId = 'console.command_loader', $commandTag = 'console.command', $noPreloadTag = 'container.no_preload', $privateTagName = 'container.private')
    {
        if (!\is_string($commandLoaderServiceId)) {
            if (!(\is_string($commandLoaderServiceId) || \is_object($commandLoaderServiceId) && \method_exists($commandLoaderServiceId, '__toString') || (\is_bool($commandLoaderServiceId) || \is_numeric($commandLoaderServiceId)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($commandLoaderServiceId) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($commandLoaderServiceId) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $commandLoaderServiceId = (string) $commandLoaderServiceId;
            }
        }
        if (!\is_string($commandTag)) {
            if (!(\is_string($commandTag) || \is_object($commandTag) && \method_exists($commandTag, '__toString') || (\is_bool($commandTag) || \is_numeric($commandTag)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($commandTag) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($commandTag) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $commandTag = (string) $commandTag;
            }
        }
        if (!\is_string($noPreloadTag)) {
            if (!(\is_string($noPreloadTag) || \is_object($noPreloadTag) && \method_exists($noPreloadTag, '__toString') || (\is_bool($noPreloadTag) || \is_numeric($noPreloadTag)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($noPreloadTag) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($noPreloadTag) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $noPreloadTag = (string) $noPreloadTag;
            }
        }
        if (!\is_string($privateTagName)) {
            if (!(\is_string($privateTagName) || \is_object($privateTagName) && \method_exists($privateTagName, '__toString') || (\is_bool($privateTagName) || \is_numeric($privateTagName)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($privateTagName) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($privateTagName) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $privateTagName = (string) $privateTagName;
            }
        }
        if (0 < \func_num_args()) {
            trigger_deprecation('symfony/console', '5.3', 'Configuring "%s" is deprecated.', __CLASS__);
        }
        $this->commandLoaderServiceId = $commandLoaderServiceId;
        $this->commandTag = $commandTag;
        $this->noPreloadTag = $noPreloadTag;
        $this->privateTagName = $privateTagName;
    }
    public function process(ContainerBuilder $container)
    {
        $commandServices = $container->findTaggedServiceIds($this->commandTag, \true);
        $lazyCommandMap = [];
        $lazyCommandRefs = [];
        $serviceIds = [];
        foreach ($commandServices as $id => $tags) {
            $definition = $container->getDefinition($id);
            $definition->addTag($this->noPreloadTag);
            $class = $container->getParameterBag()->resolveValue($definition->getClass());
            if (isset($tags[0]['command'])) {
                $aliases = $tags[0]['command'];
            } else {
                if (!($r = $container->getReflectionClass($class))) {
                    throw new InvalidArgumentException(\sprintf('Class "%s" used for service "%s" cannot be found.', $class, $id));
                }
                if (!$r->isSubclassOf(Command::class)) {
                    throw new InvalidArgumentException(\sprintf('The service "%s" tagged "%s" must be a subclass of "%s".', $id, $this->commandTag, Command::class));
                }
                $aliases = $class::getDefaultName();
            }
            $aliases = \explode('|', isset($aliases) ? $aliases : '');
            $commandName = \array_shift($aliases);
            if ($isHidden = '' === $commandName) {
                $commandName = \array_shift($aliases);
            }
            if (null === $commandName) {
                if (!$definition->isPublic() || $definition->isPrivate() || $definition->hasTag($this->privateTagName)) {
                    $commandId = 'console.command.public_alias.' . $id;
                    $container->setAlias($commandId, $id)->setPublic(\true);
                    $id = $commandId;
                }
                $serviceIds[] = $id;
                continue;
            }
            $description = isset($tags[0]['description']) ? $tags[0]['description'] : null;
            unset($tags[0]);
            $lazyCommandMap[$commandName] = $id;
            $lazyCommandRefs[$id] = new TypedReference($id, $class);
            foreach ($aliases as $alias) {
                $lazyCommandMap[$alias] = $id;
            }
            foreach ($tags as $tag) {
                if (isset($tag['command'])) {
                    $aliases[] = $tag['command'];
                    $lazyCommandMap[$tag['command']] = $id;
                }
                $description = isset($description) ? $description : (isset($tag['description']) ? $tag['description'] : null);
            }
            $definition->addMethodCall('setName', [$commandName]);
            if ($aliases) {
                $definition->addMethodCall('setAliases', [$aliases]);
            }
            if ($isHidden) {
                $definition->addMethodCall('setHidden', [\true]);
            }
            if (!$description) {
                if (!($r = $container->getReflectionClass($class))) {
                    throw new InvalidArgumentException(\sprintf('Class "%s" used for service "%s" cannot be found.', $class, $id));
                }
                if (!$r->isSubclassOf(Command::class)) {
                    throw new InvalidArgumentException(\sprintf('The service "%s" tagged "%s" must be a subclass of "%s".', $id, $this->commandTag, Command::class));
                }
                $description = $class::getDefaultDescription();
            }
            if ($description) {
                $definition->addMethodCall('setDescription', [$description]);
                $container->register('.' . $id . '.lazy', LazyCommand::class)->setArguments([$commandName, $aliases, $description, $isHidden, new ServiceClosureArgument($lazyCommandRefs[$id])]);
                $lazyCommandRefs[$id] = new Reference('.' . $id . '.lazy');
            }
        }
        $container->register($this->commandLoaderServiceId, ContainerCommandLoader::class)->setPublic(\true)->addTag($this->noPreloadTag)->setArguments([ServiceLocatorTagPass::register($container, $lazyCommandRefs), $lazyCommandMap]);
        $container->setParameter('console.command.ids', $serviceIds);
    }
}
