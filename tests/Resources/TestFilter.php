<?php

namespace Tests\Resources;

class TestFilter implements \Puff\Compilation\Filter\FilterInterface
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