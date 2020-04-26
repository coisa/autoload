<?php

namespace CoiSA\Autoload\Composer\Plugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Symfony\Component\Finder\Finder;

/**
 * Class RecursiveClassMapAutoloadPlugin
 *
 * @package CoiSA\Autoload\Composer\Plugin
 */
final class RecursiveClassMapAutoloadPlugin implements PluginInterface, EventSubscriberInterface
{
    /** @var Composer */
    protected $composer;

    /** @var IOInterface */
    protected $io;

    /**
     * {@inheritDoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            //ScriptEvents::POST_AUTOLOAD_DUMP => 'generateClassMap',
            PackageEvents::POST_PACKAGE_INSTALL => 'updateAutoloadClassMap',
        );
    }

    /**
     * @param Event $event
     */
    public function updateAutoloadClassMap(PackageEvent $event)
    {
        $package = $event->getComposer()->getPackage();
        $extra = $package->getExtra();
        $classMapRootPaths = $this->getExtraMetadata($extra);
        $paths = array_filter($classMapRootPaths, 'is_dir');

        if (empty($paths)) {
            return;
        }

        $finder = Finder::create()
            ->directories()
            ->followLinks()
            ->ignoreVCS(true)
            ->in($paths)
            ->append($paths)
        ;

        $autoload = $package->getAutoload();
        $autoload['classmap'] = $autoload['classmap'] + iterator_to_array($finder);
        $package->setAutoload($autoload);
    }

    /**
     * @param array $extra
     *
     * @return array
     */
    private function getExtraMetadata(array $extra)
    {
        if (!isset($extra['autoload']) || !isset($extra['autoload']['recursive-classmap'])) {
            return array();
        }

        if (is_string($extra['autoload']['recursive-classmap'])) {
            return array($extra['autoload']['recursive-classmap']);
        }

        return $extra['autoload']['recursive-classmap'];
    }
}
