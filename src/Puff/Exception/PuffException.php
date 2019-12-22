<?php

namespace Puff\Exception;

use Exception;

/**
 * Class PuffException
 * @package Exceptions
 * @codeCoverageIgnore
 */
class PuffException extends Exception
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