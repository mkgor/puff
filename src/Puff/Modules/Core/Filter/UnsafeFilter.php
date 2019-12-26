<?php

namespace Puff\Modules\Core\Filter;

use Puff\Compilation\Filter\FilterInterface;

/**
 * Class UnsafeFilter
 * @package Puff\Compilation\Filter
 */
class UnsafeFilter implements FilterInterface
{
    /**
     * @param $variable
     * @param array $args
     * @return string|array
     */
    public static function handle($variable, ...$args) {
        return $variable;
    }
}