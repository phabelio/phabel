<?php

namespace Phabel\Composer;

use Composer\Composer;
use Composer\Console\Application;
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
        
        $hasPhabel = false;
        if (file_exists('composer.lock')) {
            $packages = json_decode(file_get_contents('composer.lock'), true)['packages'] ?? [];
            foreach ($packages as $package) {
                [$name] = $this->transformer->extractTarget($package['name']);
                if ($name === 'phabel/phabel') {
                    $hasPhabel = true;
                    if ($name !== $package['name']) {
                        return;
                    }
                }
            }
        }
        if (!$hasPhabel) {
            return;
        }

        if (isset($GLOBALS['application']) && $GLOBALS['application'] instanceof Application) {
            register_shutdown_function(fn () => $GLOBALS['application']->run());
        }
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
                ['onInstall', 1]
        ];
    }

    public function onInstall(Event $event): void
    {
        $this->transformer->transform(json_decode(file_get_contents('composer.lock'), true));
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
