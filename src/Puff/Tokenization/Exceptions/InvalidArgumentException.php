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
     * @param int $code
     */
    public function __construct($excepted, $got, $code = 500)
    {
        parent::__construct(sprintf("Invalid argument passed to %s, expected %s - got %s", get_called_class(), $excepted, $got), $code);
    }
}