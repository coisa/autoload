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

namespace CoiSA\Autoload\Generator;

use Psr\Log\LoggerInterface;

/**
 * Class ClassMapGeneratorFactory
 *
 * @package CoiSA\Autoload\Generator
 */
final class ClassMapGeneratorFactory
{
    /**
     * Return default classmap cache file path.
     *
     * @return string
     */
    public static function getClassMapDefaultPath()
    {
        return \dirname(__DIR__) . '/../autoload_classmap.php';
    }

    /**
     * @param array                $directories
     * @param null                 $path
     * @param null|LoggerInterface $logger
     *
     * @return ClassMapFileGenerator
     */
    public static function factory(
        array $directories = array(),
        $path = null,
        LoggerInterface $logger = null
    ) {
        $classMapFileGenerator = new ClassMapFileGenerator(
            $path ?: self::getClassMapDefaultPath(),
            $logger
        );

        foreach ($directories as $directory) {
            $classMapFileGenerator->addDirectory($directory);
        }

        return $classMapFileGenerator;
    }
}
