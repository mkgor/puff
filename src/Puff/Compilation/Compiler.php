<?php

namespace Puff\Compilation;

use Puff\Compilation\Element\ElementInterface;
use Puff\Exception\InvalidKeywordException;
use Puff\Exception\PuffException;
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
     * @param string $templateString
     *
     * @return mixed|string
     *
     * @throws InvalidKeywordException
     * @throws PuffException
     */
    public function compile(TokenRepositoryInterface $tokenRepository, $templateString)
    {
        /** @var Token $token */
        foreach($tokenRepository->getContainer() as $token) {
            $elementClass = "\Puff\Compilation\Element\\" . ucfirst($token->getTokenName());

            if(!class_exists($elementClass)) {
                throw new InvalidKeywordException($token->getTokenName(), __CLASS__);
            }

            $elementClass = new $elementClass;

            if(!($elementClass instanceof ElementInterface)) {
                throw new PuffException('Invalid element provided to compiler');
            }

            /**
             * Calling class, which is responsible for processing this token
             *
             * @var string $compiledElement
             */
            $compiledElement = $elementClass->process($token->getAttributes());

            /** Replacing tokens by PHP code */
            $templateString = str_replace($token->getFullToken(), $compiledElement, $templateString);
        }

        return $templateString;
    }
}