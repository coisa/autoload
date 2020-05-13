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
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

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
            ScriptEvents::PRE_AUTOLOAD_DUMP => 'generateClassMapFromComposerExtra',
        );
    }

    /**
     * @param ScriptEvents $event
     */
    public function generateClassMapFromComposerExtra(Event $event)
    {
        $extra = $event->getComposer()->getPackage()->getExtra();

        if (false === \array_key_exists('coisa', $extra)
            || false === \array_key_exists('autoload', $extra['coisa'])
        ) {
            return;
        }

        $classMapGenerator = Factory::createClassMapFileGenerator($extra['coisa']['autoload']);
        $classMapGenerator->generateClassMap();
    }
}
