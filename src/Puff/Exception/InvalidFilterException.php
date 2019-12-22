<?php

namespace Puff\Exception;

/**
 * Class InvalidFilterException
 * @package Tokenization\Exceptions
 */
class InvalidFilterException extends PuffException
{
    /**
     * InvalidArgumentException constructor.
     *
     * @param $name
     * @param string $class
     * @param int $code
     */
    public function __construct($name, $class = 'template engine', $code = 500)
    {
        parent::__construct(sprintf("Calling of unknown filter %s",$name), $code);
    }
}