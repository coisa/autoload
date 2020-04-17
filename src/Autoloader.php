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

use CoiSA\Autoload\Iterator\RecursiveDirectoryAppendIteratorAggregate;
use Composer\Autoload\ClassMapGenerator;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\SimpleCache\CacheInterface;

/**
 * Class Autoloader
 *
 * @package CoiSA\Autoload
 */
final class Autoloader implements AutoloaderInterface
{
    /** @var CacheInterface */
    private $cache;

    /** @var LoggerInterface */
    private $logger;

    /** @var \Iterator */
    private $directories;

    /**
     * Autoloader constructor.
     *
     * @param CacheInterface       $cache
     * @param null|LoggerInterface $logger
     */
    public function __construct(
        CacheInterface $cache,
        LoggerInterface $logger = null
    ) {
        $this->cache       = $cache;
        $this->logger      = $logger ?: new NullLogger();
        $this->directories = new RecursiveDirectoryAppendIteratorAggregate();
    }

    /**
     * Registers this instance as an autoloader.
     *
     * @return bool
     */
    public function register()
    {
        return \spl_autoload_register(array($this, 'loadClass'), true, false);
    }

    /**
     * Unregisters this instance as an autoloader.
     *
     * @return bool
     */
    public function unregister()
    {
        return \spl_autoload_unregister(array($this, 'loadClass'));
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $class The name of the class
     *
     * @return null|bool True, if loaded
     */
    public function loadClass($class)
    {
        if (false === $this->tryLoadFromCache($class)) {
            return $this->tryLoad($class);
        }
    }

    /**
     * @param string $path
     */
    public function addDirectory($path)
    {
        $this->directories->append($path);
    }

    /**
     * @param string $class
     *
     * @return string
     */
    private function getCacheKey($class)
    {
        return \str_replace('\\', '|', \ltrim($class, '\\'));
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    private function tryLoadFromCache($class)
    {
        $cacheKey = $this->getCacheKey($class);

        if ($this->cache->has($cacheKey)) {
            $file = $this->cache->get($cacheKey);

            require_once $file;

            $this->logger->info(
                'Class "{class}" found in "{file}" loaded.',
                \compact('class', 'file')
            );

            return true;
        }

        return false;
    }

    /**
     * @param string $class
     *
     * @return null|bool
     */
    private function tryLoad($class)
    {
        /** @var \SplFileInfo $file */
        foreach ($this->directories as $file) {
            $classes = ClassMapGenerator::createMap($file->getRealPath());

            foreach ($classes as $className => $path) {
                $cacheKey = $this->getCacheKey($className);
                $this->cache->set($cacheKey, $path);
            }

            if ($this->tryLoadFromCache($class)) {
                return true;
            }
        }
    }
}
