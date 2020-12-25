<?php

namespace Phabel\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\InstallerEvent;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Phabel\Target\Php;
use Phabel\Tools;
use ReflectionObject;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    private Transformer $transformer;
    /**
     * Apply plugin modifications to Composer.
     *
     * @param Composer    $composer Composer instance
     * @param IOInterface $io       IO instance
     *
     * @return void
     */
    public function activate(Composer $composer, IOInterface $io): void
    {
        $rootPackage = $composer->getPackage();
        $this->transformer = new Transformer($io);
        $this->transformer->preparePackage($rootPackage, $rootPackage->getName());
        \var_dump($rootPackage->getRequires());

        $repoManager = $composer->getRepositoryManager();
        $repos = $repoManager->getRepositories();
        foreach (array_reverse($repos) as $repo) {
            $repo = Tools::cloneWithTrait($repo, Repository::class);
            $repo->phabelTransformer = $this->transformer;
            $repoManager->prependRepository($repo);
        }
        //\var_dump(\array_map('get_class', $repoManager->getRepositories()));
        $this->io = $io;
    }

    /**
     * Remove any hooks from Composer.
     *
     * This will be called when a plugin is deactivated before being
     * uninstalled, but also before it gets upgraded to a new version
     * so the old one can be deactivated and the new one activated.
     *
     * @param Composer    $composer
     * @param IOInterface $io
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * Prepare the plugin to be uninstalled.
     *
     * This will be called after deactivate.
     *
     * @param Composer    $composer
     * @param IOInterface $io
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ScriptEvents::POST_INSTALL_CMD =>
                ['onInstall', 1],
            ScriptEvents::POST_UPDATE_CMD =>
                    ['onInstall', 1],
        ];
    }

    public function onInstall(Event $event): void
    {
        $lock = json_decode(file_get_contents('composer.lock'), true);
        foreach ($lock['packages'] as $package) {
            [, $target] = $this->transformer->extractTarget($package['name']);
            if ($target === Php::TARGET_IGNORE) {
                continue;
            }
            $path = "vendor/".$package['name'];
            var_dump($path, $target);
        }
    }

    /**
     * Emitted before composer solves dependencies.
     *
     * @param InstallerEvent $event Event
     *
     * @return void
     */
    public function onDependencySolve(InstallerEvent $event): void
    {
    }
}
