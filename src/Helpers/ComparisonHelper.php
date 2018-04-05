<?php

namespace Solspace\Commons\Helpers;

class ComparisonHelper
{
    /**
     * @param string $pattern
     * @param string $string
     *
     * @return bool
     */
    public static function stringContainsWildcardKeyword(string $pattern, string $string): bool
    {
        $transforms = array(
            '\*'    => '.*',
            '\?'    => '.',
            '\[\!'    => '[^',
            '\['    => '[',
            '\]'    => ']',
            '\.'    => '\.',
            '\\'    => '\\\\'
        );

        $pattern = '#\b'
            . strtr(preg_quote($pattern, '#'), $transforms)
            . '\b#i';

        return (bool) preg_match($pattern, $string);
    }
}