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

namespace CoiSA\Autoload\Exception;

/**
 * Class RuntimeException
 *
 * @package CoiSA\Autoload\Exception
 */
final class RuntimeException extends \CoiSA\Exception\Spl\RuntimeException implements AutoloadExceptionInterface
{
    /**
     * @const string
     */
    const MESSAGE_CLASS_LOADER_NOT_FOUD = '%s could not be found. Did you run `php composer.phar install`?';

    /**
     * @param int $code
     * @param null|\Exception|\Throwable $previous
     *
     * @return \CoiSA\Exception\Throwable|mixed
     */
    public static function forClassLoaderNotFound($path, $code = 0, $previous = null)
    {
        $message = \sprintf(
            self::MESSAGE_CLASS_LOADER_NOT_FOUD,
            $path
        );

        return self::create($message, $code, $previous);
    }
}
