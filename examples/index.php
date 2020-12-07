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

require \dirname(__DIR__) . '/vendor/autoload.php';

# Optional (if not set it will save into "../resource/autoload_classmap.php"
//CoiSA\Autoload\Factory\ClassMapGeneratorAbstractFactory::setClassMapDefaultPath(
//    \dirname(__DIR__) . '/resource/custom_autoload_classmap_file_path.php'
//);

$autoloader = AbstractFactory::create(
    'CoiSA\\Autoload\\Autoloader',
    \dirname(__DIR__) . '/tests/Stubs'
    // \dirname(__DIR__) . '/other/path/to/lookup',
    // \dirname(__DIR__) . '/another/path/to/lookup',
    // ...,
);

\var_dump($autoloader->getClassMap());
