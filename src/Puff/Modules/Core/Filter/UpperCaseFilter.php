<?php

namespace Puff\Modules\Core\Filter;

use Puff\Compilation\Filter\FilterInterface;

/**
 * Class UpperCaseFilter
 * @package Puff\Compilation\Filter
 */
class UpperCaseFilter implements FilterInterface
{
    /**
     * @param $variable
     * @param array $args
     * @return string|array
     */
    public static function handle($variable, ...$args) {
        mb_internal_encoding('UTF-8');

        if(!is_array($variable)) {
            return mb_strtoupper($variable);
        } else {
            array_walk_recursive($variable, function(&$item) {
                if(!is_array($item)) {
                    $item = mb_strtoupper($item);
                }
            });

            return $variable;
        }
    }
}