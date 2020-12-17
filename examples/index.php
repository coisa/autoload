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
use CoiSA\Factory\ValueFactory;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;

require \dirname(__DIR__) . '/vendor/autoload.php';

$logger = new ConsoleLogger(
    new ConsoleOutput(ConsoleOutput::VERBOSITY_DEBUG)
);

# Optional (if not set will create a Psr\Log\NullLogger instance)
AbstractFactory::setFactory('Psr\\Log\\LoggerInterface', new ValueFactory($logger));

# Optional (if not set it will save into "../resource/autoload_classmap.php"
CoiSA\Autoload\Factory\ClassMapGeneratorAbstractFactory::setClassMapDefaultPath(
    \dirname(__DIR__) . '/resource/custom_autoload_classmap.php'
);

AbstractFactory::create(
    'CoiSA\\Autoload\\Autoloader',
    \dirname(__DIR__) . '/tests/Stubs'
    // \dirname(__DIR__) . '/other/path/to/lookup',
    // \dirname(__DIR__) . '/another/path/to/lookup',
    // ...,
);
