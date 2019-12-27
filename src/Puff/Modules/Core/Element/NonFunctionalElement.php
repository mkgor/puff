<?php


namespace Puff\Modules\Core\Element;


use Puff\Compilation\Element\AbstractElement;

class NonFunctionalElement extends AbstractElement
{
    public function process(array $attributes)
    {
        return null;
    }
}