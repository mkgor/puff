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
        $registeredElements = Registry::get('registered_elements');

        if (isset($registeredElements[$tokenName])) {
            /** @var ElementInterface $elementClass */
            $elementClass = $registeredElements[$tokenName];
        } else {
            ob_end_clean();

            throw new InvalidKeywordException($tokenName);
        }

        return $elementClass;
    }
}