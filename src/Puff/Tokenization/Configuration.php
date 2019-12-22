<?php


namespace Puff\Tokenization;

/**
 * Class Grammar
 *
 * @package Tokenization
 */
class Configuration
{
    const KEYWORDS = [
        'import',
        'show',
        'use',
        'if',
        'for',
        'else',
        'end',
        'set'
    ];

    const EXPRESSION_SIGNATURE = ['[%','%]'];
    const PRINT_SIGNATURE = ['[[',']]'];

    const EQUALITY_SIGNATURE = '=';

    const FILTER_SPLIT_REGEXP = '/([\'"]).*\1(*SKIP)(*F)|~/';
}