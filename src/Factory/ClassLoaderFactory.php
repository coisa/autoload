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

use CoiSA\Autoload\Exception\RuntimeException;
use Composer\Autoload\ClassLoader;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class ClassLoaderFactory.
 *
 * @package CoiSA\Autoload\Factory
 */
final class ClassLoaderFactory implements AutoloadFactoryInterface, LoggerAwareInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ClassLoaderFactory constructor.
     *
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @return ClassLoader
     */
    public function create()
    {
        $includedFiles = \get_included_files();
        $lookup = 'vendor/autoload.php';

        foreach ($includedFiles as $autoloadFile) {
            if (false === \mb_strpos($autoloadFile, $lookup)) {
                continue;
            }

            $this->logger->info('Relying on ClassLoader found in "{realpath}".', array(
                'realpath' => \realpath($autoloadFile),
            ));

            return require $autoloadFile;
        }

        throw RuntimeException::forClassLoaderNotFound($lookup);
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
