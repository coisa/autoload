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

use CoiSA\Factory\AbstractFactory;
use Psr\Log\NullLogger;

/**
 * Class AutoloaderAbstractFactory.
 *
 * @package CoiSA\Autoload\Factory
 */
final class AutoloaderAbstractFactory implements AutoloadAbstractFactoryInterface
{
    /**
     * @return \CoiSA\Autoload\Autoloader
     */
    public static function create()
    {
        try {
            $logger = AbstractFactory::create('Psr\\Log\\LoggerInterface');
        } catch (\Throwable $throwable) {
            $logger = new NullLogger();
        }

        $directories = \func_get_args();
        $classLoader       = AbstractFactory::create('Composer\\Autoload\\ClassLoader', $logger);
        $classMapGenerator = AbstractFactory::create(
            'CoiSA\\Autoload\\Generator\\ClassMapGeneratorInterface',
            $directories,
            $logger
        );
        $autoloaderFactory = new AutoloaderFactory($classMapGenerator, $classLoader, $logger);

        return $autoloaderFactory->create();
    }
}
