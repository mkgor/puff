<?php


namespace Puff\Tokenization\Syntax;

/**
 * Class AbstractSyntax
 * @package Puff\Tokenization\Syntax
 */
abstract class AbstractSyntax implements SyntaxInterface
{
    /**
     * Returns an array, which contains opening and closing tags for element
     *
     * @return array
     */
    public function getElementTag(): array
    {
        return ["[%","%]"];
    }

    /**
     * Returns and array, which contains opening ald closing tags for variable
     *
     * @return array
     */
    public function getVariableTag(): array
    {
        return ["[[","]]"];
    }

    /**
     * Returns equality symbol
     *
     * @return string
     */
    public function getEqualitySymbol(): string
    {
        return "=";
    }

    /**
     * Returns symbol, which should separate filters
     *
     * @return string
     */
    public function getFilterSeparator(): string
    {
        return "~";
    }

    /**
     * @return string
     */
    public function buildFilterSeparatorRegex(): string
    {
        return "/(['\"]).*\1(*SKIP)(*F)|".$this->getFilterSeparator()."/";
    }
}