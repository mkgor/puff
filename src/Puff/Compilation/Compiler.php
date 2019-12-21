<?php

namespace Puff\Compilation;

use Puff\Factory\ElementClassFactory;
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
        if(!empty($tokenRepository->getContainer())) {
            /** @var Token $token */
            foreach ($tokenRepository->getContainer() as $token) {
                $elementClassFactory = new ElementClassFactory();
                $elementClass = $elementClassFactory->getElementClass($token->getTokenName());

                /**
                 * Calling class, which is responsible for processing this token
                 *
                 * @var string $compiledElement
                 */
                $compiledElement = $elementClass->process($token->getAttributes());

                /** Replacing tokens by PHP code */
                $templateString = str_replace($token->getFullToken(), $compiledElement, $templateString);
            }
        }

        return $templateString;
    }
}