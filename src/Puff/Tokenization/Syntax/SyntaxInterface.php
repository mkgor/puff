<?php


namespace Puff\Tokenization\Syntax;

/**
 * Interface SyntaxInterface
 * @package Puff\Tokenization\Syntax
 */
interface SyntaxInterface
{
    /**
     * Returns an array, which contains opening and closing tags for element
     *
     * @return array
     */
    public function getElementTag(): array;

    /**
     * Returns and array, which contains opening ald closing tags for variable
     *
     * @return array
     */
    public function getVariableTag(): array;

    /**
     * Returns equality symbol
     *
     * @return string
     */
    public function getEqualitySymbol(): string;

    /**
     * Returns symbol, which should separate filters
     *
     * @return string
     */
    public function getFilterSeparator(): string;

    /**
     * @return string
     */
    public function getEscapeSymbol(): string;

    /**
     * Build regular expression to recognize filters in variable tag
     *
     * @return string
     */
    public function buildFilterSeparatorRegex(): string;

}