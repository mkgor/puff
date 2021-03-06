<?php


namespace Puff\Compilation\Element;

use Puff\Exception\PuffException;
use Puff\Registry;
use Puff\Tokenization\Configuration;
use Puff\Tokenization\Syntax\SyntaxInterface;

/**
 * Class AbstractElement
 * @package Puff\Compilation\Element
 */
abstract class AbstractElement implements ElementInterface
{
    /**
     * General algorithm of attributes parsing. Can be overwritten by element
     *
     * @param $tokenAttributes
     * @return array
     */
    public function handleAttributes($tokenAttributes) {
        /** @var SyntaxInterface $syntax */
        $syntax = Registry::get('syntax');

        $tokenAttributesArray = [];

        foreach($tokenAttributes as $tokenAttribute) {
            list($attribute, $value) = explode($syntax->getEqualitySymbol(), $tokenAttribute);

            $tokenAttributesArray[$attribute] = trim($value, '\'"');
        }

        return $tokenAttributesArray;
    }

    /**
     * @param array $attributes
     *
     * @return mixed|void
     * @throws PuffException
     *
     * @codeCoverageIgnore
     */
    public function process(array $attributes)
    {
        throw new PuffException(sprintf("Process method not implemented for %s", get_called_class()));
    }
}