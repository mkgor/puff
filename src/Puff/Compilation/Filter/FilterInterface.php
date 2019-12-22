<?php


namespace Puff\Compilation\Filter;


interface FilterInterface
{
    /**
     * @param $variable
     * @param mixed ...$args
     * @return mixed
     */
    public static function handle($variable, ...$args);
}