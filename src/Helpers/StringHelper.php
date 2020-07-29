<?php
/**
 * Freeform for Craft.
 *
 * @author        Solspace, Inc.
 * @copyright     Copyright (c) 2008-2016, Solspace, Inc.
 *
 * @see          https://solspace.com/craft/freeform
 *
 * @license       https://solspace.com/software/license-agreement
 */

namespace Solspace\Commons\Helpers;

class StringHelper
{
    /**
     * Replaces all of "{someKey}" occurrences in $string
     * with their respective value counterparts from $values array.
     */
    public static function replaceValues(string $string, array $values): string
    {
        foreach (self::flattenArrayValues($values) as $key => $value) {
            $string = (string) preg_replace("/\{$key\}/", $value, $string);
        }

        return $string;
    }

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
     * Splits an underscored of camelcased string into separate words.
     */
    public static function humanize(string $string): string
    {
        $string = strtolower(trim(preg_replace(['/([A-Z])/', '/[_\\s]+/'], ['_$1', ' '], $string)));

        return $string;
    }

    /**
     * Turns every first letter in every word in the string into a camel cased letter.
     */
    public static function camelize(string $string, string $delimiter = ' '): string
    {
        $stringParts = explode($delimiter, $string);
        $camelized = array_map('ucwords', $stringParts);

        return implode($delimiter, $camelized);
    }

    /**
     * Walk through the array and create an HTML tag attribute string.
     */
    public static function compileAttributeStringFromArray(array $array): string
    {
        $attributeString = '';

        foreach ($array as $key => $value) {
            if (null === $value || (\is_bool($value) && $value)) {
                $attributeString .= " $key";
            } elseif (!\is_bool($value)) {
                $attributeString .= " $key=\"$value\"";
            }
        }

        return $attributeString ? $attributeString : '';
    }

    /**
     * Takes any items separated by a whitespace or any of the following `|,;` in a string
     * And returns an array of the items.
     */
    public static function extractSeparatedValues(string $string): array
    {
        $string = preg_replace('/[\s|,;]+/', '<|_|_|>', $string);

        $items = explode('<|_|_|>', $string);
        $items = array_filter($items);
        $items = array_unique($items);
        $items = array_values($items);

        return $items;
    }

    /**
     * @param string       $glue
     * @param array|string $data
     *
     * @return string
     */
    public static function implodeRecursively(string $glue, $data): string
    {
        if (!\is_array($data)) {
            return $data;
        }

        $pieces = [];
        foreach ($data as $item) {
            if (\is_array($item)) {
                $pieces[] = self::implodeRecursively($glue, $item);
            } else {
                $pieces[] = $item;
            }
        }

        return implode($glue, $pieces);
    }
}
