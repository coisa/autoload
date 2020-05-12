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

use CoiSA\Autoload\Generator\ClassMapGeneratorInterface;
use Composer\Autoload\ClassLoader;

/**
 * Class Autoloader
 *
 * @package CoiSA\Autoload
 */
final class Autoloader implements AutoloaderInterface
{
    /** @var ClassLoader */
    private $classLoader;

    /** @var ClassMapGeneratorInterface */
    private $classMapGenerator;

    /**
     * Autoloader constructor.
     *
     * @param ClassLoader                $classLoader
     * @param ClassMapGeneratorInterface $classMapGenerator
     */
    public function __construct(
        ClassLoader $classLoader,
        ClassMapGeneratorInterface $classMapGenerator
    ) {
        $this->classLoader       = $classLoader;
        $this->classMapGenerator = $classMapGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $classMap = $this->classMapGenerator->getClassMap();

        $this->classLoader->addClassMap($classMap);
    }
}
