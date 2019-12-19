<?php

namespace Puff\Tokenization;

use Puff\Tokenization\Entity\Token;
use Puff\Tokenization\Entity\TokenRepository;
use Puff\Tokenization\Exceptions\InvalidArgumentException;

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

    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * Parses string and gets tokens from it
     *
     * @param $string
     * @return TokenRepository
     * @throws InvalidArgumentException
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

            $tokenAttributesArray = [];

            foreach($tokenAttributes as $tokenAttribute) {
                list($attribute, $value) = explode(Grammar::EQUALITY_SIGNATURE, $tokenAttribute);

                $tokenAttributesArray[$attribute] = trim($value, '\'"');
            }

            $token = new Token($tokenName, $tokenAttributesArray);
            $this->tokenRepository->push($token);
        }

        return $this->tokenRepository;
    }
}