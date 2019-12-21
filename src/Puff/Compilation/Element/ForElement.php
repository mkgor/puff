<?php


namespace Puff\Compilation\Element;


use Puff\Compilation\Service\VariableTransliterator;

class ForElement extends AbstractElement
{

    /**
     * [% for variable in key, value %}
     *
     * @param array $attributes
     * @return mixed
     */
    public function process(array $attributes)
    {
        if(strpos($attributes['item'], ',')) {
            list($key, $value) = explode(",",$attributes['item']);

            $key = VariableTransliterator::transliterate(trim($key));
            $value = VariableTransliterator::transliterate(trim($value));

            $itemString = sprintf("%s => %s", $key, $value);
        } else {
            $itemString = VariableTransliterator::transliterate(trim($attributes['item']));
        }

        return sprintf("<?php foreach(%s as %s) { ?>",
            VariableTransliterator::transliterate($attributes['iterate']),
            $itemString
        );
    }

    /**
     * @param $tokenAttributes
     * @return array
     */
    public function handleAttributes($tokenAttributes)
    {
        $iterateSubject = array_shift($tokenAttributes);

        /** Deleting `in` from attributes */
        array_shift($tokenAttributes);

        $item = implode("",$tokenAttributes);

        return [
            'iterate' => $iterateSubject,
            'item' => $item
        ];
    }
}