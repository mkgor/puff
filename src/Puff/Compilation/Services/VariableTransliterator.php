<?php

namespace Puff\Compilation\Services;

/**
 * Class VariableTransliterator
 * @package Puff\Compilation\Services
 */
class VariableTransliterator
{
    /**
     * @param $variableString
     * @return string|string[]|null
     */
    public static function transliterate($variableString)
    {
        preg_replace_callback('/\.(?<var>\w+)/', function ($result) {
            return $result['var'];
        }, $variableString);
    }
}