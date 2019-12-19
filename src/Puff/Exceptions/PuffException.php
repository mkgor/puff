<?php

namespace Puff\Exceptions;

use Throwable;

/**
 * Class PuffException
 * @package Exceptions
 */
class PuffException extends \Exception
{
    /**
     * PuffException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct($message = "", $code = 500)
    {
        parent::__construct(sprintf("Puff stopped script with message: %s", $message), $code);
    }
}