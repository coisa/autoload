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

$classLoader = require \dirname(__DIR__) . '/src/bootstrap.php';

$logger = new Symfony\Component\Console\Logger\ConsoleLogger(
    new Symfony\Component\Console\Output\ConsoleOutput(
        Symfony\Component\Console\Output\OutputInterface::VERBOSITY_DEBUG
    )
);

$classMapFilePath = \dirname(__DIR__) . '/vendor/composer/autoload_classmap_example.php';

$classMapGenerator = new CoiSA\Autoload\Generator\ClassMapFileGenerator($classMapFilePath, $logger);
$classMapGenerator->addDirectory(\dirname(__DIR__) . '/tests/stubs');

// $classLoader->addClassMap($classMapGenerator->getClassMap());
// or
// $autoloader = new CoiSA\Autoload\Autoloader($classMapGenerator);
// or even
$autoloader = new CoiSA\Autoload\Autoloader($classMapGenerator, $classLoader);
$autoloader->register();

$stub1 = new CoiSA\Autoload\Example\Stub\UnknowClassFile();
$stub2 = new CoiSA\Autoload\Example\Stub\AnotherUnknowClassFile();
$stub3 = new CoiSA\Example\Stub\LostClassFile();
$stub4 = new ClassWithoutNamespace();

if (\class_exists('TestInterface')) {
    echo "TestInterface\n";
}
