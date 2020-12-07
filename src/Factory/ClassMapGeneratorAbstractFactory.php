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

namespace CoiSA\Autoload\Factory;

use CoiSA\Autoload\Generator\ClassMapGeneratorInterface;
use CoiSA\Factory\AbstractFactory;
use Psr\Log\NullLogger;

/**
 * Class ClassMapGeneratorAbstractFactory
 *
 * @package CoiSA\Autoload\Factory
 */
final class ClassMapGeneratorAbstractFactory implements AutoloadAbstractFactoryInterface
{
    /**
     * @var string
     */
    private static $classMapDefaultPath;

    /**
     * @return ClassMapGeneratorInterface
     */
    public static function create()
    {
        try {
            $logger = \func_get_arg(1) ?: AbstractFactory::create('Psr\\Log\\LoggerInterface');
        } catch (\Throwable $throwable) {
            $logger = new NullLogger();
        }

        $directories                          = \func_get_arg(0);
        $path                                 = self::getClassMapDefaultPath();
        $phpArrayFileClassMapGeneratorFactory = new PhpArrayFileClassMapGeneratorFactory($path, $logger);

        return \call_user_func_array(array($phpArrayFileClassMapGeneratorFactory, 'create'), $directories);
    }

    /**
     * Return default classmap cache file path.
     *
     * @return string
     */
    public static function getClassMapDefaultPath()
    {
        if (empty(self::$classMapDefaultPath)) {
            self::$classMapDefaultPath = __DIR__ . '/../../resource/autoload_classmap.php';
        }

        return self::$classMapDefaultPath;
    }

    /**
     * @param string $path
     */
    public static function setClassMapDefaultPath($path)
    {
        self::$classMapDefaultPath = $path;
    }
}
