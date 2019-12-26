<?php


namespace Tests\Resources;


use Puff\Tokenization\Syntax\AbstractSyntax;

class TestSyntax extends AbstractSyntax
{
    public function getElementTag(): array
    {
        return ["[#","#]"];
    }
}