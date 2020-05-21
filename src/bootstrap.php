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

return (function ($autoloadFiles) {
    $loader = \array_reduce($autoloadFiles, function ($autoloader, $path) {
        return $autoloader ?? (\file_exists($path) ? require $path : null);
    });

    if (!$loader) {
        throw new \RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
    }

    return $loader;
})([
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php',
]);
