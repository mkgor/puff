<?php

namespace Puff\Tokenization;

use Puff\Compilation\Element\ElementInterface;
use Puff\Exception\InvalidKeywordException;
use Puff\Exception\PuffException;
use Puff\Tokenization\Entity\Token;
use Puff\Tokenization\Repository\TokenRepository;
use Puff\Exception\InvalidArgumentException;
use Puff\Tokenization\Repository\TokenRepositoryInterface;

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
    }

    /**
     * Parses string and gets tokens from it
     *
     * @param $string
     *
     * @return TokenRepositoryInterface
     *
     * @throws InvalidArgumentException
     * @throws InvalidKeywordException
     * @throws PuffException
     */
    public function tokenize($string)
    {
        if(!is_string($string)) {
            throw new InvalidArgumentException('string', gettype($string));
        }

        $expressionsRegexp = preg_quote(Grammar::EXPRESSION_SIGNATURE[0]) . "(.+?)" . preg_quote(Grammar::EXPRESSION_SIGNATURE[1]);
        $printRegexp = preg_quote(Grammar::PRINT_SIGNATURE[0]) . "(.+?)" . preg_quote(Grammar::PRINT_SIGNATURE[1]);

        preg_match_all("/{$expressionsRegexp}/m", $string, $expressions, PREG_SET_ORDER, 0);
        preg_match_all("/{$printRegexp}/m", $string, $print, PREG_SET_ORDER, 0);

        foreach($expressions as $expression) {
            $tokenAttributes = explode(" ", $expression[1]);
            $tokenName = array_shift($tokenAttributes);

            $elementClass = "\Puff\Compilation\Element\\" . $tokenName ."Element";

            if(!class_exists($elementClass)) {
                throw new InvalidKeywordException($tokenName, __CLASS__);
            }

            /** @var ElementInterface $elementClass */
            $elementClass = new $elementClass;

            if(!($elementClass instanceof ElementInterface)) {
                throw new PuffException('Invalid element provided to compiler');
            }

            if(!in_array($tokenName,Grammar::KEYWORDS)) {
                throw new InvalidKeywordException($tokenName, __CLASS__);
            }

            $tokenAttributesArray = $elementClass->handleAttributes($tokenAttributes);

            $token = new Token($tokenName, $tokenAttributesArray, $expression[0]);
            $this->tokenRepository->push($token);
        }

        foreach($print as $item) {
            $this->tokenRepository->push(new Token('show',[
                'data-source' => $item[1]
            ], $item[0]));
        }

        return $this->tokenRepository;
    }
}