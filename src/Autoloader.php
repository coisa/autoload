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
     * Warm up cache.
     *
     * It will warm up all found classes on PHP files of each directory.
     */
    public function cacheWarmUp()
    {
        /** @var \SplFileInfo $file */
        foreach ($this->directories as $file) {
            $this->cacheFileClassMap($file);
        }
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
            $this->cacheFileClassMap($file);

            if ($this->tryLoadFromCache($class)) {
                return true;
            }
        }
    }

    /**
     * @param \SplFileInfo $file
     */
    private function cacheFileClassMap(\SplFileInfo $file)
    {
        $classMap = ClassMapGenerator::createMap($file->getRealPath());

        foreach ($classMap as $fqcn => $path) {
            $this->cacheClassReference($fqcn, $path);
        }
    }

    /**
     * @param string $class
     * @param string $path
     *
     * @return bool
     */
    private function cacheClassReference($class, $path)
    {
        $cacheKey = $this->getCacheKey($class);

        if ($this->cache->has($cacheKey)) {
            $this->logger->warning('Class "{class}" found in "{path}" already set in "{file}".', array(
                'class' => $class,
                'path' => $path,
                'file' => $this->cache->get($cacheKey)
            ));

            return false;
        }

        return $this->cache->set($cacheKey, $path);
    }
}
