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

use Composer\Autoload\ClassLoader;
use Composer\Autoload\ClassMapGenerator;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class Autoloader
 *
 * @package CoiSA\Autoload
 */
final class Autoloader implements AutoloaderInterface
{
    /** @var ClassLoader */
    private $classLoader;

    /** @var \SplFileInfo */
    private $classMapCacheFile;

    /** @var LoggerInterface */
    private $logger;

    /** @var string[] */
    private $directories = array();

    /**
     * Autoloader constructor.
     *
     * @param ClassLoader          $classLoader
     * @param string               $classMapCacheFile
     * @param null|LoggerInterface $logger
     */
    public function __construct(
        ClassLoader $classLoader,
        $classMapCacheFile,
        LoggerInterface $logger = null
    ) {
        $this->classLoader       = $classLoader;
        $this->classMapCacheFile = new \SplFileInfo($classMapCacheFile);
        $this->logger            = $logger ?: new NullLogger();
    }

    /**
     * {@inheritDoc}
     */
    public function addDirectory($path)
    {
        if (false === \is_dir($path)) {
            $this->logger->error(
                'Could not scan for classes inside "{path}" which does not appear to be a folder.',
                \compact('path')
            );

            return false;
        }

        $this->directories[] = \realpath($path);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $classMap = $this->getClassMap();
        $this->classLoader->addClassMap($classMap);
    }

    /**
     * @return string[]
     */
    private function getClassMap()
    {
        if ($this->classMapCacheFile->isFile()) {
            $classMap = require $this->classMapCacheFile->getRealPath();
        }

        if (empty($classMap)) {
            $directories = \array_filter(
                \array_unique($this->directories),
                'is_readable'
            );
            $classMapCacheFile = $this->classMapCacheFile->getRealPath() ?: $this->classMapCacheFile->getPathname();

            ClassMapGenerator::dump($directories, $classMapCacheFile);
        }

        return require $this->classMapCacheFile->getRealPath();
    }
}
