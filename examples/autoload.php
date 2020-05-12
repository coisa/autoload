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

$classLoader = require \dirname(__DIR__) . '/vendor/autoload.php';

$logger = new Symfony\Component\Console\Logger\ConsoleLogger(
    new Symfony\Component\Console\Output\ConsoleOutput(
        Symfony\Component\Console\Output\OutputInterface::VERBOSITY_DEBUG
    )
);

$classMapCacheFile = \dirname(__DIR__) . '/vendor/composer/autoload_classmap_example.php';

$autoloader = new CoiSA\Autoload\Autoloader($classLoader, $classMapCacheFile, $logger);
$autoloader->addDirectory(\dirname(__DIR__) . '/tests/stubs');
$autoloader->register();

$stub1 = new CoiSA\Autoload\Example\Stub\UnknowClassFile();
$stub2 = new CoiSA\Autoload\Example\Stub\AnotherUnknowClassFile();
$stub3 = new CoiSA\Example\Stub\LostClassFile();
$stub4 = new ClassWithoutNamespace();

if (\class_exists('TestInterface')) {
    echo "TestInterface\n";
}
