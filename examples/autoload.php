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

require_once __DIR__ . '/../vendor/autoload.php';

$cache = new Cache\Adapter\PHPArray\ArrayCachePool();

$logger = new Symfony\Component\Console\Logger\ConsoleLogger(
    new Symfony\Component\Console\Output\ConsoleOutput(
        Symfony\Component\Console\Output\OutputInterface::VERBOSITY_DEBUG
    )
);

$autoloader = new CoiSA\Autoload\Autoloader($cache, $logger);
$autoloader->addDirectory(__DIR__);
$autoloader->register();

$stub1 = new CoiSA\Autoload\Example\Stub\UnknowClassFile();
$stub2 = new CoiSA\Autoload\Example\Stub\AnotherUnknowClassFile();
$stub3 = new CoiSA\Example\Stub\LostClassFile();
$stub4 = new ClassWithoutNamespace();
