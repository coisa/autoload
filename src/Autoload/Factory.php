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

use CoiSA\Autoload\Composer\ClassLoaderFactory;
use CoiSA\Autoload\Generator\ClassMapGenerator;
use CoiSA\Autoload\Generator\ClassMapGeneratorFactory;
use CoiSA\Autoload\Generator\ClassMapGeneratorInterface;
use Composer\Autoload\ClassLoader;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Factory
 *
 * @package CoiSA\Autoload
 */
final class Factory
{
    /**
     * @param array                $directories
     * @param null                 $classMapFilePath
     * @param null|LoggerInterface $logger
     *
     * @return ClassMapGenerator
     */
    public static function createClassMapGenerator(
        array $directories = array(),
        $classMapFilePath = null,
        LoggerInterface $logger = null
    ) {
        $classMapFilePath = $classMapFilePath ?: ClassMapGeneratorFactory::getClassMapDefaultPath();

        return ClassMapGeneratorFactory::factory($directories, $classMapFilePath, $logger);
    }

    /**
     * @param ClassMapGeneratorInterface|string[] $directories
     * @param null|LoggerInterface                $logger
     * @param null|ClassLoader                    $classLoader
     *
     * @return Autoloader
     */
    public static function createAutoloader(
        $directories,
        LoggerInterface $logger = null,
        ClassLoader $classLoader = null
    ) {
        $classLoader = $classLoader ?: self::createClassLoader($logger);

        return AutoloaderFactory::factory($directories, $classLoader, $logger);
    }

    /**
     * @param bool $isDebug
     *
     * @return LoggerInterface
     */
    public static function createLogger($isDebug = false)
    {
        $verbosity = $isDebug ? OutputInterface::VERBOSITY_DEBUG : OutputInterface::VERBOSITY_NORMAL;
        $output = new ConsoleOutput($verbosity);

        return new ConsoleLogger($output);
    }

    /**
     * @param LoggerInterface|null $logger
     *
     * @return ClassLoader
     */
    public static function createClassLoader(LoggerInterface $logger = null)
    {
        return ClassLoaderFactory::factory($logger);
    }
}
