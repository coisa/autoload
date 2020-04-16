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

    /** @var \AppendIterator */
    private $directories;

    /**
     * Autoloader constructor.
     *
     * @param CacheInterface       $cache
     * @param null|LoggerInterface $logger
     */
    public function __construct(CacheInterface $cache, LoggerInterface $logger = null)
    {
        $this->cache  = $cache;
        $this->logger = $logger ?: new NullLogger();

        $this->directories = new \AppendIterator();
    }

    /**
     * Registers this instance as an autoloader.
     *
     * @param bool $prepend Whether to prepend the autoloader or not
     */
    public function register($prepend = false)
    {
        \spl_autoload_register(array($this, 'loadClass'), true, $prepend);
    }

    /**
     * Unregisters this instance as an autoloader.
     */
    public function unregister()
    {
        \spl_autoload_unregister(array($this, 'loadClass'));
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
        $fileInfo = new \SplFileInfo($path);

        if (false === $fileInfo->isDir()) {
            throw new \UnexpectedValueException('The path given not seen to be a directory.');
        }

        if (false === $fileInfo->isReadable()) {
            throw new \RuntimeException('The path given is not readable.');
        }

        $directoryIterator         = new \RecursiveDirectoryIterator($fileInfo->getRealPath());
        $recursiveIteratorIterator = new \RecursiveIteratorIterator($directoryIterator);

        $this->directories->append($recursiveIteratorIterator);
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
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @return null|bool
     *
     * @TODO do not parse again not modified files
     * @TODO ignore files with vendor in path            $phpTokenStream = new PHP_Token_Stream($path);
     */
    private function tryLoad($class)
    {
        /** @var \SplFileObject $file */
        foreach ($this->directories as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $path = $file->getRealPath() ?: $file->getPathname();

            if ('php' !== \pathinfo($path, PATHINFO_EXTENSION)) {
                continue;
            }

            $phpTokenStream = \PHP_Token_Stream_CachingFactory::get($path);
            $classes        = $phpTokenStream->getClasses();

            foreach ($classes as $className => $info) {
                $cacheKey = $this->getCacheKey($info['package']['namespace'] . '\\' . $className);

                $this->cache->set($cacheKey, $info['file']);
            }

            if ($this->tryLoadFromCache($class)) {
                return true;
            }
        }
    }
}
