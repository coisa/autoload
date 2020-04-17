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

        $this->directories->append($path);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function loadClass($class)
    {
        if ($this->tryLoadFromCache($class)) {
            return true;
        }

        return $this->tryLoad($class);
    }

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        return \spl_autoload_register(array($this, 'loadClass'), true, false);
    }

    /**
     * {@inheritDoc}
     */
    public function unregister()
    {
        return \spl_autoload_unregister(array($this, 'loadClass'));
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
     * @return bool
     */
    private function tryLoadFromCache($class)
    {
        $cacheKey = $this->getCacheKey($class);

        if (false === $this->cache->has($cacheKey)) {
            return false;
        }

        $file = $this->cache->get($cacheKey);
        $this->loadFile($file, $class);

        return true;
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
     * @param string $file
     * @param string $class
     */
    private function loadFile($file, $class)
    {
        require_once $file;

        $this->logger->info(
            'Class resolution, "{class}" found in "{file}" was loaded.',
            \compact('class', 'file')
        );
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

            if (false === $this->tryLoadFromCache($class)) {
                continue;
            }

            return true;
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

        if (false === $this->cache->has($cacheKey)) {
            return $this->cache->set($cacheKey, $path);
        }

        $this->logger->warning(
            'Ambiguous class resolution, "{class}" was found in both "{file}" and "{path}", the first will be used.',
            array(
                'class' => $class,
                'path'  => $path,
                'file'  => $this->cache->get($cacheKey)
            )
        );

        return false;
    }
}
