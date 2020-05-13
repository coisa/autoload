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

use CoiSA\Autoload\Generator\ClassMapGeneratorFactory;
use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
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
            ScriptEvents::PRE_AUTOLOAD_DUMP => array(
                'removeAutoloadClassmapDefault',
                'createAutoloadClassmapDefault'
            ),
        );
    }

    /**
     * @param ScriptEvents $event
     */
    public function removeAutoloadClassmapDefault(ScriptEvents $event)
    {
        $classMapPath = ClassMapGeneratorFactory::getClassMapDefaultPath();

        @\unlink($classMapPath);
    }

    /**
     * @param ScriptEvents $event
     */
    public function createAutoloadClassmapDefault(ScriptEvents $event)
    {
        // @TODO recreate classmap based on extra composer configs
    }
}