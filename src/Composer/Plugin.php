<?php

namespace Phabel\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\InstallerEvent;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Phabel\Composer\Repository\Repository;
use ReflectionClass;
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
            $traits = [Repository::class];
            $traitsStr = '';
            foreach ($traits as $trait) {
                $traitsStr .= "use \\$trait;\n";
            }
            $reflect = new ReflectionClass($repo);

            $extend = "extends \\".\get_class($repo);
            $eval = "\$newRepo = new class $extend {
                    $traitsStr

                    public function __construct() {}
                };";
            eval($eval);

            $reflectNew = new ReflectionClass($newRepo);
            $reflectNew = $reflectNew->getParentClass();

            do {
                foreach ($reflect->getProperties() as $prop) {
                    $propNew = $reflectNew->getProperty($prop->getName());
                    $propNew->setAccessible(true);
                    $prop->setAccessible(true);
                    $propNew->setValue($newRepo, $prop->getValue($repo));
                }
                $reflectNew = $reflectNew->getParentClass();
            } while ($reflect = $reflect->getParentClass());
            $repo = $newRepo;

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
