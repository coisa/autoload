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

namespace CoiSA\Autoload\Composer;

use Composer\Autoload\ClassLoader;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;

/**
 * Class ClassLoaderFactory.
 *
 * @package CoiSA\Autoload\Composer
 */
final class ClassLoaderFactory
{
    /**
     * @param null|LoggerInterface $logger
     *
     * @throws RuntimeException if the class loader is not found
     *
     * @return ClassLoader
     */
    public static function factory(LoggerInterface $logger = null)
    {
        $logger = $logger ?: new NullLogger();

        try {
            return self::getClassLoaderFromSplAutoloadFunctions($logger);
        } catch (RuntimeException $runtimeException) {
            return self::getClassLoaderFromIncludedFiles($logger);
        }
    }

    /**
     * '     * Try to find the ClassLoader in registered autoloader functions.
     *
     * @param LoggerInterface $logger
     *
     * @return ClassLoader
     */
    private static function getClassLoaderFromSplAutoloadFunctions(LoggerInterface $logger)
    {
        $autoloaderFunctions = spl_autoload_functions();

        foreach ($autoloaderFunctions as $autoloader) {
            if (false === \is_array($autoloader)
                && false === ($autoloader[0] instanceof ClassLoader)
            ) {
                continue;
            }

            $classLoader     = $autoloader[0];
            $reflectionClass = new \ReflectionClass($classLoader);

            $realpath = $reflectionClass->getFileName();

            $logger->info(
                'Relying on first ClassLoader found from "spl_autoload_functions" defined at "{realpath}".',
                compact('realpath')
            );

            return $autoloader[0];
        }

        throw new \RuntimeException('ClassLoader could not be found into registered autoloader functions.');
    }

    /**
     * Try to find the ClassLoader in included files.
     *
     * @param LoggerInterface $logger
     *
     * @return ClassLoader
     */
    private static function getClassLoaderFromIncludedFiles(LoggerInterface $logger)
    {
        $includedFiles = get_included_files();

        foreach ($includedFiles as $autoloadFile) {
            if (false === mb_strpos($autoloadFile, 'vendor/autoload.php')) {
                continue;
            }

            $logger->info('Relying on first ClassLoader found "{realpath}".', array(
                'realpath' => \realpath($autoloadFile)
            ));

            return require $autoloadFile;
        }

        throw new RuntimeException(
            'vendor/autoload.php could not be found. Did you run `php composer.phar install`?'
        );
    }
}
