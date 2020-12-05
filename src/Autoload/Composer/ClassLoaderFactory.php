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
namespace CoiSA\Autoload\Composer;

use Composer\Autoload\ClassLoader;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class ClassLoaderFactory.
 *
 * @package CoiSA\Autoload\Composer
 */
final class ClassLoaderFactory
{
    /**
     * @param LoggerInterface|null $logger
     *
     * @return ClassLoader
     */
    public static function factory(LoggerInterface $logger = null)
    {
        $logger        = $logger ?: new NullLogger();
        $includedFiles = \get_included_files();

        foreach ($includedFiles as $autoloadFile) {
            if (false === \mb_strpos($autoloadFile, 'vendor/autoload.php')) {
                continue;
            }

            $logger->info('Relying on first ClassLoader found "{realpath}".', array(
                'realpath' => \realpath($autoloadFile),
            ));

            return require $autoloadFile;
        }

        throw new \RuntimeException(
            'vendor/autoload.php could not be found. Did you run `php composer.phar install`?'
        );
    }
}
