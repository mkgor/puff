<?php

namespace Puff\Tokenization\Exceptions;

use Puff\Exceptions\PuffException;

/**
 * Class InvalidArgumentException
 * @package Tokenization\Exceptions
 */
class InvalidArgumentException extends PuffException
{
    /**
     * InvalidArgumentException constructor.
     * @param $excepted
     * @param $got
     * @param $class
     * @param int $code
     */
    public function __construct($excepted, $got, $class = 'template engine', $code = 500)
    {
        parent::__construct(sprintf("Invalid argument passed to %s, expected %s - got %s", $class, $excepted, $got), $code);
    }
}