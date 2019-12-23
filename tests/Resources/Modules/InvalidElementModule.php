<?php


namespace Tests\Resources\Modules;


use Puff\Modules\ModuleInterface;
use Tests\Resources\InvalidFilter;

class InvalidElementModule implements ModuleInterface
{

    /**
     * Returns an array of elements and filters which will be initialized
     *
     * @return array
     */
    public function setUp(): array
    {
        return [
            'elements' => [
                'invalid_element' => new InvalidElement()
            ]
        ];
    }
}