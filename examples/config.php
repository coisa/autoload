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

namespace Coisa\Autoload;

/**
 * Class Config
 *
 * @package Coisa\Autoload
 */
final class ConfigProvider
{
    /**
     * @return mixed[]
     */
    public function __invoke()
    {
        return array(
            'directories' => array(
                __DIR__,
                \dirname(__DIR__) . '/tests/stubs',
            ),
            'custom_autoload_path' => __DIR__ . '/custom_autoload_classmap_file_path.php',
        );
    }
}

$configProvider = new ConfigProvider();

return $configProvider();
