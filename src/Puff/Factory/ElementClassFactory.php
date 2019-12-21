<?php


namespace Puff\Factory;


use Puff\Compilation\Element\ElementInterface;
use Puff\Exception\InvalidKeywordException;
use Puff\Exception\PuffException;
use Puff\Registry;

/**
 * Class ElementClassFactory
 * @package Factory
 */
class ElementClassFactory
{
    /**
     * @param $tokenName
     *
     * @return ElementInterface|string
     *
     * @throws InvalidKeywordException
     * @throws PuffException
     */
    public function getElementClass($tokenName)
    {
        $elementClass = "\Puff\Compilation\Element\\" . $tokenName ."Element";

        if(!class_exists($elementClass)) {
            $customKeywords = Registry::get('custom_keywords');

            if(isset($customKeywords[$tokenName])) {
                $elementClass = $customKeywords[$tokenName];
            } else {
                throw new InvalidKeywordException($tokenName, get_called_class());
            }
        } else {
            /** @var ElementInterface $elementClass */
            $elementClass = new $elementClass;
        }

        if(!($elementClass instanceof ElementInterface)) {
            throw new PuffException(sprintf('Element with name `%s` is not instance of ElementInterface', $tokenName));
        }

        return $elementClass;
    }
}