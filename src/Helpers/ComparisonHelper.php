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
        $pattern = '#\b' . self::wildcardToRegex($pattern) . '\b#iu';

        return (bool) preg_match($pattern, $string);
    }

    /**
     * @param string $wildcardPattern
     * @param string $string
     *
     * @return bool
     */
    public static function stringMatchesWildcard(string $wildcardPattern, string $string): bool
    {
        $pattern = '#^' . self::wildcardToRegex($wildcardPattern). '$#iu';

        return (bool) preg_match($pattern, $string);
    }

    /**
     * @param string $wildcardPattern
     * @param string $delimiter
     *
     * @return string
     */
    private static function wildcardToRegex(string $wildcardPattern, string $delimiter = '/'): string
    {
        $converted = preg_quote($wildcardPattern, $delimiter);
        $converted = str_replace('\*', '.*', $converted);

        return $converted;
    }
}
