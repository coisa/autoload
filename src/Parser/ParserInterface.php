<?php

namespace CoiSA\Autoload\Parser;

/**
 * Interface ParserInterface
 *
 * @package CoiSA\Autoload\Parser
 */
interface ParserInterface
{
    /**
     * @param \SplFileInfo $fileInfo
     *
     * @return array
     */
    public function findClasses(\SplFileInfo $fileInfo);
}
