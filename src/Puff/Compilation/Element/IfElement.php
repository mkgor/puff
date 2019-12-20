<?php


namespace Puff\Compilation\Element;


use Puff\Compilation\Services\VariableTransliterator;

class IfElement extends AbstractElement
{

    /**
     * @param array $attributes
     * @return mixed
     */
    public function process(array $attributes)
    {
        $predicate = preg_replace_callback('/\b(?:(?!null|true|false|or|and|isset|empty)[a-zA-Z][a-zA-Z0-9]+)+\b/', function($item) {
            return VariableTransliterator::transliterate($item);
        }, $attributes['predicate']);

        return sprintf("<?php if(%s) { ?>", );
    }

    public function handleAttributes($tokenAttributes)
    {
        return ["predicate" => implode(" ", $tokenAttributes)];
    }
}