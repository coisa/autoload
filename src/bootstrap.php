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

/*
 * @param string[] $autoloadFiles
 *
 * @return Composer\Autoload\ClassLoader
 *
 * @throws \RuntimeException When could not found any near composer ClassLoader autoloader file.
 */
return (function ($autoloadFiles) {
    foreach ($autoloadFiles as $autoloadFile) {
        if (\file_exists($autoloadFile)) {
            return require $autoloadFile;
        }
    }

    throw new \RuntimeException(
        'vendor/autoload.php could not be found. Did you run `php composer.phar install`?'
    );
})(array(
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php',
));
