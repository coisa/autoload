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

/**
 * Class ClassWithoutNamespace.
 */
class ClassWithoutNamespace
{
    public function __construct()
    {
        echo \get_class($this) . "\n";
    }
}
