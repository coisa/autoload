<?php

/**
 * This file is part of coisa/autoload.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/autoload
 * @copyright Copyright (c) 2022 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

use CoiSA\Autoload\Generator\ClassMapGeneratorInterface;
use CoiSA\Factory\FactoryInterface;
use Composer\Autoload\ClassLoader;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class AutoloadFactory implements FactoryInterface
{
    /**
     * @var ClassLoader
     */
    private $classLoader;

    /**
     * @var ClassMapGeneratorInterface
     */
    private $classMapGenerator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AutoloaderFactory constructor.
     *
     * @param ClassMapGeneratorInterface $classMapGenerator
     * @param ClassLoader                $classLoader
     * @param null|LoggerInterface       $logger
     */
    public function __construct(
        ClassMapGeneratorInterface $classMapGenerator,
        ClassLoader $classLoader,
        LoggerInterface $logger = null
    ) {
        $this->classMapGenerator = $classMapGenerator;
        $this->classLoader       = $classLoader;
        $this->logger            = $logger ?: new NullLogger();
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        $autoloader = new Autoloader(
            $this->classMapGenerator,
            $this->classLoader,
            $this->logger
        );

        $autoloader->register();

        return $autoloader;
    }
}
