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

use CoiSA\Autoload\Autoloader;
use CoiSA\Autoload\Generator\ClassMapGeneratorInterface;
use Composer\Autoload\ClassLoader;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class AutoloaderFactory
 *
 * @package CoiSA\Autoload\Factory
 */
final class AutoloaderFactory implements AutoloadFactoryInterface, LoggerAwareInterface
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
     * @param LoggerInterface|null       $logger
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

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
