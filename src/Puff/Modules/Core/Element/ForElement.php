<?php


namespace Puff\Modules\Core\Element;


use Puff\Compilation\Element\AbstractElement;
use Puff\Compilation\Service\FilterStringBuilder;
use Puff\Compilation\Service\VariableTransliterator;
use Puff\Tokenization\Configuration;

class ForElement extends AbstractElement
{

    /**
     * [% for variable in key, value %}
     *
     * @param array $attributes
     * @return mixed
     * @throws \Puff\Exception\InvalidFilterException
     */
    public function process(array $attributes)
    {
        $filterStringBuilder = new FilterStringBuilder();
        $filterString = null;

        if(strpos($attributes['iterate'], '~')) {
            $iterateExplode = preg_split(Configuration::FILTER_SPLIT_REGEXP, $attributes['iterate']);

            $builderResult = $filterStringBuilder->buildString(array_slice($iterateExplode, 1), VariableTransliterator::transliterate(array_shift($iterateExplode)));

            $filterString = $builderResult['compiled'];
            $iterate = $builderResult['tmp_variable'];
        } else {
            $iterate = VariableTransliterator::transliterate($attributes['iterate']);
        }

        if(strpos($attributes['item'], ',')) {
            list($key, $value) = explode(",",$attributes['item']);

            $key = VariableTransliterator::transliterate(trim($key));
            $value = VariableTransliterator::transliterate(trim($value));

            $itemString = sprintf("%s => %s", $key, $value);
        } else {
            $itemString = VariableTransliterator::transliterate(trim($attributes['item']));
        }

        return sprintf("%s<?php foreach(%s as %s) { ?>",
            $filterString,
            $iterate,
            $itemString
        );
    }

    /**
     * @param $tokenAttributes
     * @return array
     */
    public function handleAttributes($tokenAttributes)
    {
        $inIndex = array_search('in', $tokenAttributes);

        $iterateSubject = array_slice($tokenAttributes, 0, $inIndex);
        $item = array_slice($tokenAttributes, $inIndex + 1, count($tokenAttributes));

        return [
            'iterate' => implode($iterateSubject),
            'item' => implode($item)
        ];
    }
}