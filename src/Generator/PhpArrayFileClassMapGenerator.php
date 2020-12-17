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

namespace CoiSA\Autoload\Generator;

use Composer\Autoload\ClassMapGenerator;
use Psr\Log\LoggerInterface;

/**
 * Class PhpArrayFileClassMapGenerator.
 *
 * @package CoiSA\Autoload\Generator
 */
final class PhpArrayFileClassMapGenerator implements ClassMapGeneratorInterface
{
    /** @var \SplFileInfo */
    private $classMapCacheFile;

    /** @var LoggerInterface */
    private $logger;

    /** @var string[] */
    private $directories = array();

    /**
     * PhpArrayFileClassMapGenerator constructor.
     *
     * @param string          $classMapCacheFile
     * @param LoggerInterface $logger
     */
    public function __construct(
        $classMapCacheFile,
        LoggerInterface $logger
    ) {
        $this->classMapCacheFile = new \SplFileInfo($classMapCacheFile);
        $this->logger            = $logger;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getClassMap()
    {
        if ($this->classMapCacheFile->isFile()) {
            $classMap = $this->includeClassMap();
        }

        if (empty($classMap)) {
            $classMap = $this->generateClassMap();
        }

        return $classMap;
    }

    /**
     * {@inheritdoc}
     */
    public function generateClassMap()
    {
        $directories = \array_filter(
            \array_unique($this->directories),
            'is_readable'
        );
        $classMapFile = $this->classMapCacheFile->getRealPath() ?: $this->classMapCacheFile->getPathname();

        ClassMapGenerator::dump($directories, $classMapFile);

        $classMapFile = $this->classMapCacheFile->getRealPath();
        $classMap     = $this->includeClassMap();

        $this->logger->notice('Classmap "{classMapFile}" was created.', \compact('classMapFile', 'classMap'));

        return $classMap;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Load file with classmap array references.
     *
     * @return string[]
     */
    private function includeClassMap()
    {
        return includePhpFile($this->classMapCacheFile->getRealPath());
    }
}

/**
 * Scope isolated include.
 *
 * Prevents access to $this/self from included files.
 *
 * @param mixed $file
 */
function includePhpFile($file)
{
    return include $file;
}
