<?php

namespace CoiSA\Autoload\Factory;

use _HumbugBoxaf515cad4e15\Psr\Log\NullLogger;
use CoiSA\Factory\AbstractFactory;

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
