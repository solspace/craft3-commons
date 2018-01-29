<?php
/**
 * Freeform for Craft
 *
 * @package       Solspace:Freeform
 * @author        Solspace, Inc.
 * @copyright     Copyright (c) 2008-2016, Solspace, Inc.
 * @link          https://solspace.com/craft/freeform
 * @license       https://solspace.com/software/license-agreement
 */

namespace Solspace\Commons\Helpers;

class StringHelper
{
    /**
     * Replaces all of "{someKey}" occurrences in $string
     * with their respective value counterparts from $values array
     *
     * @param string $string
     * @param array  $values
     *
     * @return string
     */
    public static function replaceValues($string, $values): string
    {
        foreach (self::flattenArrayValues($values) as $key => $value) {
            $string = (string) preg_replace("/\{$key\}/", $value, $string);
        }

        return $string;
    }

    /**
     * @param array $values
     *
     * @return array
     */
    public static function flattenArrayValues(array $values): array
    {
        $return = [];

        foreach ($values as $key => $value) {
            if (\is_array($value)) {
                $value = implode(', ', $value);
            }

            $return[$key] = $value;
        }

        return $return;
    }

    /**
     * Splits an underscored of camelcased string into separate words
     *
     * @param string $string
     *
     * @return string
     */
    public static function humanize($string): string
    {
        $string = strtolower(trim(preg_replace(['/([A-Z])/', "/[_\\s]+/"], ['_$1', ' '], $string)));

        return $string;
    }

    /**
     * Turns every first letter in every word in the string into a camel cased letter
     *
     * @param string $string
     * @param string $delimiter
     *
     * @return string
     */
    public static function camelize($string, $delimiter = ' '): string
    {
        $stringParts = explode($delimiter, $string);
        $camelized = array_map('ucwords', $stringParts);

        return implode($delimiter, $camelized);
    }
}
