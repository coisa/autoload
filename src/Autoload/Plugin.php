<?php

/**
 * This file is part of coisa/autoload.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/autoload
 * @copyright Copyright (c) 2022 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
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
 * Class Plugin.
 *
 * @package CoiSA\Autoload
 */
final class Plugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ScriptEvents::PRE_AUTOLOAD_DUMP => 'generateClassMapFromExtra',
        );
    }

    /**
     * @param ScriptEvents $event
     */
    public function generateClassMapFromExtra(Event $event)
    {
        $extra = \array_merge(
            array('coisa' => array('autoload' => array())),
            $event->getComposer()->getPackage()->getExtra()
        );

        $autoloadConfig = $extra['coisa']['autoload'];

        if (empty($autoloadConfig)) {
            return;
        }

        $classMapGenerator = Factory::createClassMapGenerator(
            $autoloadConfig['classmap'] ?: array(),
            $autoloadConfig['custom_path'] ?: null
        );

        $classMapGenerator->generateClassMap();
    }
}
