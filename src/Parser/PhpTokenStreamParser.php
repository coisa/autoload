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

namespace CoiSA\Autoload\Parser;

/**
 * Class PhpTokenStreamParser
 *
 * @package CoiSA\Autoload\Parser
 */
final class PhpTokenStreamParser implements ParserInterface
{
    /**
     * @param string $path
     *
     * @return array
     */
    public function findClasses(\SplFileInfo $fileInfo)
    {
        $classes = array();

        if ('php' !== $fileInfo->getExtension()) {
            return $classes;
        }

        $tokens = $this->getTokens($fileInfo);

        $T_TRAIT = PHP_VERSION_ID < 50400 ? -1 : T_TRAIT;

        $namespace = '';
        for ($i = 0; isset($tokens[$i]); ++$i) {
            $token = $tokens[$i];

            if (!isset($token[1])) {
                continue;
            }

            $class = '';

            switch ($token[0]) {
                case T_NAMESPACE:
                    $namespace = '';
                    // If there is a namespace, extract it
                    while (isset($tokens[++$i][1])) {
                        if (\in_array($tokens[$i][0], array(T_STRING, T_NS_SEPARATOR))) {
                            $namespace .= $tokens[$i][1];
                        }
                    }
                    $namespace .= '\\';

                    break;
                case T_CLASS:
                case T_INTERFACE:
                case $T_TRAIT:
                    // Skip usage of ::class constant
                    $isClassConstant = false;
                    for ($j = $i - 1; $j > 0; --$j) {
                        if (!isset($tokens[$j][1])) {
                            break;
                        }

                        if (T_DOUBLE_COLON === $tokens[$j][0]) {
                            $isClassConstant = true;

                            break;
                        }

                        if (!\in_array($tokens[$j][0], array(T_WHITESPACE, T_DOC_COMMENT, T_COMMENT))) {
                            break;
                        }
                    }

                    if ($isClassConstant) {
                        break;
                    }

                    // Find the classname
                    while (isset($tokens[++$i][1])) {
                        $t = $tokens[$i];
                        if (T_STRING === $t[0]) {
                            $class .= $t[1];
                        } elseif ('' !== $class && T_WHITESPACE === $t[0]) {
                            break;
                        }
                    }

                    $classes[] = \ltrim($namespace . $class, '\\');

                    break;
                default:
                    break;
            }
        }

        if (\PHP_VERSION_ID >= 70000) {
            /* @see https://bugs.php.net/70098 */
            \gc_mem_caches();
        }

        return $classes;
    }

    /**
     * @param \SplFileInfo $fileInfo
     *
     * @return array
     */
    private function getTokens(\SplFileInfo $fileInfo)
    {
        $fileObject = $fileInfo->openFile();
        $contents = $fileObject->fread($fileObject->getSize());

        return \token_get_all($contents);
    }
}
