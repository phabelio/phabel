<?php

namespace Phabel\Composer;

use Composer\Composer;
use Composer\Console\Application;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\InstallerEvent;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Repository\PlatformRepository;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Phabel\Target\Php;
use Phabel\Tools;
use Phabel\Version;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    private string $toRequire = '';
    /** @psalm-suppress MissingConstructor */
    private \Phabel\Composer\Transformer $transformer;
    private ?array $lock = null;
    /**
     * Apply plugin modifications to Composer.
     *
     * @param Composer    $composer Composer instance
     * @param IOInterface $io       IO instance
     *
     * @return void
     */
    public function activate(Composer $composer, IOInterface $io) : void
    {
        if (!\defined('PHABEL_INCLUDED')) {
            $json = \json_decode(\file_get_contents(__DIR__ . '/../../composer.json'), \true);
            foreach ($json['autoload']['files'] as $file) {
                require_once __DIR__ . '/../../' . $file;
            }
        }
        if (\file_exists('composer.lock')) {
            $this->lock = \json_decode(\file_get_contents('composer.lock'), \true);
        }
        \putenv("PHABEL_INSIDE_COMPOSER=1");
        $rootPackage = $composer->getPackage();
        $php = $rootPackage->getConfig()['platform']['php'] ?? Php::DEFAULT_TARGET;
        $php = Php::normalizeVersion($php);
        $this->transformer = \Phabel\Composer\Transformer::getInstance($io, $php);
        $this->transformer->setInstallationManager($composer->getInstallationManager());
        $this->transformer->preparePackage($rootPackage, $rootPackage->getName());
        foreach ($rootPackage->getRequires() as $link) {
            if (PlatformRepository::isPlatformPackage($link->getTarget())) {
                continue;
            }
            $this->toRequire = $link->getTarget();
        }
        $repoManager = $composer->getRepositoryManager();
        $repos = $repoManager->getRepositories();
        foreach (\array_reverse($repos) as $repo) {
            if (!\method_exists($repo, 'setPhabelTransformer')) {
                $repo = Tools::cloneWithTrait($repo, \Phabel\Composer\Repository::class);
                $repo->setPhabelTransformer($this->transformer);
                $repoManager->prependRepository($repo);
            }
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
        return [ScriptEvents::POST_INSTALL_CMD => ['onInstall', 1], ScriptEvents::POST_UPDATE_CMD => ['onUpdate', 1]];
    }
    public function onInstall(Event $event) : void
    {
        $this->run($event, \false);
    }
    public function onUpdate(Event $event) : void
    {
        $this->run($event, \true);
    }
    private function run(Event $event, bool $isUpdate) : void
    {
        $lock = \json_decode(\file_get_contents('composer.lock'), \true);
        if ($this->transformer->transform($lock, $this->lock)) {
            \register_shutdown_function(function () use($isUpdate) {
                /** @var Application */
                $application = ($GLOBALS['application'] ?? null) instanceof Application ? $GLOBALS['application'] : new Application();
                $this->transformer->log("Loading additional dependencies...\n");
                if (!$isUpdate) {
                    $require = $application->find('require');
                    $require->run(new ArrayInput(['packages' => [$this->toRequire]]), new NullOutput());
                } else {
                    $application->setAutoExit(\false);
                    $application->run();
                }
            });
        } else {
            \register_shutdown_function(function () {
                $json = \json_decode(\file_get_contents('composer.json'), \true);
                if (!isset($json['require']['phabel/phabel']) && !isset($json['require-dev']['phabel/phabel'])) {
                    return;
                }
                $old = $json['extra']['phabel']['revision'] ?? -1;
                if ($old === Version::LATEST) {
                    return;
                }
                $json['extra'] ??= [];
                $json['extra']['phabel'] ??= [];
                $json['extra']['phabel']['revision'] = Version::LATEST;
                $json['require'] ??= [];
                $json['require']['php'] = '^8.0';
                $this->transformer->banner();
                $f = [$this->transformer, 'format'];
                $io = $this->transformer->getIo();
                for ($x = $old; $x <= Version::LATEST; $x++) {
                    if (isset(Version::CHANGELOG[$x])) {
                        $io->writeError($f(Version::CHANGELOG[$x]));
                    }
                }
                \file_put_contents('composer.json', \json_encode($json, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES) . \PHP_EOL);
            });
        }
    }
    /**
     * Emitted before composer solves dependencies.
     *
     * @param InstallerEvent $event Event
     *
     * @return void
     */
    public function onDependencySolve(InstallerEvent $event) : void
    {
    }
}
