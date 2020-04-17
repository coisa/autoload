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

namespace CoiSA\Autoload\Iterator;

/**
 * Class RecursiveDirectoryAppendIteratorAggregate
 *
 * @package CoiSA\Autoload\Iterator
 */
final class RecursiveDirectoryAppendIteratorAggregate implements \IteratorAggregate
{
    /** @var int */
    const FLAGS = \FilesystemIterator::SKIP_DOTS;

    /** @var \AppendIterator */
    private $directories;

    /**
     * RecursiveDirectoryAppendIteratorAggregate constructor.
     */
    public function __construct()
    {
        $this->directories = new \AppendIterator();
    }

    /**
     * @param string $path
     *
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    public function append($path)
    {
        $directoryIterator                       = new \RecursiveDirectoryIterator($path, self::FLAGS);
        $recursiveDirectoryPhpFileFilterIterator = new RecursiveDirectoryPhpFileFilterIterator($directoryIterator);
        $recursiveIteratorIterator               = new \RecursiveIteratorIterator(
            $recursiveDirectoryPhpFileFilterIterator
        );

        $this->directories->append($recursiveIteratorIterator);
    }

    /**
     * @return \AppendIterator
     */
    public function getIterator()
    {
        return $this->directories;
    }
}
