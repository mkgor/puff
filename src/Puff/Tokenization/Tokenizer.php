<?php

namespace Puff\Tokenization;

use Puff\Factory\ElementClassFactory;
use Puff\Exception\InvalidKeywordException;
use Puff\Exception\PuffException;
use Puff\Registry;
use Puff\Tokenization\Entity\Token;
use Puff\Tokenization\Repository\TokenRepository;
use Puff\Tokenization\Repository\TokenRepositoryInterface;
use Puff\Tokenization\Syntax\SyntaxInterface;

/**
 * Class Tokenizer
 *
 * @package Puff\Tokenization
 */
class Tokenizer
{
    /**
     * @var TokenRepository
     */
    private $tokenRepository;

    public function __construct(TokenRepositoryInterface $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
        $this->tokenRepository->setContainer([]);
    }

    /**
     * Parses string and gets tokens from it
     *
     * @param $string
     *
     * @return TokenRepositoryInterface
     *
     * @throws InvalidKeywordException
     * @throws PuffException
     */
    public function tokenize(string $string)
    {
        /** @var SyntaxInterface $syntax */
        $syntax = Registry::get('syntax');

        $elementTag = $syntax->getElementTag();
        $variableTag = $syntax->getVariableTag();
        $escapeSymbol = preg_replace("/\//", '\\/', preg_quote($syntax->getEscapeSymbol()));

        $expressionsRegexp = $escapeSymbol.".+?(*SKIP)(*F)|". preg_quote($elementTag[0]) . "(.+?)" . preg_quote($elementTag[1]);
        $printRegexp = $escapeSymbol.".+?(*SKIP)(*F)|". preg_quote($variableTag[0]) . "(.+?)" . preg_quote($variableTag[1]);

        preg_match_all("/{$expressionsRegexp}/m", $string, $expressions, PREG_SET_ORDER, 0);
        preg_match_all("/{$printRegexp}/m", $string, $print, PREG_SET_ORDER, 0);

        foreach($expressions as $expression) {
            $tokenAttributes = explode(" ", trim($expression[1]));
            $tokenName = array_shift($tokenAttributes);

            $elementClassFactory = new ElementClassFactory();
            $elementClass = $elementClassFactory->getElementClass($tokenName);

            $tokenAttributesArray = $elementClass->handleAttributes($tokenAttributes);

            $token = new Token($tokenName, $tokenAttributesArray, $expression[0]);
            $this->tokenRepository->push($token);
        }

        foreach($print as $item) {
            if(preg_match($syntax->buildFilterSeparatorRegex(), $item[1])) {
                $itemExploded = preg_split($syntax->buildFilterSeparatorRegex(), $item[1]);

                $source = trim($itemExploded[0]);
                $filters = array_slice(array_map('trim', $itemExploded), 1);
            } else {
                $source = trim($item[1]);
                $filters = null;
            }

            $this->tokenRepository->push(new Token('show',[
                'data-source' => $source,
                'filters' => $filters
            ], $item[0]));
        }

        return $this->tokenRepository;
    }
}