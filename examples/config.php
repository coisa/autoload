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
namespace Coisa\Autoload;

return array(
    'custom_autoload_path' => \dirname(__DIR__) . '/resource/custom_autoload_classmap_file_path.php',

    'directories' => array(
        \dirname(__DIR__) . '/tests/Stubs',
        // \dirname(__DIR__),
    ),
);
