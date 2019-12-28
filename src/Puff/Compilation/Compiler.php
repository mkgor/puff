<?php

namespace Puff\Compilation;

use Puff\Factory\ElementClassFactory;
use Puff\Exception\InvalidKeywordException;
use Puff\Exception\PuffException;
use Puff\Registry;
use Puff\Tokenization\Entity\Token;
use Puff\Tokenization\Repository\TokenRepositoryInterface;
use Puff\Tokenization\Syntax\SyntaxInterface;

/**
 * Class Compiler
 * @package Puff\Compilation
 */
class Compiler
{
    /**
     * @var SyntaxInterface
     */
    private $syntax;

    /**
     * Compiler constructor.
     */
    public function __construct()
    {
        $this->syntax = Registry::get('syntax');
    }

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

        $templateString = Registry::get($stringKey);

        $escapeSymbol = $this->escapeSlashes(preg_quote($this->syntax->getEscapeSymbol()));
        $templateString = preg_replace('/'.$escapeSymbol.'(?<tag>('.preg_quote($this->syntax->getElementTag()[0]).'|'.preg_quote($this->syntax->getVariableTag()[0]).'))/', '$1', $templateString);

        return $templateString;
    }

    /**
     * @param string $search
     * @param string $replace
     * @param string $text
     * @return mixed
     */
    private function replaceToken($search, $replace, $text)
    {
        $escapeSymbol = $this->escapeSlashes(preg_quote($this->syntax->getEscapeSymbol()));

        return preg_replace("/".$escapeSymbol.".+?(*SKIP)(*F)|".$this->escapeSlashes(preg_quote($search)) ."/", $replace, $text,1);
    }

    /**
     * @param $string
     *
     * @return string|string[]|null
     */
    private function escapeSlashes($string)
    {
        return preg_replace("/\//", '\\/', $string);
    }
}