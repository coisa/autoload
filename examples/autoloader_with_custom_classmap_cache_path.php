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

require \dirname(__DIR__) . '/vendor/autoload.php';

$config = require __DIR__ . '/config.php';

/** @var Psr\Log\LoggerInterface $logger */
$logger = CoiSA\Autoload\Factory::createLogger(true);

/** @var CoiSA\Autoload\Generator\ClassMapGenerator $classMapGenerator */
$classMapGenerator = CoiSA\Autoload\Factory::createClassMapGenerator(
    $config['directories'],
    $config['custom_autoload_path'],
    $logger
);

$autoloader = CoiSA\Autoload\Factory::createAutoloader(
    $classMapGenerator,
    $logger
);

$autoloader->register();
