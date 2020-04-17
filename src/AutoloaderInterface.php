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

/**
 * Interface AutoloaderInterface
 *
 * @package CoiSA\Autoload
 */
interface AutoloaderInterface
{
    /**
     * @param string $path
     */
    public function addDirectory($path);

    /**
     * Loads the given class or interface.
     *
     * @param string $class The name of the class
     *
     * @return null|bool True, if loaded
     */
    public function loadClass($class);

    /**
     * Registers this instance as an autoloader.
     *
     * @return bool
     */
    public function register();

    /**
     * Unregisters this instance as an autoloader.
     *
     * @return bool
     */
    public function unregister();
}
