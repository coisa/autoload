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
 * Class DirectoryIteratorAggregate
 *
 * @package CoiSA\Autoload\Iterator
 */
final class DirectoryIteratorAggregate implements \IteratorAggregate
{
    /** @var \AppendIterator */
    private $directories;

    /**
     * DirectoryIteratorAggregate constructor.
     */
    public function __construct()
    {
        $this->directories = new \AppendIterator();
    }

    /**
     * @param string $path
     */
    public function addDirectory($path)
    {
        $fileInfo = new \SplFileInfo($path);

        if (false === $fileInfo->isDir()) {
            throw new \UnexpectedValueException('The path given not seen to be a directory.');
        }

        if (false === $fileInfo->isReadable()) {
            throw new \RuntimeException('The path given is not readable.');
        }

        $directoryIterator = new \RecursiveDirectoryIterator(
            $fileInfo->getRealPath(),
            \FilesystemIterator::SKIP_DOTS
        );
        $recursivePhpFileFilterIterator = new RecursivePhpFileFilterIterator($directoryIterator);
        $recursiveIteratorIterator      = new \RecursiveIteratorIterator($recursivePhpFileFilterIterator);

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
