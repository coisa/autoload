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

use CoiSA\Autoload\Generator\PhpArrayFileClassMapGenerator;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class PhpArrayFileClassMapGeneratorFactory.
 *
 * @package CoiSA\Autoload\Factory
 */
final class PhpArrayFileClassMapGeneratorFactory implements AutoloadFactoryInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * PhpArrayFileClassMapGeneratorFactory constructor.
     *
     * @param string               $path
     * @param LoggerInterface|null $logger
     */
    public function __construct($path, LoggerInterface $logger = null)
    {
        $this->path   = $path;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @return PhpArrayFileClassMapGenerator
     */
    public function create()
    {
        $directories                       = \func_get_args();
        $phpArrayFileClassMapFileGenerator = new PhpArrayFileClassMapGenerator($this->path, $this->logger);

        foreach ($directories as $directory) {
            $phpArrayFileClassMapFileGenerator->addDirectory($directory);
        }

        return $phpArrayFileClassMapFileGenerator;
    }
}
