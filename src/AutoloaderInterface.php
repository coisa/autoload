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

namespace CoiSA\Autoload;

/**
 * Interface AutoloaderInterface.
 *
 * @package CoiSA\Autoload
 */
interface AutoloaderInterface
{
    /**
     * Registers this instance as an autoloader.
     *
     * @return void
     */
    public function register();
}
