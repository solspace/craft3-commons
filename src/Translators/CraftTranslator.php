<?php

namespace Solspace\Commons\Translators;

class CraftTranslator implements TranslatorInterface
{
    /**
     * Translates a string
     * Replaces any variables in the $string with variables from $variables
     * User brackets to specify variables in string
     *
     * Example:
     * Translation string: "Hello, {firstName}!"
     * Variables: ["firstName": "Icarus"]
     * End result: "Hello, Icarus!"
     *
     * @param string $category
     * @param string $string
     * @param array  $variables
     *
     * @return string
     */
    public function translate(string $category, string$string, array $variables = []): string
    {
        return \Craft::t($category, $string, $variables);
    }
}
