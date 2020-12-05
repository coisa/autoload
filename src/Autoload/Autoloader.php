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
namespace CoiSA\Autoload;

use CoiSA\Autoload\Generator\ClassMapGeneratorInterface;
use Composer\Autoload\ClassLoader;
use Psr\Log\LoggerInterface;

/**
 * Class Autoloader.
 *
 * @package CoiSA\Autoload
 */
final class Autoloader implements AutoloaderInterface
{
    /** @var ClassLoader */
    private $classLoader;

    /** @var ClassMapGeneratorInterface */
    private $classMapGenerator;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Autoloader constructor.
     *
     * @param ClassMapGeneratorInterface $classMapGenerator
     * @param ClassLoader                $classLoader
     * @param LoggerInterface            $logger
     */
    public function __construct(
        ClassMapGeneratorInterface $classMapGenerator,
        ClassLoader $classLoader,
        LoggerInterface $logger
    ) {
        $this->classMapGenerator = $classMapGenerator;
        $this->classLoader       = $classLoader;
        $this->logger            = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $classMap = $this->getClassMap();

        $this->classLoader->addClassMap($classMap);
        $this->classLoader->register();

        $this->logger->debug('ClassMap added to autoloader.', \compact('classMap'));
    }

    /**
     * @return string[]
     */
    public function getClassMap()
    {
        return $this->classMapGenerator->getClassMap();
    }
}
