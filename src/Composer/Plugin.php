<?php

namespace Phabel\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\InstallerEvent;
use Composer\Installer\InstallerEvents;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * IO interface.
     */
    private IOInterface $io;
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
        $repoManager = $composer->getRepositoryManager();
        $repos = $repoManager->getRepositories();
        $repoManager->prependRepository(new Repository($repos[0]));
        $this->io = $io;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            InstallerEvents::PRE_DEPENDENCIES_SOLVING =>
                ['onDependencySolve', 100000],
            PackageEvents::POST_PACKAGE_INSTALL =>
                ['onInstall', 100000],
        ];
    }

    public function onInstall(PackageEvent $event): void
    {
        var_dumP($event);
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
        \var_dump($event);
    }
}
