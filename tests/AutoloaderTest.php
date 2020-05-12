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

namespace Test;

use CoiSA\Autoload\Autoloader;
use Composer\Autoload\ClassLoader;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

/**
 * Class AutoloaderTest
 *
 * @package Test
 */
final class AutoloaderTest extends TestCase
{
    /** @var ClassLoader|ObjectProphecy */
    private $classLoader;

    /** @var LoggerInterface|ObjectProphecy */
    private $logger;

    /** @var Autoloader */
    private $autoloader;

    public function setUp(): void
    {
        $this->classLoader = $this->prophesize(ClassLoader::class);
        $this->logger      = $this->prophesize(LoggerInterface::class);

        $this->autoloader = new Autoloader(
            $this->classLoader->reveal(),
            \sys_get_temp_dir() . '/' . \uniqid('autoload_classmap', false),
            $this->logger->reveal()
        );
    }
}
