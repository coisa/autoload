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

use CoiSA\Autoload\Generator\ClassMapGeneratorInterface;
use Composer\Autoload\ClassLoader;
use Psr\Log\LoggerInterface;

/**
 * Class AutoloaderFactory
 *
 * @package CoiSA\Autoload
 */
final class AutoloaderFactory
{
    /**
     * @param ClassMapGeneratorInterface|string[] $directories
     * @param null|LoggerInterface                $logger
     *
     * @return Autoloader
     */
    public static function factory($directories = array(), LoggerInterface $logger = null)
    {
        $classMapGenerator = $directories instanceof ClassMapGeneratorInterface ?
            $directories : Factory::createClassMapFileGenerator(
                $directories,
                $logger
            );

        $classLoader = new ClassLoader();
        $classLoader->register();

        return new Autoloader($classMapGenerator, $classLoader);
    }
}
