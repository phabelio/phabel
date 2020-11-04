<?php

namespace Phabel\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\InstallerEvent;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Repository\ArrayRepository as ComposerArrayRepository;
use Composer\Repository\ComposerRepository as ComposerComposerRepository;
use Composer\Repository\ConfigurableRepository as ComposerConfigurableRepository;
use Phabel\Composer\Repository\ArrayRepository;
use Phabel\Composer\Repository\ComposerRepository;
use Phabel\Composer\Repository\ConfigurableRepository;
use Phabel\Composer\Repository\Repository;
use ReflectionObject;

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
        $reflect = (new ReflectionObject($repoManager))->getProperty('repositories');
        $reflect->setAccessible(true);
        $reflect->setValue($repoManager, []);
        foreach ($repos as $repo) {
            if ($repo instanceof ComposerComposerRepository) {
                $repo = new ComposerRepository($repo);
            } else {
                $traits = [];
                foreach ([
                    ComposerArrayRepository::class => ArrayRepository::class,
                    ComposerConfigurableRepository::class => ConfigurableRepository::class,
                ] as $class => $trait) {
                    if ($repo instanceof $class) {
                        $traits []= $trait;
                    }
                }
                \sort($traits);
                if ($traits === [ArrayRepository::class]) {
                    $repo = new class($repo) extends ComposerArrayRepository {
                        use Repository;
                        use ArrayRepository;
                    };
                } elseif ($traits === [ConfigurableRepository::class]) {
                    $repo = new class($repo) extends ComposerRepository {
                        use Repository;
                        use ConfigurableRepository;
                    };
                } else {
                    $repo = new class($repo) extends ComposerArrayRepository {
                        use Repository;
                        use ArrayRepository;
                        use ConfigurableRepository;
                    };
                }
            }
            $repoManager->prependRepository($repo);
        }
        \var_dump(\array_map('get_class', $repoManager->getRepositories()));
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
            PackageEvents::POST_PACKAGE_INSTALL =>
                ['onInstall', 100000],
        ];
    }

    public function onInstall(PackageEvent $event): void
    {
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
