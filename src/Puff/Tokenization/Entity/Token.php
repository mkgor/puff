<?php

namespace Puff\Tokenization\Entity;

/**
 * Class Token
 *
 * @package Tokenization\Entity
 */
class Token
{
    /**
     * @var string
     */
    private $tokenName;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @var string
     */
    private $fullToken;

    /**
     * Token constructor.
     *
     * @param string $tokenName
     * @param array $attributes
     * @param string $fullToken
     */
    public function __construct($tokenName, array $attributes, $fullToken)
    {
        $this->tokenName = $tokenName;
        $this->attributes = $attributes;
        $this->fullToken = $fullToken;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getTokenName()
    {
        return $this->tokenName;
    }

    /**
     * @param string $tokenName
     * @codeCoverageIgnore
     */
    public function setTokenName($tokenName)
    {
        $this->tokenName = $tokenName;
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @codeCoverageIgnore
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getFullToken()
    {
        return $this->fullToken;
    }

    /**
     * @param string $fullToken
     * @codeCoverageIgnore
     */
    public function setFullToken($fullToken)
    {
        $this->fullToken = $fullToken;
    }



    /**
     * Builds an array using object's properties
     *
     * @return array
     */
    public function asArray()
    {
        $result = [];

        foreach (array_keys(get_class_vars(get_class($this))) as $property) {
            $methodName = 'get' . ucfirst($property);

            $result[$property] = $this->{$methodName}();
        }

        return $result;
    }
}