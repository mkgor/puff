<?php


namespace Puff\Tokenization;

/**
 * Class Grammar
 *
 * @package Tokenization
 */
class Grammar
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

    const HEAD_SIGNATURE = ['@head','@endhead'];
    const EQUALITY_SIGNATURE = '=';
}