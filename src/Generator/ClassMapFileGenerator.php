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

namespace CoiSA\Autoload\Generator;

use Composer\Autoload\ClassMapGenerator as ComposerClassMapGenerator;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class ClassMapFileGenerator
 *
 * @package CoiSA\Autoload\Generator
 */
final class ClassMapFileGenerator implements ClassMapGeneratorInterface
{
    /** @var \SplFileInfo */
    private $classMapCacheFile;

    /** @var LoggerInterface */
    private $logger;

    /** @var string[] */
    private $directories = array();

    /**
     * ClassMapFileGenerator constructor.
     *
     * @param $classMapCacheFile
     * @param null|LoggerInterface $logger
     */
    public function __construct(
        $classMapCacheFile,
        LoggerInterface $logger = null
    ) {
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
     * {@inheritDoc}
     */
    public function generateClassMap()
    {
        $directories = \array_filter(
            \array_unique($this->directories),
            'is_readable'
        );
        $classMapCacheFile = $this->classMapCacheFile->getRealPath() ?: $this->classMapCacheFile->getPathname();

        ComposerClassMapGenerator::dump($directories, $classMapCacheFile);

        return $this->includeClassMap();
    }

    /**
     * Load file with classmap array references.
     *
     * @return string[]
     */
    private function includeClassMap()
    {
        return include $this->classMapCacheFile->getRealPath();
    }
}
