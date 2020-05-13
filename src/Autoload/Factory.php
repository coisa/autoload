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

use CoiSA\Autoload\Generator\ClassMapGenerator;
use CoiSA\Autoload\Generator\ClassMapGeneratorFactory;
use CoiSA\Autoload\Generator\ClassMapGeneratorInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Factory
 *
 * @package CoiSA\Autoload
 */
final class Factory
{
    /**
     * @param array                $directories
     * @param null|LoggerInterface $logger
     *
     * @return ClassMapGenerator
     */
    public static function createClassMapGenerator(
        array $directories = array(),
        LoggerInterface $logger = null
    ) {
        $classMapFilePath = ClassMapGeneratorFactory::getClassMapDefaultPath();

        return ClassMapGeneratorFactory::factory($directories, $classMapFilePath, $logger);
    }

    /**
     * @param ClassMapGeneratorInterface|string[] $directories
     * @param null|LoggerInterface                $logger
     *
     * @return Autoloader
     */
    public static function createAutoloader($directories = array(), LoggerInterface $logger = null)
    {
        return AutoloaderFactory::factory($directories, $logger);
    }
}
