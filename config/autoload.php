<?php

/**
 * This file is part of coisa/autoload.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/autoload
 *
 * @copyright Copyright (c) 2020 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

use CoiSA\Factory\AbstractFactory;
use CoiSA\Factory\AbstractFactoryFactory;
use CoiSA\Factory\AliasFactory;

\call_user_func(
    function($classLoaderFactory, $autoloaderFactory, $generatorFactory, $generatorAliasFactory) {
        AbstractFactory::setFactory('Composer\\Autoload\\ClassLoader', $classLoaderFactory);
        AbstractFactory::setFactory('CoiSA\\Autoload\\Autoloader', $autoloaderFactory);
        AbstractFactory::setFactory('CoiSA\\Autoload\\Generator\\PhpArrayFileClassMapGenerator', $generatorFactory);
        AbstractFactory::setFactory('CoiSA\\Autoload\\Generator\\ClassMapGeneratorInterface', $generatorAliasFactory);
    },
    new AbstractFactoryFactory('CoiSA\\Autoload\\Factory\\ClassLoaderAbstractFactory'),
    new AbstractFactoryFactory('CoiSA\\Autoload\\Factory\\AutoloaderAbstractFactory'),
    new AbstractFactoryFactory('CoiSA\\Autoload\\Factory\\ClassMapGeneratorAbstractFactory'),
    new AliasFactory('CoiSA\\Autoload\\Generator\\PhpArrayFileClassMapGenerator')
);
