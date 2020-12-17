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
 * Class ClassLoaderAbstractFactory
 *
 * @package CoiSA\Autoload\Factory
 */
final class ClassLoaderAbstractFactory implements AutoloadAbstractFactoryInterface
{
    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function create()
    {
        try {
            $logger = \func_get_arg(0) ?: AbstractFactory::create('Psr\\Log\\LoggerInterface');
        } catch (\Throwable $throwable) {
            $logger = new NullLogger();
        }

        $classLoaderFactory = new ClassLoaderFactory($logger);

        return $classLoaderFactory->create();
    }
}
