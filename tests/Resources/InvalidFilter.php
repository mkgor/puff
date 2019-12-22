<?php

namespace Tests\Resources;

class InvalidFilter
{

    /**
     * @param $variable
     * @param mixed ...$args
     * @return mixed
     */
    public static function handle($variable, ...$args)
    {
        return 'filter';
    }
}