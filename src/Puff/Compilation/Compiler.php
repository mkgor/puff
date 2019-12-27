<?php

namespace Puff\Compilation;

use Puff\Factory\ElementClassFactory;
use Puff\Exception\InvalidKeywordException;
use Puff\Exception\PuffException;
use Puff\Registry;
use Puff\Tokenization\Entity\Token;
use Puff\Tokenization\Repository\TokenRepositoryInterface;

/**
 * Class Compiler
 * @package Puff\Compilation
 */
class Compiler
{
    /**
     * Replaces all tokens in the template by PHP code
     *
     * @param TokenRepositoryInterface $tokenRepository
     * @param $stringKey
     * @return mixed|string
     *
     * @throws InvalidKeywordException
     * @throws PuffException
     */
    public function compile(TokenRepositoryInterface $tokenRepository, $stringKey)
    {
        if(!empty($tokenRepository->getContainer())) {
            /** @var Token $token */
            foreach ($tokenRepository->getContainer() as $token) {
                $elementClassFactory = new ElementClassFactory();
                $elementClass = $elementClassFactory->getElementClass($token->getTokenName());

                $attributesArray = $token->getAttributes();

                array_walk_recursive($attributesArray, function(&$item) {
                    return (!is_array($item)) ? trim($item) : $item;
                });

                /**
                 * Calling class, which is responsible for processing this token
                 *
                 * @var string $compiledElement
                 */
                $compiledElement = $elementClass->process($token->getAttributes());

                //Updating template string, because elements can modify it
                $templateString = Registry::get($stringKey);

                /** Replacing tokens by PHP code */
                Registry::add($stringKey, $this->replaceToken($token->getFullToken(), $compiledElement, $templateString));
            }
        }

        return Registry::get($stringKey);
    }

    /**
     * @param string $search
     * @param string $replace
     * @param string $text
     * @return mixed
     */
    private function replaceToken($search, $replace, $text){
        $position = strpos($text, $search);

        return $position !== false ? substr_replace($text, $replace, $position, strlen($search)) : $text;
    }
}