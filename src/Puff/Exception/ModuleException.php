<?php


namespace Puff\Exception;


class ModuleException extends PuffException
{
    public function __construct($message = "", $code = 500)
    {
        parent::__construct($message, $code);
    }
}