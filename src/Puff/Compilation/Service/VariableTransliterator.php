<?php

namespace Puff\Compilation\Service;

/**
 * Class VariableTransliterator
 * @package Puff\Compilation\Service
 */
class VariableTransliterator
{
    /**
     * @param $variableString
     * @return string|string[]|null
     */
    public static function transliterate($variableString)
    {
        $variable = preg_replace('/^([a-zA-Z][a-zA-Z0-9])/', '\$$0', $variableString);

        /** Checking, if provided string is variable and if there are calling array item, it transliterates it into PHP syntax */
        return preg_replace_callback('/([\'"]).*\1(*SKIP)(*F)|\.(?<value>[a-zA-Z0-9_]+)/', function ($result) {
            return sprintf("['%s']", trim($result['value']));
        }, $variable);
    }
}