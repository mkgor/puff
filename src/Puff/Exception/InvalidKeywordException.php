<?php

namespace Puff\Exception;

use Puff\Tokenization\Grammar;

/**
 * Class InvalidKeywordException
 * @package Tokenization\Exceptions
 */
class InvalidKeywordException extends PuffException
{
    /**
     * InvalidArgumentException constructor.
     * @param $excepted
     * @param $got
     * @param $class
     * @param int $code
     */
    public function __construct($got, $class = 'template engine', $code = 500)
    {
        parent::__construct(sprintf("Invalid keyword passed to %s, expected %s - got %s", $class, implode(',', Grammar::KEYWORDS), $got), $code);
    }
}