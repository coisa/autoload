<?php

/**
 * This file is part of coisa/autoload.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/autoload
 * @copyright Copyright (c) 2020 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace CoiSA\Autoload;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;

/**
 * Class Plugin
 *
 * @package CoiSA\Autoload
 */
final class Plugin implements PluginInterface, EventSubscriberInterface
{
    /** @var Composer */
    private $composer;

    /** @var IOInterface */
    private $io;

    /**
     * {@inheritDoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io       = $io;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            PackageEvents::POST_PACKAGE_INSTALL => 'updateAutoloadClassMap',
            PackageEvents::POST_PACKAGE_UPDATE  => 'updateAutoloadClassMap',
        );
    }

    /**
     * @param Event $event
     */
    public function updateAutoloadClassMap(PackageEvent $event)
    {
        $package           = $event->getComposer()->getPackage();
        $extra             = $package->getExtra();
        $classMapRootPaths = $this->getExtraMetadata($extra);
        $paths             = \array_filter($classMapRootPaths, 'is_dir');

        if (empty($paths)) {
            return;
        }
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

        if (\is_string($extra['autoload']['recursive-classmap'])) {
            return array($extra['autoload']['recursive-classmap']);
        }

        return $extra['autoload']['recursive-classmap'];
    }
}
