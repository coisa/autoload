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
 * Class RecursivePhpFileFilterIterator
 *
 * @package CoiSA\Autoload\Iterator
 */
final class RecursivePhpFileFilterIterator extends \RecursiveFilterIterator
{
    /**
     * RecursivePhpFileFilterIterator constructor.
     *
     * @param \RecursiveDirectoryIterator $recursiveDirectoryIterator
     */
    public function __construct(\RecursiveDirectoryIterator $recursiveDirectoryIterator)
    {
        parent::__construct($recursiveDirectoryIterator);
    }

    /**
     * @return bool
     */
    public function accept()
    {
        /** @var \SplFileInfo $fileInfo */
        $fileInfo = $this->current();

        if ($fileInfo->isDir() && $fileInfo->getBasename() === 'vendor') {
            return false;
        }

        if ($fileInfo->isFile() && $fileInfo->getExtension() !== 'php') {
            return false;
        }

        return true;
    }
}
